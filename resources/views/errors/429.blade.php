@php
    $code        = 429;
    $icon        = 'bi-speedometer2';
    $title       = 'Terlalu Banyak Permintaan';
    $description = 'Sepertinya kamu <strong>mengirim terlalu banyak permintaan</strong> dalam waktu singkat. Tunggu sebentar, lalu coba lagi ya!';
    $showBack    = true;
@endphp
@include('errors.error')
