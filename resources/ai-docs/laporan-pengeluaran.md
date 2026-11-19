# Panduan Laporan Pengeluaran — Flavory.id

## Cara Mengakses
Buka halaman **Laporan Pengeluaran** lewat salah satu cara berikut:
- **Kasir:** klik tab "Opsi Lainnya" di navbar → pilih "Laporan Pengeluaran"
- **Owner/Manager:** buka sidebar → klik "Laporan Pengeluaran"

---

## Fitur yang Tersedia

### 1. Filter Tanggal
Di bagian atas halaman terdapat filter rentang tanggal:
- Isi **Dari Tanggal** dan **Sampai Tanggal**, lalu klik **Terapkan**
- Atau gunakan tombol cepat: **1 Hari**, **1 Minggu**, **1 Bulan**, **1 Tahun**

### 2. Kartu Ringkasan
Empat kartu menampilkan total pengeluaran berdasarkan periode yang dipilih:
- **Total Pengeluaran** — keseluruhan semua kategori
- **Operasional Usaha** — bahan baku, sewa, utilitas, dll.
- **Operasional Karyawan** — gaji, tunjangan, dll.
- **Lainnya** — pengeluaran di luar dua kategori di atas

### 3. Grafik Batang
Visualisasi pengeluaran harian/periodik dalam bentuk grafik batang otomatis muncul di bawah kartu ringkasan.

### 4. Input Pengeluaran Baru
Form input terdiri dari:
- **Kategori**: Operasional Usaha / Operasional Karyawan / Lainnya
- **Nama Barang**: keterangan item (contoh: "Es Batu", "Bayar Listrik")
- **Harga**: harga satuan item
- **Qty**: jumlah/kuantitas (mendukung desimal, contoh: 0.5)
- **Total**: dihitung otomatis dari Harga × Qty, bisa diedit manual
- **Keterangan**: catatan tambahan opsional per item
- **Bukti Foto**: upload foto struk/nota (JPG/PNG, maks 2 MB, opsional)
- Klik **+ Tambah Baris** untuk mencatat beberapa item sekaligus dalam satu entri
- Klik **Simpan Pengeluaran** untuk menyimpan

### 5. Tabel Rekap Data
Menampilkan semua pengeluaran yang tersimpan dengan kolom:
- No, Kode Pengeluaran, Kategori, Nama Item, Harga, Qty, Total, Tanggal, Aksi

Tab di atas tabel:
- **Data Tersedia** — pengeluaran aktif
- **Data Terhapus** — pengeluaran yang sudah dihapus (bisa dipulihkan)

Filter kategori tersedia via dropdown **Semua Kategori / Operasional Usaha / Operasional Karyawan / Lainnya**.

### 6. Export ke Excel ✅ FITUR INI SUDAH ADA
**YA, fitur export Excel sudah tersedia dan bisa digunakan sekarang.**

Langkah export:
1. Atur filter tanggal sesuai periode yang ingin diekspor (misal: 1 Bulan)
2. Klik tombol **EXCEL** berwarna hijau di pojok kanan atas tabel rekap
3. File Excel (.xlsx) langsung terunduh otomatis ke perangkat

Kolom yang tersedia di file Excel hasil export:
`No | Kategori | Nama Item | Harga (Rp) | Qty | Total (Rp) | Keterangan | Tanggal`

> Tombol EXCEL hanya muncul di tab "Data Tersedia", bukan di tab "Data Terhapus".

### 7. Folder Bukti Foto
Klik tombol **FOLDER BUKTI** untuk melihat semua foto struk/nota yang pernah diunggah dalam satu galeri.

---

## Edit & Hapus Pengeluaran
- Klik ikon **pensil** (✏️) di kolom Aksi untuk mengedit data pengeluaran
- Klik ikon **tempat sampah** (🗑️) untuk menghapus; data masuk ke tab "Data Terhapus"
- Di tab "Data Terhapus", klik **Pulihkan** untuk mengembalikan data yang terhapus

---

## Tips Pencatatan
- Catat pengeluaran sesegera mungkin agar tidak ada yang terlewat
- Selalu lampirkan foto bukti (struk/nota) untuk kemudahan audit owner
- Tulis keterangan yang jelas, contoh: "Beli bahan baku sayur", "Bayar listrik"
- Data pengeluaran yang tercatat otomatis mempengaruhi kalkulasi profit di halaman Ikhtisar Bisnis
