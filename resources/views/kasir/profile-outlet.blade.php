<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile Outlet - Flavory.id</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('layouts.favicon')
    @include('layouts.loading')

    <style>
        :root { 
            --primary-orange: #fd7e14; 
            --dark-orange: #e86c00; 
            --soft-orange: #fff4e6;
        }

        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #fff7ed; 
            color: #333; 
        }

        /* Card Enhancement */
        .profile-card {
            background: white;
            border-radius: 20px;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            overflow: hidden;
        }

        .profile-header {
            background: var(--soft-orange);
            border-bottom: 2px solid rgba(253, 126, 20, 0.1);
            padding: 1.5rem;
            text-align: center;
        }

        .profile-header h4 {
            color: var(--dark-orange);
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }

        /* Form Input Styling */
        .form-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .form-control, .form-select, .input-group-text {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1px solid #e9ecef;
            background-color: #f8f9fa;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .input-group-text {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            font-weight: 600;
            color: #6c757d;
        }

        .form-control:focus, .form-select:focus {
            background-color: #ffffff;
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 4px rgba(253, 126, 20, 0.15);
        }

        /* Modern Upload Area Styling */
        .upload-area {
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            padding: 2rem 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8fafc;
            position: relative;
            overflow: hidden;
            max-width: 400px;
            margin: 0 auto;
        }

        .upload-area:hover {
            border-color: var(--primary-orange);
            background-color: var(--soft-orange);
        }

        .upload-area.error {
            border-color: #dc3545;
            background-color: #fff2f2;
        }

        .upload-placeholder i {
            font-size: 2.5rem;
            color: #94a3b8;
            margin-bottom: 10px;
            transition: color 0.3s;
        }

        .upload-area:hover .upload-placeholder i {
            color: var(--primary-orange);
        }

        .upload-placeholder p {
            margin: 0;
            font-size: 0.9rem;
            color: #64748b;
            font-weight: 500;
        }

        .upload-placeholder small {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .modern-preview-wrapper img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 12px;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .change-image-btn {
            display: inline-block;
            margin-top: 15px;
            font-size: 0.85rem;
            color: var(--primary-orange);
            background: var(--soft-orange);
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 600;
        }

        /* Button Styling */
        .btn-orange { 
            background-color: var(--primary-orange); 
            color: white; 
            border: none; 
            font-weight: 600; 
            border-radius: 12px; 
            padding: 12px 20px;
            transition: 0.3s; 
            letter-spacing: 0.5px;
        }

        .btn-orange:hover { 
            background-color: var(--dark-orange); 
            color: white; 
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(253, 126, 20, 0.2);
        }
    </style>
</head>
<body>

@include('kasir.layouts.app')

<div class="container py-5" style="margin-top:-50px;">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert" style="background-color: #d1e7dd; color: #0f5132;">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="profile-card">
                <div class="profile-header position-relative">
                    <a href="{{ route('kasir.akun.index') }}" class="btn btn-sm btn-light position-absolute top-0 end-0 m-3 shadow-sm" style="border-radius: 10px; color: var(--dark-orange); font-weight: 600;">
                        <i class="bi bi-shield-lock me-1"></i> Pengaturan Akun
                    </a>

                    <i class="bi bi-shop fs-1 mb-2 d-block mt-3" style="color: var(--primary-orange);"></i>
                    <h4>{{ $informasi->nama_outlet ?? 'Informasi Outlet' }}</h4>
                    <p class="text-muted small mb-0 mt-1">Kelola detail profil dan tampilan halaman outlet Anda</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    @if(isset($informasi) && !empty($informasi->page_name))
                        @php
                            $publicUrl = url('/' . $informasi->page_name);
                        @endphp
                        <div class="mb-5 text-center p-4 rounded-4" style="background-color: var(--soft-orange); border: 2px dashed rgba(253, 126, 20, 0.3);">
                            <h5 class="fw-bold mb-3" style="color: var(--dark-orange);">
                                <i class="bi bi-qr-code-scan me-2"></i>QR Code Halaman Outlet
                            </h5>
                            
                            <div class="mb-3 bg-white p-3 d-inline-block rounded-4 shadow-sm">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(160)->generate($publicUrl) !!}
                            </div>
                            
                            <p class="mb-3 text-muted small">Scan QR Code ini untuk mengunjungi halaman outlet Anda.</p>
                            
                            <div class="mb-4">
                                <a href="{{ $publicUrl }}" target="_blank" class="fw-bold text-decoration-none fs-5 d-inline-block px-3 py-2 rounded-3" style="color: var(--dark-orange); background: white; border: 1px solid #f8d7da; word-break: break-all;">
                                    <i class="bi bi-globe me-2"></i>{{ $publicUrl }}
                                </a>
                            </div>
                            
                            <a href="{{ route('kasir.profile-outlet.download-qr') }}" class="btn px-4 py-2 fw-semibold" style="color: var(--primary-orange); border: 1px solid var(--primary-orange); background: white; border-radius: 12px; transition: 0.3s;">
                                <i class="bi bi-download me-2"></i> Download QR Code (PNG)
                            </a>
                        </div>
                        
                        <hr class="mb-5 text-secondary" style="opacity: 0.15;">
                    @endif
                    <form action="{{ route('kasir.profile-outlet.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4 text-center border-bottom pb-4">
                            <label class="form-label d-block"><i class="bi bi-image me-1 text-orange" style="color: var(--primary-orange);"></i> Logo Outlet</label>
                            
                            <div class="upload-area @error('logo') error @enderror" onclick="document.getElementById('logo').click()">
                                <div class="modern-preview-wrapper" id="logo-preview-wrapper" style="{{ isset($informasi->logo) && $informasi->logo ? '' : 'display: none;' }}">
                                    <img src="{{ isset($informasi->logo) && $informasi->logo ? asset('assets/logo/' . $informasi->logo) : '#' }}" alt="Logo" id="logo-preview">
                                    <div class="change-image-btn"><i class="bi bi-arrow-repeat me-1"></i> Ganti Logo</div>
                                </div>

                                <div class="upload-placeholder" id="logo-placeholder" style="{{ isset($informasi->logo) && $informasi->logo ? 'display: none;' : '' }}">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                    <p>Klik di sini untuk unggah Logo</p>
                                    <small>Maksimal 2MB (JPG, PNG)</small>
                                </div>

                                <input type="file" id="logo" name="logo" class="d-none" accept="image/jpeg,image/png,image/jpg" onchange="previewModernImage(this, 'logo-preview', 'logo-preview-wrapper', 'logo-placeholder')">
                            </div>
                            @error('logo') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="nama_outlet" class="form-label"><i class="bi bi-tag me-1" style="color: var(--primary-orange);"></i> Nama Outlet <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_outlet') is-invalid @enderror" id="nama_outlet" name="nama_outlet" value="{{ old('nama_outlet', $informasi->nama_outlet ?? '') }}" required placeholder="Contoh: Flavory Cafe">
                                @error('nama_outlet') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="jenis_outlet" class="form-label"><i class="bi bi-grid me-1" style="color: var(--primary-orange);"></i> Jenis Outlet <span class="text-danger">*</span></label>
                                <select class="form-select @error('jenis_outlet') is-invalid @enderror" id="jenis_outlet" name="jenis_outlet" required>
                                    <option value="" disabled selected>Pilih Jenis...</option>
                                    <option value="cafe" {{ old('jenis_outlet', $informasi->jenis_outlet ?? '') == 'cafe' ? 'selected' : '' }}>Cafe</option>
                                    <option value="restoran" {{ old('jenis_outlet', $informasi->jenis_outlet ?? '') == 'restoran' ? 'selected' : '' }}>Restoran</option>
                                    <option value="lainnya" {{ old('jenis_outlet', $informasi->jenis_outlet ?? '') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('jenis_outlet') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4 bg-light p-3 rounded-3 border" id="container_lainnya" style="display: none;">
                            <label for="jenis_lainnya" class="form-label"><i class="bi bi-pencil me-1" style="color: var(--primary-orange);"></i> Spesifikasi Jenis Outlet <span class="text-danger">*</span></label>
                            <input type="text" class="form-control bg-white @error('jenis_lainnya') is-invalid @enderror" id="jenis_lainnya" name="jenis_lainnya" value="{{ old('jenis_lainnya', $informasi->jenis_lainnya ?? '') }}" placeholder="Contoh: Foodtruck, Bakery, Warmindo...">
                            @error('jenis_lainnya') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="page_name" class="form-label"><i class="bi bi-link-45deg me-1" style="color: var(--primary-orange);"></i> Tautan Halaman (URL)</label>
                            <div class="input-group">
                                <span class="input-group-text border-end-0">flavory.id/</span>
                                <input type="text" class="form-control border-start-0 @error('page_name') is-invalid @enderror" id="page_name" name="page_name" value="{{ old('page_name', $informasi->page_name ?? '') }}" placeholder="namaoutletanda" style="padding-left: 0;">
                            </div>
                            <div class="form-text">Buat tautan unik tanpa spasi untuk pelanggan Anda.</div>
                            @error('page_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4 border-top pt-4">
                            <label class="form-label d-block"><i class="bi bi-images me-1" style="color: var(--primary-orange);"></i> Foto Bangunan / Suasana Outlet</label>
                            
                            <div class="upload-area @error('foto_outlet') error @enderror" style="max-width: 100%;" onclick="document.getElementById('foto_outlet').click()">
                                
                                <div class="modern-preview-wrapper" id="foto-preview-wrapper" style="{{ !empty($informasi->foto_outlet) ? '' : 'display: none;' }}">
                                    <img src="{{ !empty($informasi->foto_outlet) ? asset('assets/outlet/' . $informasi->foto_outlet) : '#' }}" alt="Foto Outlet" id="foto-preview">
                                    <div class="d-block">
                                        <div class="change-image-btn"><i class="bi bi-arrow-repeat me-1"></i> Ganti Foto Suasana</div>
                                    </div>
                                </div>

                                <div class="upload-placeholder" id="foto-placeholder" style="{{ !empty($informasi->foto_outlet) ? 'display: none;' : '' }}">
                                    <i class="bi bi-images display-4 text-secondary mb-3"></i>
                                    <p>Klik di sini untuk unggah Foto Outlet</p>
                                    <small>Beri pelanggan gambaran suasana tempatmu (Maks: 2MB)</small>
                                </div>

                                <input type="file" id="foto_outlet" name="foto_outlet" class="d-none" accept="image/jpeg,image/png,image/jpg" onchange="previewModernImage(this, 'foto-preview', 'foto-preview-wrapper', 'foto-placeholder')">
                            </div>
                            @error('foto_outlet') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="deskripsi" class="form-label"><i class="bi bi-text-paragraph me-1" style="color: var(--primary-orange);"></i> Deskripsi Singkat</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3" placeholder="Ceritakan keunggulan atau konsep outlet Anda...">{{ old('deskripsi', $informasi->deskripsi ?? '') }}</textarea>
                            @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4 border-top pt-4">
                            <label for="alamat_lengkap" class="form-label"><i class="bi bi-geo-alt me-1" style="color: var(--primary-orange);"></i> Alamat Lengkap</label>
                            <textarea class="form-control @error('alamat_lengkap') is-invalid @enderror" id="alamat_lengkap" name="alamat_lengkap" rows="2" placeholder="Nama Jalan, Blok, RT/RW...">{{ old('alamat_lengkap', $informasi->alamat_lengkap ?? '') }}</textarea>
                            @error('alamat_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="kota" class="form-label"><i class="bi bi-buildings me-1" style="color: var(--primary-orange);"></i> Kota / Kabupaten</label>
                                <input type="text" class="form-control @error('kota') is-invalid @enderror" id="kota" name="kota" value="{{ old('kota', $informasi->kota ?? '') }}">
                                @error('kota') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="provinsi" class="form-label"><i class="bi bi-map me-1" style="color: var(--primary-orange);"></i> Provinsi</label>
                                <input type="text" class="form-control @error('provinsi') is-invalid @enderror" id="provinsi" name="provinsi" value="{{ old('provinsi', $informasi->provinsi ?? '') }}">
                                @error('provinsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="maps" class="form-label"><i class="bi bi-pin-map me-1" style="color: var(--primary-orange);"></i> Tautan Google Maps</label>
                            <input type="url" class="form-control @error('maps') is-invalid @enderror" id="maps" name="maps" value="{{ old('maps', $informasi->maps ?? '') }}" placeholder="https://maps.google.com/...">
                            @error('maps') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-5 border-top pt-4">
                            <h5 class="fw-bold mb-3" style="color: var(--dark-orange); font-size: 1.1rem;">
                                <i class="bi bi-share me-1"></i> Hubungkan Media Sosial
                            </h5>
                            <p class="text-muted small mb-4">Masukkan tautan (URL) atau *username* media sosial outlet Anda untuk memudahkan pelanggan menemukan Anda.</p>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="instagram" class="form-label"><i class="bi bi-instagram me-1" style="color: #E1306C;"></i> Instagram</label>
                                    <input type="text" class="form-control @error('instagram') is-invalid @enderror" id="instagram" name="instagram" value="{{ old('instagram', $media->instagram ?? '') }}" placeholder="Link atau @username">
                                    @error('instagram') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="facebook" class="form-label"><i class="bi bi-facebook me-1" style="color: #1877F2;"></i> Facebook</label>
                                    <input type="text" class="form-control @error('facebook') is-invalid @enderror" id="facebook" name="facebook" value="{{ old('facebook', $media->facebook ?? '') }}" placeholder="Link Facebook">
                                    @error('facebook') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="tiktok" class="form-label"><i class="bi bi-tiktok me-1" style="color: #000000;"></i> TikTok</label>
                                    <input type="text" class="form-control @error('tiktok') is-invalid @enderror" id="tiktok" name="tiktok" value="{{ old('tiktok', $media->tiktok ?? '') }}" placeholder="Link atau @username">
                                    @error('tiktok') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="youtube" class="form-label"><i class="bi bi-youtube me-1" style="color: #FF0000;"></i> YouTube</label>
                                    <input type="text" class="form-control @error('youtube') is-invalid @enderror" id="youtube" name="youtube" value="{{ old('youtube', $media->youtube ?? '') }}" placeholder="Link Channel YouTube">
                                    @error('youtube') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="x" class="form-label"><i class="bi bi-twitter-x me-1" style="color: #000000;"></i> X (Twitter)</label>
                                    <input type="text" class="form-control @error('x') is-invalid @enderror" id="x" name="x" value="{{ old('x', $media->x ?? '') }}" placeholder="Link atau @username">
                                    @error('x') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-orange text-uppercase">
                                <i class="bi bi-save2 me-2"></i> Simpan Perubahan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const jenisOutlet = document.getElementById('jenis_outlet');
    const containerLainnya = document.getElementById('container_lainnya');
    const inputLainnya = document.getElementById('jenis_lainnya');

    function toggleLainnya() {
        if (jenisOutlet.value === 'lainnya') {
            containerLainnya.style.display = 'block';
            inputLainnya.setAttribute('required', 'required');
            // Sedikit animasi transisi
            containerLainnya.style.opacity = '0';
            setTimeout(() => {
                containerLainnya.style.transition = 'opacity 0.3s';
                containerLainnya.style.opacity = '1';
            }, 10);
        } else {
            containerLainnya.style.display = 'none';
            inputLainnya.removeAttribute('required');
        }
    }

    jenisOutlet.addEventListener('change', toggleLainnya);
    toggleLainnya();
});

// Fungsi untuk Preview Image versi UI Modern
function previewModernImage(input, imgId, wrapperId, placeholderId) {
    const previewImg = document.getElementById(imgId);
    const wrapper = document.getElementById(wrapperId);
    const placeholder = document.getElementById(placeholderId);

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            
            // Tampilkan wrapper gambar
            wrapper.style.display = 'block'; 
            
            // Sembunyikan teks/icon placeholder "Klik untuk unggah"
            if(placeholder) {
                placeholder.style.display = 'none';
            }
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>
</html>