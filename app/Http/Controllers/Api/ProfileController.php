<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InformasiOutlet;
use App\Models\MediaQr; 
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->partner) {
            return response()->json(['error' => 'Akses ditolak: Akun belum terhubung dengan Partner.'], 403);
        }

        $partnerId = $user->partner->id;
        $informasi = InformasiOutlet::where('partner_id', $partnerId)->first();
        $media = $informasi ? MediaQr::where('informasi_id', $informasi->id)->first() : null;

        $publicUrl = ($informasi && !empty($informasi->page_name)) ? url('/' . $informasi->page_name) : null;

        return response()->json([
            'status' => 'success',
            'data' => [
                'informasi' => $informasi,
                'media' => $media,
                'public_url' => $publicUrl
            ]
        ], 200);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->partner) {
            return response()->json(['error' => 'Gagal menyimpan! Akun Anda belum terhubung dengan data Partner.'], 403);
        }

        $partner_id = $user->partner->id;
        $informasi = InformasiOutlet::where('partner_id', $partner_id)->first();
        $informasiId = $informasi ? $informasi->id : 'NULL';

        // Validasi Data
        $validator = Validator::make($request->all(), [
            'logo'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            'nama_outlet'    => 'required|string|max:255',
            'jenis_outlet'   => 'required|in:cafe,restoran,lainnya',
            'jenis_lainnya'  => 'required_if:jenis_outlet,lainnya|nullable|string|max:255',
            'page_name'      => 'nullable|string|max:255|unique:informasi_outlet,page_name,' . $informasiId,
            'foto_outlet'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            'deskripsi'      => 'nullable|string',
            'alamat_lengkap' => 'nullable|string',
            'kota'           => 'nullable|string|max:255',
            'provinsi'       => 'nullable|string|max:255',
            'maps'           => 'nullable|url|max:500',
            'facebook'       => 'nullable|string|max:255',
            'instagram'      => 'nullable|string|max:255',
            'youtube'        => 'nullable|string|max:255',
            'tiktok'         => 'nullable|string|max:255',
            'x'              => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $validatedData = $validator->validated();

        if (!$informasi) {
            $informasi = new InformasiOutlet();
            $informasi->partner_id = $partner_id;
        }

        // --- PROSES UPLOAD & KOMPRES LOGO ---
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($informasi->logo) {
                Storage::disk('public')->delete('logo/' . $informasi->logo);
            }
            
            $logoName = time() . '_logo.' . $request->logo->extension();
            // Disk 'public' root-nya storage/app/public — simpan ke subfolder logo/
            $destination = storage_path('app/public/logo/' . $logoName);
            
            // Kompres gambar 60%
            $this->compressImage($request->file('logo'), $destination, 60);
            $informasi->logo = $logoName;
        }

        // --- PROSES UPLOAD & KOMPRES FOTO OUTLET ---
        if ($request->hasFile('foto_outlet')) {
            // Hapus foto lama jika ada
            if ($informasi->foto_outlet) {
                Storage::disk('public')->delete('outlet/' . $informasi->foto_outlet);
            }
            
            $fotoName = time() . '_foto.' . $request->foto_outlet->extension();
            // Disk 'public' root-nya storage/app/public — simpan ke subfolder outlet/
            $destination = storage_path('app/public/outlet/' . $fotoName);
            
            // Kompres gambar 60%
            $this->compressImage($request->file('foto_outlet'), $destination, 60);
            $informasi->foto_outlet = $fotoName;
        }

        // Update Field Text
        $informasi->nama_outlet    = $validatedData['nama_outlet'];
        $informasi->jenis_outlet   = $validatedData['jenis_outlet'];
        $informasi->jenis_lainnya  = $validatedData['jenis_outlet'] === 'lainnya' ? ($validatedData['jenis_lainnya'] ?? null) : null;
        if (!empty($validatedData['page_name'])) {
            $informasi->page_name  = Str::slug($validatedData['page_name']); 
        }
        $informasi->deskripsi      = $validatedData['deskripsi'] ?? null;
        $informasi->alamat_lengkap = $validatedData['alamat_lengkap'] ?? null;
        $informasi->kota           = $validatedData['kota'] ?? null;
        $informasi->provinsi       = $validatedData['provinsi'] ?? null;
        $informasi->maps           = $validatedData['maps'] ?? null;
        $informasi->save();

        // Simpan Data Media Sosial (MediaQr)
        $media = MediaQr::where('informasi_id', $informasi->id)->first();
        if (!$media) {
            $media = new MediaQr();
            $media->informasi_id = $informasi->id;
        }
        
        $media->facebook  = $validatedData['facebook'] ?? null;
        $media->instagram = $validatedData['instagram'] ?? null;
        $media->youtube   = $validatedData['youtube'] ?? null;
        $media->tiktok    = $validatedData['tiktok'] ?? null;
        $media->x         = $validatedData['x'] ?? null;
        $media->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Informasi outlet dan media sosial berhasil disimpan!'
        ], 200);
    }

    public function downloadQr(Request $request)
    {
        // Mendukung request melalui Query string (Untuk token yang dilempar dari Flutter URL Download)
        $user = $request->user();
        if (!$user && $request->has('token')) {
            $token = \Laravel\Sanctum\PersonalAccessToken::findToken($request->token);
            if ($token) {
                $user = $token->tokenable;
            }
        }

        if (!$user || !$user->partner) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $informasi = InformasiOutlet::where('partner_id', $user->partner->id)->first();
        if (!$informasi || empty($informasi->page_name)) {
            return response()->json(['error' => 'URL Halaman Outlet belum diset.'], 404);
        }

        $url = url('/' . $informasi->page_name);
        $qrCode = QrCode::format('png')->size(500)->generate($url);
        $fileName = 'QRCode_' . $informasi->page_name . '.png';
        
        return response($qrCode)
            ->header('Content-type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    // Fungsi Helper PHP GD (Sama Persis)
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