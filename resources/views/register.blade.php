<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Partner | Flavory.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('logo-512.png') }}">
    <link rel="apple-touch-icon" sizes="512x512" href="{{ asset('logo-512.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('logo-512.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('logo-512.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('logo-512.png') }}">
    
    <style>
        body { font-family: 'Poppins', sans-serif; overflow-x: hidden; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(15px);
        }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #fff7ed; }
        ::-webkit-scrollbar-thumb { background: #fb923c; border-radius: 10px; }
        
        [x-cloak] { display: none !important; }
    </style>
</head>
@include('layouts.wa')
<body class="bg-orange-50 text-slate-800 antialiased min-h-screen flex flex-col items-center justify-center py-8">

   <div class="w-full max-w-md md:max-w-3xl px-4 md:px-6" 
     x-data="{ step: 1, showPass: false, paket: '{{ old('paket', 'gratis') }}', jenis: '{{ old('jenis_outlet', '') }}', imageUrl: null, usernameError: '', pageNameError: '', passwordError: '', confirmPasswordError: '', passwordValue: '' }" x-cloak>
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 md:w-14 md:h-14 bg-orange-500 rounded-2xl shadow-lg mb-3 transform">
                <i class="bi bi-shop text-white text-xl md:text-2xl"></i>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-orange-600 tracking-tight">Flavory<span class="text-slate-900">.id</span></h1>
            <p class="text-slate-500 mt-1 font-medium text-xs md:text-sm">Daftarkan bisnis kulinermu menuju ekosistem digital</p>
        </div>

        <div class="flex items-center justify-between mb-8 px-2 max-w-xl mx-auto">
            <template x-for="i in 4">
                <div class="flex items-center" :class="i < 4 ? 'flex-1' : ''">
                    
                    <div :class="step >= i ? 'bg-orange-500 text-white shadow-lg shadow-orange-200 ring-4 ring-orange-100' : 'bg-white text-slate-400 border border-slate-200'" 
                        class="w-8 h-8 md:w-10 md:h-10 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-sm transition-all duration-500 z-10">
                        <span x-text="i"></span>
                    </div>

                    <div x-show="i < 4" class="w-full h-1 bg-slate-200 overflow-hidden">
                        <div class="h-full bg-orange-500 transition-all duration-700 ease-in-out" 
                            :style="'width: ' + (step > i ? '100%' : '0%')">
                        </div>
                    </div>
                    
                </div>
            </template>
        </div>

        <div class="glass-effect rounded-[2rem] md:rounded-3xl shadow-2xl overflow-hidden border border-white">
            <form action="/register" method="POST" enctype="multipart/form-data" class="p-6 md:p-8" id="registerForm">
                @csrf
                
                @if ($errors->any() || session('error'))
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
                        <div class="flex items-start">
                            <i class="bi bi-exclamation-circle-fill text-red-500 mt-0.5 mr-3"></i>
                            <div>
                                <h3 class="text-sm font-bold text-red-800">Pendaftaran Tertunda:</h3>
                                <ul class="mt-1 list-disc list-inside text-xs text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                    @if(session('error'))
                                        <li>{{ session('error') }}</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

               <div id="step1" x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                    <div class="mb-6">
                        <h2 class="text-xl md:text-2xl font-bold text-slate-900">Buat Akun</h2>
                        <p class="text-slate-500 text-xs md:text-sm mt-1">Langkah pertama untuk mengakses dashboard kasir Anda.</p>
                    </div>
                    
                    <div class="space-y-4 md:space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                            <div class="md:col-span-2">
                                <label class="block text-xs md:text-sm font-semibold text-slate-700 mb-1">Username</label>
                                <div class="relative group">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400"><i class="bi bi-person"></i></span>
                                    <input type="text" name="username" value="{{ old('username') }}" placeholder="outlet_keren" required 
                                        class="w-full pl-11 pr-4 py-2.5 md:py-3 bg-slate-50 border rounded-xl focus:ring-4 outline-none text-sm transition-all"
                                        :class="usernameError ? 'border-red-500 focus:ring-red-100' : 'border-slate-200 focus:ring-orange-100 focus:border-orange-500'"
                                        @input.debounce.500ms="
                                            if($event.target.value.length > 2) {
                                                fetch('/check-availability?type=username&value=' + $event.target.value)
                                                .then(res => res.json())
                                                .then(data => { usernameError = data.available ? '' : 'Username sudah terpakai, cari yang lain!'; });
                                            } else { usernameError = ''; }
                                        ">
                                </div>
                                <p x-show="usernameError" x-text="usernameError" class="text-red-500 text-xs mt-1 font-semibold" x-transition></p>
                            </div>

                            <div>
                                <label class="block text-xs md:text-sm font-semibold text-slate-700 mb-1">Password</label>
                                <div class="relative">
                                    <input :type="showPass ? 'text' : 'password'" name="password" x-model="passwordValue" placeholder="••••••••" required 
                                        class="w-full px-4 py-2.5 md:py-3 bg-slate-50 border rounded-xl focus:ring-4 outline-none text-sm transition-all"
                                        :class="passwordError ? 'border-red-500 focus:ring-red-100' : 'border-slate-200 focus:ring-orange-100 focus:border-orange-500'"
                                        @input="
                                            if (passwordValue.length === 0) {
                                                passwordError = '';
                                            } else if (passwordValue.length < 8) {
                                                passwordError = 'Password minimal 8 karakter.';
                                            } else if (!/[a-zA-Z]/.test(passwordValue)) {
                                                passwordError = 'Harus mengandung minimal 1 huruf.';
                                            } else if (!/[0-9]/.test(passwordValue)) {
                                                passwordError = 'Harus mengandung minimal 1 angka.';
                                            } else {
                                                passwordError = '';
                                            }
                                            
                                            // Cek ulang otomatis kalau password bawah sudah terisi
                                            let confirmInput = document.querySelector('input[name=password_confirmation]');
                                            if (confirmInput && confirmInput.value !== '') {
                                                if (confirmInput.value !== passwordValue) {
                                                    confirmPasswordError = 'Password tidak cocok.';
                                                } else {
                                                    confirmPasswordError = '';
                                                }
                                            }
                                        ">
                                </div>
                                <p x-show="passwordError" x-text="passwordError" class="text-red-500 text-xs mt-1 font-semibold" x-transition></p>
                            </div>

                            <div>
                                <label class="block text-xs md:text-sm font-semibold text-slate-700 mb-1">Konfirmasi Password</label>
                                <div class="relative">
                                    <input :type="showPass ? 'text' : 'password'" name="password_confirmation" placeholder="••••••••" required 
                                        class="w-full px-4 py-2.5 md:py-3 bg-slate-50 border rounded-xl focus:ring-4 outline-none text-sm transition-all"
                                        :class="confirmPasswordError ? 'border-red-500 focus:ring-red-100' : 'border-slate-200 focus:ring-orange-100 focus:border-orange-500'"
                                        @input="
                                            if ($event.target.value === '') {
                                                confirmPasswordError = '';
                                            } else if ($event.target.value !== passwordValue) {
                                                confirmPasswordError = 'Password tidak cocok.';
                                            } else {
                                                confirmPasswordError = '';
                                            }
                                        ">
                                </div>
                                <p x-show="confirmPasswordError" x-text="confirmPasswordError" class="text-red-500 text-xs mt-1 font-semibold" x-transition></p>
                            </div>

                            <div class="md:col-span-2 flex items-center pt-1">
                                <input type="checkbox" id="togglePassword" x-model="showPass" class="w-4 h-4 text-orange-500 bg-slate-50 border-slate-300 rounded focus:ring-orange-500 focus:ring-2 cursor-pointer transition-all">
                                <label for="togglePassword" class="ml-2 text-xs md:text-sm font-medium text-slate-600 cursor-pointer select-none">
                                    Tampilkan Password
                                </label>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <div id="step2" x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                    <div class="mb-6">
                        <h2 class="text-xl md:text-2xl font-bold text-slate-900">Kontak Person</h2>
                        <p class="text-slate-500 text-xs md:text-sm mt-1">Informasi untuk koordinasi dan verifikasi.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-xs md:text-sm font-semibold text-slate-700 mb-1">Nama Lengkap Manager/Owner</label>
                            <input type="text" name="nama_manager" value="{{ old('nama_manager') }}" placeholder="Nama Lengkap Anda" required class="w-full px-4 py-2.5 md:py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-orange-100 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs md:text-sm font-semibold text-slate-700 mb-1">Nomor WhatsApp</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400"><i class="bi bi-whatsapp"></i></span>
                                <input type="number" name="no_wa" value="{{ old('no_wa') }}" placeholder="0812..." required class="w-full pl-11 pr-4 py-2.5 md:py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-orange-100 outline-none text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs md:text-sm font-semibold text-slate-700 mb-1">Email Aktif</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@gmail.com" required class="w-full pl-11 pr-4 py-2.5 md:py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-orange-100 outline-none text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="step3" x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                    <div class="mb-6">
                        <h2 class="text-xl md:text-2xl font-bold text-slate-900">Profil Bisnis</h2>
                        <p class="text-slate-500 text-xs md:text-sm mt-1">Lengkapi data outlet dan sosial media untuk halaman publik.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                        <div>
                            <label class="block text-xs md:text-sm font-semibold text-slate-700 mb-1">Jenis Outlet</label>
                            <select name="jenis_outlet" x-model="jenis" required class="w-full p-2.5 md:p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-orange-100 outline-none text-sm">
                                <option value="">Pilih Jenis...</option>
                                <option value="cafe">Cafe / Kedai Kopi</option>
                                <option value="restoran">Restoran Rumah Makan</option>
                                <option value="lainnya">Lainnya...</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs md:text-sm font-semibold text-slate-700 mb-1">Nama Outlet</label>
                            <input type="text" name="nama_outlet" value="{{ old('nama_outlet') }}" placeholder="Flavory Coffee" required class="w-full p-2.5 md:p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-orange-100 outline-none text-sm">
                        </div>

                        <div class="md:col-span-2" x-show="jenis === 'lainnya'" x-transition>
                            <label class="block text-xs font-bold text-orange-600 mb-1 italic">Sebutkan Jenis Outlet Lainnya</label>
                            <input type="text" name="jenis_lainnya" value="{{ old('jenis_lainnya') }}" placeholder="Misal: Food Truck, Jajanan Kaki Lima" class="w-full p-2.5 md:p-3 bg-orange-50 border border-orange-200 rounded-xl focus:ring-4 focus:ring-orange-100 outline-none text-sm">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs md:text-sm font-semibold text-slate-700 mb-1">Custom URL (Slug)</label>
                            <div class="flex items-center">
                                <span class="bg-slate-200 px-3 py-2.5 md:py-3 rounded-l-xl text-xs font-bold text-slate-500 border border-r-0 border-slate-200">flavory.id/</span>
                                <input type="text" name="page_name" value="{{ old('page_name') }}" placeholder="nama-toko-anda" required 
                                    class="w-full p-2.5 md:p-3 bg-slate-50 border rounded-r-xl focus:ring-4 outline-none text-sm transition-all"
                                    :class="pageNameError ? 'border-red-500 focus:ring-red-100' : 'border-slate-200 focus:ring-orange-100 focus:border-orange-500'"
                                    @input.debounce.500ms="
                                        if($event.target.value.length > 2) {
                                            fetch('/check-availability?type=page_name&value=' + $event.target.value)
                                            .then(res => res.json())
                                            .then(data => { pageNameError = data.available ? '' : 'URL ini sudah dipakai outlet lain!'; });
                                        } else { pageNameError = ''; }
                                    ">
                            </div>
                            <p x-show="pageNameError" x-text="pageNameError" class="text-red-500 text-xs mt-1 font-semibold" x-transition></p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs md:text-sm font-semibold text-slate-700 mb-1">Deskripsi Outlet</label>
                            <textarea name="deskripsi" rows="2" placeholder="Ceritakan keunikan dan keunggulan outlet Anda..." required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-orange-100 outline-none text-sm">{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs md:text-sm font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                            <textarea name="alamat_lengkap" rows="2" placeholder="Nama Jalan, Blok, No. Rumah/Ruko..." required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-orange-100 outline-none text-sm">{{ old('alamat_lengkap') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs md:text-sm font-semibold text-slate-700 mb-1">Kota/Kabupaten</label>
                            <input type="text" name="kota" value="{{ old('kota') }}" placeholder="Contoh: Jakarta Selatan" required class="w-full p-2.5 md:p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-orange-100 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs md:text-sm font-semibold text-slate-700 mb-1">Provinsi</label>
                            <input type="text" name="provinsi" value="{{ old('provinsi') }}" placeholder="Contoh: DKI Jakarta" required class="w-full p-2.5 md:p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-orange-100 outline-none text-sm">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs md:text-sm font-semibold text-slate-700 mb-1">Unggah Foto Outlet</label>
                            <div class="relative group">
                                <label class="flex flex-col items-center justify-center w-full h-32 md:h-36 border-2 border-slate-200 border-dashed rounded-[1.5rem] cursor-pointer bg-slate-50 hover:bg-orange-50 hover:border-orange-300 transition-all overflow-hidden relative">
                                    <div x-show="!imageUrl" class="flex flex-col items-center justify-center pt-4 pb-4">
                                        <div class="w-10 h-10 md:w-12 md:h-12 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                            <i class="bi bi-camera text-xl md:text-2xl"></i>
                                        </div>
                                        <p class="text-[10px] md:text-xs text-slate-500 font-bold uppercase tracking-wider">Klik untuk foto</p>
                                    </div>
                                    <div x-show="imageUrl" class="absolute inset-0 w-full h-full p-1.5" x-transition>
                                        <img :src="imageUrl" class="w-full h-full object-cover rounded-[1.2rem] shadow-inner">
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 hover:opacity-100 transition-opacity rounded-[1.2rem]">
                                            <div class="bg-white/20 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/30 text-white text-[10px] font-bold uppercase tracking-widest">
                                                Ganti <i class="bi bi-arrow-repeat ml-1"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="file" name="foto_outlet" required class="absolute w-[1px] h-[1px] opacity-0" accept="image/*" 
                                        @change="
                                            const file = $event.target.files[0];
                                            if (file) {
                                                const reader = new FileReader();
                                                reader.onload = (e) => { imageUrl = e.target.result; };
                                                reader.readAsDataURL(file);
                                            }
                                        " />
                                </label>
                                <button type="button" x-show="imageUrl" @click="imageUrl = null; $refs.fileInput.value = ''" 
                                        class="absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 md:w-8 md:h-8 rounded-full shadow-lg flex items-center justify-center hover:bg-red-600 transition-colors border-2 border-white z-20 text-xs md:text-base">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs md:text-sm font-semibold text-slate-700 mb-1">Tautan Google Maps (Opsional)</label>
                            <input type="url" name="maps" value="{{ old('maps') }}" placeholder="http://googleusercontent.com/maps..." class="w-full p-2.5 md:p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-orange-100 outline-none text-sm">
                        </div>

                        <div class="md:col-span-2 grid grid-cols-2 md:grid-cols-3 gap-3 mt-1">
                            <div class="relative">
                                <i class="bi bi-instagram absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input type="text" name="instagram" value="{{ old('instagram') }}" placeholder="IG" class="w-full pl-8 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs outline-none focus:border-orange-500">
                            </div>
                            <div class="relative">
                                <i class="bi bi-tiktok absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input type="text" name="tiktok" value="{{ old('tiktok') }}" placeholder="TikTok" class="w-full pl-8 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs outline-none focus:border-orange-500">
                            </div>
                            <div class="relative">
                                <i class="bi bi-facebook absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input type="text" name="facebook" value="{{ old('facebook') }}" placeholder="FB" class="w-full pl-8 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs outline-none focus:border-orange-500">
                            </div>
                            <div class="relative">
                                <i class="bi bi-youtube absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input type="text" name="username_youtube" value="{{ old('username_youtube') }}" placeholder="YouTube" class="w-full pl-8 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs outline-none focus:border-orange-500">
                            </div>
                            <div class="relative">
                                <i class="bi bi-twitter-x absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input type="text" name="username_x" value="{{ old('username_x') }}" placeholder="X (Twitter)" class="w-full pl-8 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs outline-none focus:border-orange-500">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="step4" x-show="step === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                    <div class="mb-6 text-center md:text-left">
                        <h2 class="text-xl md:text-2xl font-bold text-slate-900">Pembayaran</h2>
                        <p class="text-slate-500 text-xs md:text-sm mt-1">Pilih paket layanan untuk outlet Flavory.id Anda.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <div class="bg-slate-900 p-6 md:p-8 rounded-[2rem] text-center text-white relative overflow-hidden shadow-xl h-full flex flex-col justify-center">
                            <i class="bi bi-shield-lock-fill text-4xl md:text-5xl text-orange-400 mb-3"></i>
                            <h3 class="text-lg font-bold mb-2">Pembayaran Terverifikasi</h3>
                            <p class="text-xs opacity-70 leading-relaxed">Transaksi Anda diproses secara instan dan aman melalui Payment Gateway resmi.</p>
                            <div class="absolute -top-10 -left-10 w-24 h-24 bg-orange-500 rounded-full opacity-10"></div>
                        </div>

                        <div class="space-y-4">
                                <div>
                                    <label class="block text-xs md:text-sm font-semibold text-slate-700 mb-2">Pilih Paket Layanan</label>
                                    <select name="paket" x-model="paket" class="w-full p-3 md:p-4 bg-white border-2 border-slate-100 rounded-xl md:rounded-2xl outline-none focus:border-orange-500 font-bold text-slate-700 text-sm md:text-base cursor-pointer">
                                        <option value="gratis">Coba Gratis 30 Hari (Rp 0)</option>
                                        <option value="eksklusif">★ Paket Eksklusif (Rp 70.000 / Bulan)</option>
                                        <option value="super">♛ Paket Super (Rp 880.000 / Selamanya)</option>
                                    </select>
                                </div>

                                <div x-show="paket !== 'gratis'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2">
                                    <label class="block text-xs md:text-sm font-semibold text-slate-700 mb-2">Pilih Metode Pembayaran</label>
                                    <select name="method" :required="paket !== 'gratis'" class="w-full p-3 md:p-4 bg-orange-50 border-2 border-orange-100 rounded-xl md:rounded-2xl outline-none focus:border-orange-500 font-bold text-slate-700 text-sm md:text-base cursor-pointer">
                                        <option value="">-- Pilih Metode --</option>
                                        <option value="QRIS">QRIS (Otomatis)</option>
                                        <option value="BCAVA">BCA Virtual Account</option>
                                        <option value="BRIVA">BRI Virtual Account</option>
                                        <option value="BNIVA">BNI Virtual Account</option>
                                         <option value="BSIVA">BSI Virtual Account</option>
                                        <option value="MANDIRIVA">Mandiri Virtual Account</option>
                                    </select>
                                    <p class="mt-2 text-[10px] text-slate-400 italic">*Pilih metode yang paling memudahkan Anda untuk verifikasi instan.</p>
                                </div>

                                <p x-show="paket === 'gratis'" x-transition 
                                class="mt-3 text-xs md:text-sm font-medium text-orange-600 bg-orange-50 px-3 py-3 rounded-xl border border-orange-100 flex items-start gap-2">
                                    <i class="bi bi-info-circle-fill mt-0.5"></i>
                                    <span>Anda dapat menggunakan seluruh fitur flavory.id secara gratis selama 30 hari dan dapat batalkan kapan saja.</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse md:flex-row items-center justify-between gap-4 mt-8 md:mt-10 pt-6 border-t border-slate-100">

                    <a href="{{ route('home') }}" x-show="step === 1" 
                        class="w-full md:w-auto group flex items-center justify-center gap-2 px-5 py-2.5 md:py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl md:rounded-2xl transition-all text-sm">
                        <i class="bi bi-arrow-left group-hover:-translate-x-1 transition-transform"></i> Beranda
                    </a>
                    <button type="button" x-show="step > 1" @click="step--" 
                            class="w-full md:w-auto group flex items-center justify-center gap-2 px-5 py-2.5 md:py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl md:rounded-2xl transition-all text-sm">
                        <i class="bi bi-chevron-left group-hover:-translate-x-1"></i> Kembali
                    </button>
                    
                    <button type="button" x-show="step < 4" 
                        @click="
                            if (usernameError !== '' || pageNameError !== '' || passwordError !== '' || confirmPasswordError !== '') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Silakan perbaiki kolom yang berwarna merah sebelum melanjutkan.',
                                confirmButtonColor: '#ea580c'
                            });
                            return;
                        }

                            let currentStep = document.getElementById('step' + step);
                            let inputs = currentStep.querySelectorAll('[required]');
                            let valid = true;
                            for (let input of inputs) {
                                if (!input.checkValidity()) {
                                    input.reportValidity();
                                    valid = false;
                                    break;
                                }
                            }
                            if (valid) step++;
                        " 
                        class="w-full md:w-auto md:ml-auto group flex items-center justify-center gap-2 px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl md:rounded-2xl shadow-lg shadow-orange-100 transition-all text-sm md:text-base">
                        Lanjut <i class="bi bi-chevron-right group-hover:translate-x-1"></i>
                    </button>

                    <button type="submit" x-show="step === 4" 
                            class="w-full md:w-auto md:ml-auto flex items-center justify-center gap-2 px-6 py-3 md:py-4 bg-green-600 hover:bg-green-700 text-white font-black rounded-xl md:rounded-2xl shadow-xl shadow-green-100 transition-all transform hover:scale-[1.02] active:scale-95 text-xs md:text-sm tracking-wide">
                        KONFIRMASI PENDAFTARAN <i class="bi bi-send-check-fill"></i>
                    </button>
                </div>
            </form>
        </div>
        
        <div class="mt-8 text-center">
            <p class="text-slate-400 text-[9px] md:text-[10px] font-medium tracking-[0.2em] uppercase">
                &copy; {{ date('Y') }} Flavory.id. All Rights Reserved.
            </p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/loading.js') }}"></script>
</body>
</html>