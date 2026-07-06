<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\VariantLevel; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class KasirMenuController extends Controller {
    
    public function index() {
        $partner   = Auth::user()->partner;
        $partnerId = $partner->id;

        // Cache daftar menu 5 menit — di-invalidate saat ada perubahan menu
        $menus = Cache::remember("menu_list_{$partnerId}", 300, function () use ($partnerId) {
            return Menu::where('partner_id', $partnerId)
                ->with('variants')
                ->orderBy('created_at', 'desc')  // menu terbaru selalu paling atas
                ->get();
        });
        
        $paket = $partner->outlet->mediaQr->paket ?? 'gratis';
        $limit = 500;

        return view('kasir.gallery-menu', compact('menus', 'limit', 'paket'));
    }

    public function store(Request $request) {
        $request->validate([
            'menus' => 'required|array|max:20',
            'menus.*.nama_menu' => 'required|string|max:255',
            'menus.*.harga' => 'required|numeric|min:0',
            'menus.*.gambar' => 'nullable|image|max:2048',
            'menus.*.deskripsi' => 'nullable|string',
            'menus.*.stok_menu' => 'nullable|integer', 
            'menus.*.variants' => 'nullable|array', 
            'menus.*.variants.*.variant' => 'nullable|string|max:255',
            'menus.*.variants.*.harga' => 'nullable|numeric|min:0',
            'kategori' => 'nullable|string',
            'tipe_menu' => 'required|in:utama,best_seller',
        ]);

        $partner = Auth::user()->partner;
        $partnerId = $partner->id;

        // Aturan baru: Semua paket maksimal 500 menu
        $limit = 500;

        $currentMenuCount = Menu::where('partner_id', $partnerId)->count();
        $incomingMenuCount = count($request->menus);

        if (($currentMenuCount + $incomingMenuCount) > $limit) {
            $sisaKuota = $limit - $currentMenuCount;
            // Pesan error disesuaikan agar lebih umum
            return redirect()->back()->with('error', "Kuota menu penuh! Sistem saat ini membatasi maksimal $limit menu untuk setiap outlet. Sisa kuota Anda: $sisaKuota menu.");
        }

        try {
            DB::beginTransaction();
            $kategori = $request->kategori ?? 'F&B';

            // Pastikan folder storage/app/public/menus exists
            Storage::disk('public')->makeDirectory('menus');

            foreach ($request->menus as $item) {
                $path = null;
                
                // --- PROSES UPLOAD & KOMPRES GAMBAR SAAT STORE ---
                if (isset($item['gambar'])) {
                    $file = $item['gambar'];
                    $name = time() . '_' . Str::random(5) . '_' . Str::slug($item['nama_menu']) . '.' . $file->extension();
                    $destination = storage_path('app/public/menus/' . $name);
                    
                    // Gunakan kompresi dengan kualitas 60%
                    $this->compressImage($file, $destination, 60);
                    
                    $path = 'menus/' . $name;
                }

                $menu = Menu::create([
                    'partner_id' => $partnerId,
                    'nama_menu' => $item['nama_menu'],
                    'harga' => $item['harga'],
                    'deskripsi'  => !empty($item['deskripsi']) ? $item['deskripsi'] : null,
                    'stok_menu'  => (isset($item['stok_menu']) && $item['stok_menu'] !== '') ? $item['stok_menu'] : null,
                    'kategori' => $kategori,
                    'tipe_menu' => $request->tipe_menu,
                    'gambar' => $path,
                ]);

                // Simpan Variants jika ada
                if (!empty($item['variants'])) {
                    foreach ($item['variants'] as $v) {
                        if (!empty($v['variant'])) { 
                            $menu->variants()->create([
                                'variant' => $v['variant'],
                                'harga' => $v['harga'] ?? 0,
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            $pageName = $partner->outlet->page_name ?? '';
            Cache::forget("menu_list_{$partnerId}");
            Cache::forget("outlet_page_{$pageName}");
            return redirect()->back()->with('success', 'Berhasil menambahkan ' . $incomingMenuCount . ' menu baru!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function search(Request $request) {
        $query = $request->query('q'); 
        $partnerId = Auth::user()->partner->id;
        
        $menus = Menu::where('partner_id', $partnerId)
            ->with('variants')
            ->where(function($q) use ($query) {
                if (!empty($query)) {
                    $q->where('nama_menu', 'LIKE', "%$query%")
                      ->orWhere('harga', 'LIKE', "%$query%")
                      ->orWhere('kategori', 'LIKE', "%$query%")
                      ->orWhere('deskripsi', 'LIKE', "%$query%");
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'html' => view('kasir.partials.list-menu-rows', compact('menus'))->render()
        ]);
    }

    public function update(Request $request, $id) {
        $menu = Menu::findOrFail($id);
        
        $rules = [
            'nama_menu'   => 'sometimes|string|max:255',
            'harga'       => 'sometimes|numeric|min:0',
            'status_menu' => 'sometimes|in:tersedia,habis',
            'stok_menu'   => 'nullable|integer',
            'deskripsi'   => 'nullable|string',
            'kategori'    => 'nullable|string',
            'tipe_menu'   => 'sometimes|in:utama,best_seller',
            'gambar'      => 'nullable|image|max:2048',
            'variants'    => 'nullable|array', 
        ];

        $validated = $request->validate($rules);

        try {
            DB::beginTransaction();

            if (isset($validated['stok_menu']) && $validated['stok_menu'] === 0) {
                $validated['status_menu'] = 'habis';
            }

            // --- PROSES UPLOAD & KOMPRES GAMBAR SAAT UPDATE ---
            if ($request->hasFile('gambar')) {
                // Hapus file lama jika ada
                if ($menu->gambar) {
                    Storage::disk('public')->delete($menu->gambar);
                }

                // Pastikan folder ada
                Storage::disk('public')->makeDirectory('menus');

                $file = $request->file('gambar');
                $name = time() . '_' . Str::slug($request->nama_menu ?? $menu->nama_menu) . '.' . $file->extension();
                $destination = storage_path('app/public/menus/' . $name);
                
                // Gunakan kompresi dengan kualitas 60%
                $this->compressImage($file, $destination, 60);
                
                $validated['gambar'] = 'menus/' . $name;
            }

            $menu->update($validated);

            // Update/Sync Variants
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
            $partnerId2 = Auth::user()->partner->id;
            $pageName   = Auth::user()->partner->outlet->page_name ?? '';
            Cache::forget("menu_list_{$partnerId2}");
            Cache::forget("outlet_page_{$pageName}");
            return response()->json(['success' => true, 'message' => 'Menu berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id) {
        try {
            $menu = Menu::findOrFail($id);
            if ($menu->gambar) {
                Storage::disk('public')->delete($menu->gambar);
            }
            $menu->variants()->delete();
            $menu->delete();

            $partnerId = Auth::user()->partner->id;
            $pageName  = Auth::user()->partner->outlet->page_name ?? '';
            Cache::forget("menu_list_{$partnerId}");
            Cache::forget("outlet_page_{$pageName}");
            return response()->json(['success' => true, 'message' => 'Menu berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus menu'], 500);
        }
    }

    /**
     * Fungsi Helper untuk mengompres gambar menggunakan PHP GD Library
     */
    private function compressImage($file, $destination, $quality = 60)
    {
        $info = getimagesize($file->getRealPath());
        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($file->getRealPath());
                imagejpeg($image, $destination, $quality);
                break;
                
            case 'image/png':
                $image = imagecreatefrompng($file->getRealPath());
                imagealphablending($image, false);
                imagesavealpha($image, true);
                $compression = 9 - round(($quality / 100) * 9);
                imagepng($image, $destination, $compression);
                break;
                
            default:
                move_uploaded_file($file->getRealPath(), $destination);
                return;
        }

        imagedestroy($image);
    }
}