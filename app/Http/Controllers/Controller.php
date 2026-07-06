<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

abstract class Controller
{
    /**
     * Kompres dan simpan gambar ke path tujuan.
     * Mendukung JPEG dan PNG. Folder tujuan dibuat otomatis jika belum ada.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $directory   Path direktori tujuan (misal: storage_path('app/public/logo'))
     * @param  string  $fileName    Nama file tujuan (misal: '1234_logo.jpg')
     * @param  int     $quality     Kualitas kompresi 0–100 (default 60)
     * @return string               Nama file yang disimpan
     */
    protected function compressAndStore($file, string $directory, string $fileName, int $quality = 60): string
    {
        // Pastikan folder tujuan sudah ada
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $destination = $directory . DIRECTORY_SEPARATOR . $fileName;
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
                // Format tidak dikenali — simpan tanpa kompresi
                move_uploaded_file($file->getRealPath(), $destination);
                return $fileName;
        }

        imagedestroy($image);

        return $fileName;
    }
}
