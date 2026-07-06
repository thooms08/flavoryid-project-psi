{{-- HANYA baris TR, jangan ada div atau x-data di sini --}}
@forelse($menus as $index => $menu)
<tr id="menu-row-{{ $menu->id }}"
    class="align-middle border-bottom{{ $index >= 20 ? ' menu-row-extra d-none' : '' }}">
    <td class="py-3 px-3">
        <div class="d-flex align-items-center gap-3">
            @if($menu->gambar)
                <img src="{{ asset('storage/' . $menu->gambar) }}" class="rounded-3 object-fit-cover border border-warning-subtle" style="width: 45px; height: 45px;">
            @else
                <div class="rounded-3 bg-warning-subtle d-flex align-items-center justify-content-center text-warning" style="width: 45px; height: 45px;">
                    <i class="bi bi-egg-fried fs-5"></i>
                </div>
            @endif
            <div class="d-flex flex-column">
                <span class="fw-bold text-dark">{{ $menu->nama_menu }}</span>
                <span class="text-muted text-uppercase" style="font-size: 10px; letter-spacing: 0.5px;">
                    {{ $menu->kategori }} | {{ str_replace('_', ' ', $menu->tipe_menu) }}
                </span>
            </div>
        </div>
    </td>
    <td class="py-3 px-3 fw-medium text-secondary">
        Rp <span>{{ number_format($menu->harga, 0, ',', '.') }}</span>
    </td>
    <td class="py-3 px-3 text-center">
        @if($menu->status_menu == 'tersedia')
            <span class="badge bg-success-subtle text-success text-uppercase rounded-pill px-3 py-2" style="font-size: 10px;">{{ $menu->status_menu }}</span>
        @else
            <span class="badge bg-danger-subtle text-danger text-uppercase rounded-pill px-3 py-2" style="font-size: 10px;">{{ $menu->status_menu }}</span>
        @endif
    </td>
    <td class="py-3 px-3 text-end">
        <div class="d-flex justify-content-end gap-2">
            <button type="button" 
                    data-menu="{{ base64_encode(json_encode($menu)) }}"
                    onclick="openEditModal(this)" 
                    class="btn btn-sm btn-warning-subtle text-warning hover-orange d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                <i class="bi bi-pencil-fill"></i>
            </button>
            
            <button type="button" 
                    onclick="deleteMenu({{ $menu->id }})" 
                    class="btn btn-sm btn-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                <i class="bi bi-trash-fill"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="4" class="py-5 text-center text-muted fst-italic">Menu tidak ditemukan...</td>
</tr>
@endforelse
