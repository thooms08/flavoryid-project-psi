@forelse($stokBarang as $item)
    @php
        $stokTerakhir = $item->stok_terakhir;
        $warnaClass = 'bg-white border-start border-4 border-secondary'; 
        $statusTeks = 'Belum Ada Stok';

        if($stokTerakhir > 0 || $item->stokHarian->count() > 0) {
            if($stokTerakhir >= $item->min_hijau && $stokTerakhir <= $item->max_hijau) {
                $warnaClass = 'bg-success text-white shadow-sm';
                $statusTeks = 'Tersedia';
            } elseif($stokTerakhir >= $item->min_kuning && $stokTerakhir <= $item->max_kuning) {
                $warnaClass = 'bg-warning text-dark shadow-sm';
                $statusTeks = 'Menipis';
            } elseif($stokTerakhir >= $item->min_merah && $stokTerakhir <= $item->max_merah) {
                $warnaClass = 'bg-danger text-white shadow-sm';
                $statusTeks = 'Hampir Habis';
            }
        }

        $tanggalAktif = \Carbon\Carbon::createFromDate($tahunIni, $bulanIni, 1);
        $namaBulan = $tanggalAktif->translatedFormat('F Y');
        $jumlahHariFix = $tanggalAktif->daysInMonth; 
    @endphp

    <div class="col-12 col-md-6 col-lg-4">
        <div class="card rounded-4 border-0 {{ $warnaClass }} p-3 h-100 shadow-sm transition-hover">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="fw-bold mb-0 text-truncate pe-2" title="{{ $item->nama_barang }}">{{ $item->nama_barang }}</h5>
                <span class="badge bg-light text-dark shadow-sm">{{ $statusTeks }}</span>
            </div>
            <div class="mb-4 mt-2">
                <h2 class="fw-bolder mb-0 display-6">{{ floatval($stokTerakhir) }} <span class="fs-6 fw-normal">{{ $item->satuan }}</span></h2>
                <small class="opacity-75">Sisa stok terakhir</small>
            </div>
            
            <div class="d-flex gap-2 mt-auto">
                <button type="button" class="btn btn-light btn-sm flex-fill fw-bold" style="color: #fd7e14; border: 1px solid #fd7e1433;" data-bs-toggle="modal" data-bs-target="#modalAturStok{{ $item->id }}">
                    <i class="bi bi-calendar-check me-1"></i> ATUR STOK
                </button>
                
                <button type="button" class="btn btn-light btn-sm text-secondary" style="border: 1px solid #dee2e6;" data-bs-toggle="modal" data-bs-target="#modalEditBarang{{ $item->id }}" title="Edit Barang">
                    <i class="bi bi-pencil-square"></i>
                </button>
                
                <button type="button" class="btn btn-light btn-sm text-secondary" style=" border: 1px solid #fd7e1433;" data-bs-toggle="modal" data-bs-target="#modalAturWarna{{ $item->id }}" title="Atur Warna">
                    <i class="bi bi-palette-fill"></i>
                </button>
                
                <form action="{{ route('kasir.stok.delete', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm text-danger border-0" title="Hapus"><i class="bi bi-trash-fill"></i></button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditBarang{{ $item->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <form action="{{ route('kasir.stok.update', $item->id) }}" method="POST">
                    @csrf
                    <div class="modal-header border-bottom-0 pb-0">
                        <h6 class="modal-title fw-bold">Edit Info Barang</h6>
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body px-4 pt-3 pb-2">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted mb-1">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control rounded-3" value="{{ $item->nama_barang }}" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-semibold small text-muted mb-1">Satuan</label>
                            <input type="text" name="satuan" class="form-control rounded-3" value="{{ $item->satuan }}" placeholder="Contoh: KG, PCS" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0 pb-3 px-3">
                        <button type="submit" class="btn text-white w-100 fw-bold btn-sm py-2 rounded-3" style="background-color: #fd7e14;">SIMPAN PERUBAHAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAturWarna{{ $item->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <form action="{{ route('kasir.stok.updateWarna', $item->id) }}" method="POST">
                    @csrf
                    <div class="modal-header border-bottom-0 pb-0">
                        <h6 class="modal-title fw-bold">Atur Warna: <span class="text-truncate d-inline-block align-bottom" style="max-width: 120px;">{{ $item->nama_barang }}</span></h6>
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body px-3 pt-2">
                        <p class="text-muted" style="font-size: 0.75rem; margin-bottom: 12px;">Input angka desimal (contoh: 1.5). Sistem akan mewarnai list otomatis.</p>
                        
                        <div class="row g-2 mb-2">
                            <div class="col-12"><label class="badge bg-success w-100 text-start py-2">Hijau (Aman)</label></div>
                            <div class="col-6"><input type="number" step="0.01" name="min_hijau" class="form-control form-control-sm text-center" placeholder="Min" value="{{ floatval($item->min_hijau) }}" required></div>
                            <div class="col-6"><input type="number" step="0.01" name="max_hijau" class="form-control form-control-sm text-center" placeholder="Max" value="{{ floatval($item->max_hijau) }}" required></div>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-12"><label class="badge bg-warning text-dark w-100 text-start py-2">Kuning (Menipis)</label></div>
                            <div class="col-6"><input type="number" step="0.01" name="min_kuning" class="form-control form-control-sm text-center" placeholder="Min" value="{{ floatval($item->min_kuning) }}" required></div>
                            <div class="col-6"><input type="number" step="0.01" name="max_kuning" class="form-control form-control-sm text-center" placeholder="Max" value="{{ floatval($item->max_kuning) }}" required></div>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-12"><label class="badge bg-danger w-100 text-start py-2">Merah (Hampir Habis)</label></div>
                            <div class="col-6"><input type="number" step="0.01" name="min_merah" class="form-control form-control-sm text-center" placeholder="Min" value="{{ floatval($item->min_merah) }}" required></div>
                            <div class="col-6"><input type="number" step="0.01" name="max_merah" class="form-control form-control-sm text-center" placeholder="Max" value="{{ floatval($item->max_merah) }}" required></div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0 pb-3 px-3">
                        <button type="submit" class="btn text-white w-100 fw-bold btn-sm py-2 rounded-3" style="background-color: #fd7e14;">SIMPAN WARNA</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAturStok{{ $item->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <form action="{{ route('kasir.stok.aturStok', $item->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="bulan" value="{{ $bulanIni }}">
                    <input type="hidden" name="tahun" value="{{ $tahunIni }}">
                    
                    <div class="modal-header border-bottom-0 pb-2 bg-light rounded-top-4">
                        <div>
                            <h6 class="modal-title fw-bold text-dark mb-1">Stok: {{ $item->nama_barang }}</h6>
                            <span class="badge fw-semibold" style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba;">
                                <i class="bi bi-calendar-event me-1"></i> Bulan: {{ $namaBulan }}
                            </span>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="align-self: flex-start; margin-top: 5px;"></button>
                    </div>
                    
                    <div class="modal-body px-3 py-3 custom-scroll-area" style="background-color: #f8f9fa;">
                        <div class="alert alert-warning border-0 rounded-3 p-2 mb-3 shadow-sm" style="font-size: 0.75rem; background-color: #fff3cd; color: #856404;">
                            <i class="bi bi-info-circle-fill me-1"></i> Input gunakan angka/desimal (contoh: 1.5).
                        </div>
                        
                        <div class="row g-2">
                            @for($i = 1; $i <= $jumlahHariFix; $i++)
                                @php
                                    $tglCari = \Carbon\Carbon::createFromDate($tahunIni, $bulanIni, $i)->format('Y-m-d');
                                    $dataHarian = $item->stokHarian->where('tanggal', $tglCari)->first();
                                    $nilaiStok = $dataHarian ? floatval($dataHarian->jumlah) : '';
                                    
                                    $isHariIni = ($i == \Carbon\Carbon::now()->day && $bulanIni == \Carbon\Carbon::now()->month) ? 'border-warning shadow-sm' : 'border-light shadow-sm';
                                @endphp
                                <div class="col-4 col-sm-3 col-md-2">
                                    <div class="card {{ $isHariIni }} rounded-3 overflow-hidden h-100" style="border-width: 1px;">
                                        <div class="card-header py-1 px-1 bg-white border-bottom-0 text-center">
                                            <small class="fw-bold text-muted" style="font-size: 0.7rem;">Tgl {{ $i }}</small>
                                        </div>
                                        <div class="card-body p-0">
                                            <input <input type="number" step="0.01" name="input_stok[{{ $i }}]" id="tgl{{$item->id}}_{{$i}}"
                                                   class="form-control form-control-sm border-0 text-center fw-semibold rounded-0" 
                                                   style="background-color: #fdfdfd; padding: 4px;"
                                                   placeholder="0" value="{{ $nilaiStok }}">
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                    
                    <div class="modal-footer border-top-0 pt-3 pb-3 px-3 bg-white rounded-bottom-4">
                        <button type="submit" class="btn text-white w-100 fw-bold py-2 rounded-3" style="background-color: #fd7e14; box-shadow: 0 4px 10px rgba(253, 126, 20, 0.2);">
                            <i class="bi bi-check-circle me-1"></i> SIMPAN STOK HARIAN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@empty
    <div class="col-12 text-center py-5 mt-4 bg-white rounded-4 shadow-sm border border-light">
        <i class="bi bi-box2 display-1 text-muted opacity-25"></i>
        <p class="mt-3 text-muted fw-medium">Belum ada barang di inventori. Silakan tambahkan barang baru.</p>
    </div>
@endforelse

<style>
    .transition-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .transition-hover:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }

    .custom-scroll-area {
        max-height: 55vh; 
        overflow-y: auto;
    }

    .custom-scroll-area::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scroll-area::-webkit-scrollbar-track {
        background: #f1f1f1; 
        border-radius: 10px;
    }
    .custom-scroll-area::-webkit-scrollbar-thumb {
        background: #c1c1c1; 
        border-radius: 10px;
    }
    .custom-scroll-area::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8; 
    }
</style>