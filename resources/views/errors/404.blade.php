@php
    $code        = 404;
    $icon        = 'bi-search';
    $title       = 'Halaman Tidak Ditemukan';
    $description = 'Maaf, halaman yang kamu cari <strong>tidak dapat ditemukan</strong>. Mungkin alamatnya salah ketik, sudah dipindahkan, atau memang tidak ada. Coba kembali ke beranda ya!';
    $showBack    = true;
@endphp
@include('errors.error')
