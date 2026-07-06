<div class="bg-white rounded-[2.5rem] p-6 border border-orange-100 shadow-sm hover:shadow-xl transition-all flex flex-col group" id="order-card-{{ $order->id }}">
    <div class="flex justify-between items-start mb-6">
        <div>
            <span class="text-[10px] font-black text-orange-500 uppercase tracking-widest">#{{ $order->nomor_pesanan }}</span>
            <h3 class="text-lg font-black text-slate-800 leading-tight">{{ $order->nama_pelanggan ?? 'Pelanggan Umum' }}</h3>
            <div class="flex items-center gap-2 mt-2">
                <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[9px] font-black rounded-lg uppercase">Meja: {{ $order->no_meja ?? '-' }}</span>
                @if($order->no_whatsapp)
                <a href="https://wa.me/{{ $order->no_whatsapp }}" class="text-green-500 hover:text-green-600"><i class="bi bi-whatsapp text-xs"></i></a>
                @endif
            </div>
        </div>
        <div class="flex flex-col items-end">
            <span class="bg-orange-50 text-orange-600 text-[9px] font-black px-2 py-1 rounded-lg uppercase animate-pulse">Pending</span>
            <p class="text-[9px] text-slate-400 mt-1 italic">{{ $order->created_at->diffForHumans() }}</p>
        </div>
    </div>

    <div class="bg-slate-50 rounded-3xl p-4 mb-6 flex-1">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 border-b border-slate-200 pb-2">Item Pesanan</p>
        <div class="space-y-3">
            @foreach($order->orderDetails as $detail)
            <div class="flex justify-between items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-slate-700 leading-none">{{ $detail->menu->nama_menu }}</p>
                    <p class="text-[10px] text-slate-400 mt-1">{{ $detail->qty }}x @ Rp{{ number_format($detail->harga, 0, ',', '.') }}</p>
                </div>
                <span class="text-xs font-black text-slate-600">Rp{{ number_format($detail->qty * $detail->harga, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="flex items-center justify-between mb-6 px-2">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Bayar</p>
        <p class="text-xl font-black text-orange-500">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</p>
    </div>

    <button @click="openPaymentModal({{ json_encode($order) }})" class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl shadow-xl hover:shadow-orange-200 hover:bg-orange-600 transition-all active:scale-95 flex items-center justify-center gap-3 text-xs tracking-widest">
        BAYAR SEKARANG <i class="bi bi-arrow-right-short text-xl"></i>
    </button>
</div>