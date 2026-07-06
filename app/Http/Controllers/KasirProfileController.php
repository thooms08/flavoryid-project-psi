<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InformasiOutlet;
use App\Models\Partner;
use App\Models\MediaQr; 
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KasirProfileController extends Controller
{
    public function edit()
    {
        // Panggil menggunakan relasi yang sudah kita buat
        $partner = Auth::user()->partner;

        $informasi = null;
        $media = null; 
        
        if ($partner) {
            $informasi = InformasiOutlet::where('partner_id', $partner->id)->first();
            
            // Jika informasi outlet sudah ada, ambil relasi media sosialnya
            if ($informasi) {
                $media = MediaQr::where('informasi_id', $informasi->id)->first();
            }
        }

        return view('kasir.profile-outlet', compact('informasi', 'media'));
    }

    public function update(Request $request)
    {
        $partner = Auth::user()->partner;

        if (!$partner) {
            return redirect()->back()->with('error', 'Gagal menyimpan! Akun Anda belum terhubung dengan data Partner.');
        }

        $partner_id = $partner->id;
        
        // Cari informasi outlet yang sudah ada (untuk keperluan validasi unique)
        $informasi = InformasiOutlet::where('partner_id', $partner_id)->first();
        $informasiId = $informasi ? $informasi->id : 'NULL'; // Jika belum ada, set NULL

        // Validasi Data
        $validatedData = $request->validate([
            'logo'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_outlet'    => 'required|string|max:255',
            'jenis_outlet'   => 'required|in:cafe,restoran,lainnya',
            'jenis_lainnya'  => 'required_if:jenis_outlet,lainnya|nullable|string|max:255',
            // KUNCI PENTING: page_name HARUS UNIQUE agar URL tidak bentrok antar toko!
            'page_name'      => 'nullable|string|max:255|unique:informasi_outlet,page_name,' . $informasiId,
            'foto_outlet'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi'      => 'nullable|string',
            'alamat_lengkap' => 'nullable|string',
            'kota'           => 'nullable|string|max:255',
            'provinsi'       => 'nullable|string|max:255',
            'maps'           => 'nullable|url|max:500',
            // Validasi Sosmed
            'facebook'       => 'nullable|string|max:255',
            'instagram'      => 'nullable|string|max:255',
            'youtube'        => 'nullable|string|max:255',
            'tiktok'         => 'nullable|string|max:255',
            'x'              => 'nullable|string|max:255',
        ], [
            'nama_outlet.required' => 'Nama outlet wajib diisi.',
            'jenis_outlet.required' => 'Pilih salah satu jenis outlet.',
            'jenis_lainnya.required_if' => 'Kolom jenis lainnya wajib diisi jika Anda memilih "Lainnya".',
            'page_name.unique' => 'Custom URL ini sudah dipakai oleh outlet lain. Silakan pilih nama URL yang berbeda.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
            'foto_outlet.max' => 'Ukuran foto outlet maksimal 2MB.',
        ]);

        // Simpan Informasi Outlet
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
            
            // Kompres gambar dengan kualitas 60%
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
            
            // Kompres gambar dengan kualitas 60%
            $this->compressImage($request->file('foto_outlet'), $destination, 60);
            
            $informasi->foto_outlet = $fotoName;
        }

        $informasi->nama_outlet    = $validatedData['nama_outlet'];
        $informasi->jenis_outlet   = $validatedData['jenis_outlet'];
        $informasi->jenis_lainnya  = $validatedData['jenis_outlet'] === 'lainnya' ? $validatedData['jenis_lainnya'] : null;
        $informasi->page_name      = Str::slug($validatedData['page_name']); 
        $informasi->deskripsi      = $validatedData['deskripsi'];
        $informasi->alamat_lengkap = $validatedData['alamat_lengkap'];
        $informasi->kota           = $validatedData['kota'];
        $informasi->provinsi       = $validatedData['provinsi'];
        $informasi->maps           = $validatedData['maps'];

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

        return redirect()->back()->with('success', 'Informasi outlet dan media sosial berhasil disimpan!');
    }

    public function downloadQr()
    {
        $partner = Auth::user()->partner;
        
        if (!$partner) {
            return redirect()->back()->with('error', 'Gagal memuat QR! Akun belum terhubung dengan data Partner.');
        }

        $informasi = InformasiOutlet::where('partner_id', $partner->id)->first();
        
        if (!$informasi || empty($informasi->page_name)) {
            return redirect()->back()->with('error', 'URL Halaman Outlet belum diset. Silakan isi dan simpan Tautan Halaman (URL) terlebih dahulu.');
        }

        // Generate URL Publik
        $url = url('/' . $informasi->page_name);
        
        // Generate QR Code format PNG
        $qrCode = QrCode::format('png')->size(300)->generate($url);
        
        $fileName = 'QRCode_' . $informasi->page_name . '.png';
        
        return response($qrCode)
            ->header('Content-type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Fungsi Helper untuk mengompres gambar menggunakan PHP GD Library
     */
    private function compressImage($file, $destination, $quality = 60)
    {
        // Dapatkan info MIME type gambar
        $info = getimagesize($file->getRealPath());
        $mime = $info['mime'];

        // Buat resource gambar berdasarkan ekstensi
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($file->getRealPath());
                // Simpan & Kompres JPG/JPEG
                imagejpeg($image, $destination, $quality);
                break;
                
            case 'image/png':
                $image = imagecreatefrompng($file->getRealPath());
                
                // Pertahankan transparansi (alpha channel) pada PNG
                imagealphablending($image, false);
                imagesavealpha($image, true);
                
                // Skala kompresi PNG di PHP GD adalah 0 (tanpa kompresi) hingga 9 (kompresi max)
                $compression = 9 - round(($quality / 100) * 9);
                imagepng($image, $destination, $compression);
                break;
                
            default:
                // Jika format tidak dikenali (fallback), gunakan move bawaan Laravel (tanpa kompresi)
                move_uploaded_file($file->getRealPath(), $destination);
                return;
        }

        // Bersihkan memori server
        imagedestroy($image);
    }
}