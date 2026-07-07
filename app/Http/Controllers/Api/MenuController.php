<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            
            // 1. Cek dulu apakah user punya relasi partner
            if (!$user || !$user->partner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data partner tidak ditemukan untuk akun ini.'
                ], 404);
            }

            $partnerId = $user->partner->id;
            $query = $request->query('q');

            // Hanya cache jika tidak ada filter pencarian (search bersifat dinamis)
            if (empty($query)) {
                $menus = Cache::remember("menu_list_{$partnerId}", 300, function () use ($partnerId) {
                    return \App\Models\Menu::where('partner_id', $partnerId)
                        ->with('variants')
                        ->orderBy('created_at', 'desc')
                        ->get();
                });
            } else {
                $menus = \App\Models\Menu::where('partner_id', $partnerId)
                    ->with('variants')
                    ->where(function ($q) use ($query) {
                        $q->where('nama_menu', 'LIKE', "%$query%")
                          ->orWhere('harga', 'LIKE', "%$query%")
                          ->orWhere('kategori', 'LIKE', "%$query%")
                          ->orWhere('deskripsi', 'LIKE', "%$query%");
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            return response()->json([
                'success' => true,
                'data' => $menus
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Error di Server: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'menus' => 'required|array|max:20',
            'menus.*.nama_menu' => 'required|string|max:255',
            'menus.*.harga' => 'required|numeric|min:0',
            'menus.*.gambar' => 'nullable|image|max:5120', // Maks 5MB upload sblm di kompres
            'kategori' => 'nullable|string',
            'tipe_menu' => 'required|in:utama,best_seller',
        ]);

        $partnerId = $request->user()->partner->id;
        
        try {
            DB::beginTransaction();
            $kategori = $request->kategori ?? 'F&B';

            foreach ($request->menus as $item) {
                $path = null;
                
                // Proses Gambar
                if (isset($item['gambar']) && $item['gambar'] instanceof \Illuminate\Http\UploadedFile) {
                    $file = $item['gambar'];
                    $name = time() . '_' . Str::random(5) . '_' . Str::slug($item['nama_menu']) . '.' . $file->extension();
                    $this->compressAndStore($file, 'menus', $name, 60);
                    $path = $name;
                }

                $menu = Menu::create([
                    'partner_id' => $partnerId,
                    'nama_menu'  => $item['nama_menu'],
                    'harga'      => $item['harga'],
                    'deskripsi'  => $item['deskripsi'] ?? null,
                    'stok_menu'  => $item['stok_menu'] ?? null,
                    'kategori'   => $kategori,
                    'tipe_menu'  => $request->tipe_menu,
                    'gambar'     => $path,
                ]);

                // Proses Variant jika ada
                if (!empty($item['variants'])) {
                    foreach ($item['variants'] as $v) {
                        if (!empty($v['variant'])) {
                            $menu->variants()->create([
                                'variant' => $v['variant'],
                                'harga'   => $v['harga'] ?? 0,
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            Cache::forget("menu_list_{$partnerId}");
            return response()->json(['success' => true, 'message' => 'Menu berhasil ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Fungsi update ditambahkan untuk melayani endpoint edit dari Flutter
    public function update(Request $request, $id)
    {
        $partnerId = $request->user()->partner->id;

        try {
            DB::beginTransaction();
            $menu = Menu::where('partner_id', $partnerId)->findOrFail($id);
            
            $validated = $request->validate([
                'nama_menu'   => 'sometimes|string|max:255',
                'harga'       => 'sometimes|numeric|min:0',
                'status_menu' => 'sometimes|in:tersedia,habis',
                'stok_menu'   => 'nullable|integer',
                'deskripsi'   => 'nullable|string',
                'kategori'    => 'nullable|string',
                'tipe_menu'   => 'sometimes|in:utama,best_seller',
                'gambar'      => 'nullable|image|max:5120',
                'variants'    => 'nullable|array', 
            ]);

            // Jika stok 0, otomatis status jadi habis
            if (isset($validated['stok_menu']) && $validated['stok_menu'] == 0) {
                $validated['status_menu'] = 'habis';
            }

            // Proses Update Gambar
            if ($request->hasFile('gambar')) {
                if ($menu->gambar) {
                    $oldPath = public_path('assets/menus/' . $menu->gambar);
                    if (\Illuminate\Support\Facades\File::exists($oldPath)) \Illuminate\Support\Facades\File::delete($oldPath);
                }
                $file = $request->file('gambar');
                $name = time() . '_' . Str::random(5) . '_' . Str::slug($request->nama_menu ?? $menu->nama_menu) . '.' . $file->extension();
                $this->compressAndStore($file, 'menus', $name, 60);
                $validated['gambar'] = $name;
            }

            $menu->update($validated);

            // Update Variants
            if ($request->has('variants')) {
                $menu->variants()->delete(); 
                foreach ($request->variants as $v) {
                    if (!empty($v['variant'])) {
                        $menu->variants()->create([
                            'variant' => $v['variant'],
                            'harga' => $v['harga'] ?? 0,
                        ]);
                    }
                }
            }

            DB::commit();
            Cache::forget("menu_list_{$partnerId}");
            return response()->json(['success' => true, 'message' => 'Menu berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $menu = Menu::where('partner_id', $request->user()->partner->id)->findOrFail($id);
            
            if ($menu->gambar) {
                $oldPath = public_path('assets/menus/' . $menu->gambar);
                if (\Illuminate\Support\Facades\File::exists($oldPath)) \Illuminate\Support\Facades\File::delete($oldPath);
            }
            
            $menu->variants()->delete();
            $menu->delete();

            Cache::forget("menu_list_{$request->user()->partner->id}");
            return response()->json(['success' => true, 'message' => 'Menu dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal hapus menu'], 500);
        }
    }
}