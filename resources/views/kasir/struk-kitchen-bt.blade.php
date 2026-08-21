<!DOCTYPE html>
<html>
<head><title>Printing Kitchen...</title></head>
<body>
    <div style="text-align: center; font-family: sans-serif; margin-top: 50px;">
        <p>Mengirim Pesanan ke Dapur...</p>
    </div>

    @php
        $width = 32; // Standar 58mm

        // 1. Header Dapur - tampilkan nama outlet & nomor meja agar koki tahu dari mana pesanan
        $namaOutlet = strtoupper($outlet->nama_outlet ?? 'OUTLET');
        $header  = str_repeat("=", $width) . "\n";
        $header .= str_pad("** PESANAN DAPUR **", $width, " ", STR_PAD_BOTH) . "\n";
        $header .= str_pad($namaOutlet, $width, " ", STR_PAD_BOTH) . "\n";
        $header .= str_repeat("=", $width) . "\n";
        $header .= "Order : #$order->nomor_pesanan\n";
        $header .= "Meja  : " . ($order->no_meja ?? '-') . "\n";

        // 2. Info Utama
        $content  = str_repeat("-", $width) . "\n";
        $content .= "PELANGGAN: " . strtoupper($order->nama_pelanggan) . "\n";
        $content .= "WAKTU    : " . \Carbon\Carbon::parse($order->created_at)->timezone('Asia/Jakarta')->format('H:i') . "\n";

        // --- FIX: MENGAMBIL KETERANGAN DARI TABEL ORDERS ---
        if(!empty($order->keterangan)) {
            $content .= str_repeat("-", $width) . "\n";
            $content .= "CATATAN:\n";
            // Dibuat HURUF BESAR agar koki tidak terlewat membaca instruksi khusus
            $content .=strtoupper($order->keterangan) . "\n";
        }
        
        $content .= str_repeat("-", $width) . "\n";

        // 3. Daftar Menu (Hanya Nama & Qty)
        // 3. Daftar Menu (Hanya Nama & Qty)
        foreach($order->orderDetails as $detail) {
            // Qty diletakkan di depan dan ditebalkan
            $content .= $detail->qty . "x " . strtoupper($detail->menu->nama_menu) . "\n";
            
            // --- Menambahkan Varian Khusus Dapur ---
            if (!empty($detail->variant)) {
                // Beri spasi di awal agar menjorok ke dalam, dan ubah ke huruf besar
                $variantText = "   (+ " . strtoupper($detail->variant) . ")";
                // Potong jika terlalu panjang agar tidak merusak format 32 karakter
                $content .= substr($variantText, 0, $width) . "\n";
            }
        }

        $content .= str_repeat("=", $width) . "\n";
        $content .= str_pad("Segera Diproses!", $width, " ", STR_PAD_BOTH) . "\n";
        $content .= str_pad("Flavory.id", $width, " ", STR_PAD_BOTH) . "\n\n\n\n\n"; // Beri ekstra enter agar mudah disobek

        $fullContent = $header . $content;
    @endphp

    <script>
        function printToKitchen() {
            const params = new URLSearchParams();
            // Kirim konten yang sudah diformat ke aplikasi Techpuram
            params.append('content', @json($fullContent));
            params.append('encode_format', 'UTF-8');

            const url = `btprinter://print?${params.toString()}`;
            window.location.href = url;

            // Otomatis kembali ke layar kasir setelah 2 detik
            setTimeout(function() {
                window.history.back();
            }, 2000);
        }

        // Picu cetak saat halaman dimuat
        window.onload = printToKitchen;
    </script>
</body>
</html>