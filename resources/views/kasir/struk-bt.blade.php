<!DOCTYPE html>
<html>
<head>
    <title>Printing...</title>
</head>
<body>
    <p>Mohon tunggu, sedang mengirim data ke printer Bluetooth...</p>

    @php
        // Setting lebar karakter untuk kertas 58mm (umumnya 32 karakter)
        $width = 32;

        // Fungsi Helper untuk merapikan baris (Nama Menu di kiri, Harga di kanan)
        $formatLine = function($left, $right) use ($width) {
            $spaceCount = $width - (strlen($left) + strlen($right));
            return $left . str_repeat(' ', max(1, $spaceCount)) . $right;
        };

        // Judul Outlet (Centered)
        $namaOutlet = strtoupper($outlet->nama_outlet ?? 'OUTLET');
        $alamatOutlet = $outlet->alamat ?? '';

        // Baris nama outlet - di-center
        $header = str_pad($namaOutlet, $width, " ", STR_PAD_BOTH) . "\n";

        // Baris alamat (hanya jika ada) - potong agar muat 32 karakter
        if (!empty($alamatOutlet)) {
            $alamatTrimmed = substr($alamatOutlet, 0, $width);
            $header .= str_pad($alamatTrimmed, $width, " ", STR_PAD_BOTH) . "\n";
        }

        $header .= str_repeat("=", $width) . "\n"; // Garis tegas bukan minus

        // Info Order
        $content = "No: #" . $order->nomor_pesanan . "\n";
        $content .= "Tgl: " . \Carbon\Carbon::parse($order->created_at)->format('d/m/y H:i') . "\n";
        $content .= "Plg: " . substr($order->nama_pelanggan, 0, 20) . "\n";
        $content .= str_repeat("-", $width) . "\n";

        // List Menu
        $subTotalRaw = 0; // Variabel baru untuk menampung subtotal sebelum diskon
        
        foreach($order->orderDetails as $detail) {
            $namaMenu = $detail->menu->nama_menu ?? 'Menu Terhapus';
            $content .= $namaMenu . "\n";
            
            // --- Menambahkan Varian Jika Ada ---
            if ($detail->variant) {
                $variantText = "  (+ " . $detail->variant . ")";
                // Potong teks jika melebihi lebar kertas (32 karakter) agar rapi
                $content .= substr($variantText, 0, $width) . "\n";
            }
            
            $itemTotal = $detail->qty * $detail->harga;
            $subTotalRaw += $itemTotal; // Akumulasi subtotal
            
            $subTotalStr = number_format($itemTotal, 0, ',', '.');
            $detailLine = $detail->qty . " x " . number_format($detail->harga, 0, ',', '.');
            $content .= $formatLine($detailLine, $subTotalStr) . "\n";
        }

        $content .= str_repeat("-", $width) . "\n";

        // --- TAMBAHAN LOGIKA DISKON ---
        if (isset($order->nominal_diskon) && $order->nominal_diskon > 0) {
            // Tampilkan baris Subtotal
            $content .= $formatLine("SUBTOTAL", number_format($subTotalRaw, 0, ',', '.')) . "\n";
            
            // Hitung nilai potongan
            $potongan = 0;
            $labelDiskon = "DISKON";
            
            if ($order->jenis_diskon === '%') {
                $potongan = ($subTotalRaw * $order->nominal_diskon) / 100;
                $labelDiskon = "DISKON (" . floatval($order->nominal_diskon) . "%)";
            } else {
                $potongan = $order->nominal_diskon;
            }
            
            // Tampilkan baris Diskon
            $content .= $formatLine($labelDiskon, "-" . number_format($potongan, 0, ',', '.')) . "\n";
        }

        // --- TAMBAHAN LOGIKA VOUCHER ---
        if (!empty($order->kode_voucher) && $order->nilai_voucher > 0) {
            if (!(isset($order->nominal_diskon) && $order->nominal_diskon > 0)) {
                // Tampilkan subtotal jika belum ditampilkan oleh blok diskon
                $content .= $formatLine("SUBTOTAL", number_format($subTotalRaw, 0, ',', '.')) . "\n";
            }
            $labelVoucher = "VOUCHER ";
            $content .= $formatLine($labelVoucher, "-" . number_format($order->nilai_voucher, 0, ',', '.')) . "\n";
        }
        // ------------------------------

        // Total
        $content .= $formatLine("TOTAL", "Rp" . number_format($order->total_harga, 0, ',', '.')) . "\n";
        $content .= $formatLine("BAYAR", number_format($order->uang_bayar, 0, ',', '.')) . "\n";
        $content .= $formatLine("KEMBALI", number_format($order->kembalian, 0, ',', '.')) . "\n";
        $content .= str_repeat("-", $width) . "\n";

        // --- TAMBAHAN: HIDE TEXT PWA OFFLINE PADA CATATAN ---
        $keteranganBersih = trim(str_replace('(Order via PWA Offline)', '', $order->keterangan ?? ''));
        if (!empty($keteranganBersih)) {
            $content .= "Catatan:\n";
            $wrappedCatatan = wordwrap($keteranganBersih, $width, "\n", true);
            $content .= $wrappedCatatan . "\n";
            $content .= str_repeat("-", $width) . "\n";
        }
        // ---------------------------------------------------

        // Footer & Digital Link
        $urlStruk = route('kasir.qr-struk', ['uuid' => $order->uuid, 'mode' => 'view']);
        
        // Memastikan variabel teksCatatan ada (fallback string kosong jika tidak di-pass dari controller)
        $teksCatatan = $teksCatatan ?? 'Terima Kasih Atas Kunjungan Anda!'; 
        $wrappedNote = wordwrap($teksCatatan, $width, "\n", true);
        $barisCatatan = explode("\n", $wrappedNote);
        
        foreach ($barisCatatan as $baris) {
            $content .= str_pad(trim($baris), $width, " ", STR_PAD_BOTH) . "\n";
        }
        $content .= str_pad("Powered by Flavory.id", $width, " ", STR_PAD_BOTH) . "\n\n\n";

        // Gabungkan Header + Content
        $fullText = $header . $content;
    @endphp

    <script>
        function printToBluetooth(content) {
            const params = new URLSearchParams();
            params.append('content', content);
            
            // URL Scheme aplikasi Printer Bluetooth Sederhana
            const url = `btprinter://print?${params.toString()}`;
            
            // Eksekusi URL Scheme
            window.location.href = url;

            // Kembali ke halaman sebelumnya setelah 2 detik
            setTimeout(function() {
                window.history.back();
            }, 2000);
        }

        // Jalankan fungsi print dengan data dari PHP
        printToBluetooth(@json($fullText));
    </script>
</body>
</html>