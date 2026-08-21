<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{KasirMenuController, RegisterController, VerifikasiController, 
KasirController, KasirOrderController, KasirOrderHistoryController, KasirOrderBatalController,
KasirFormController, KasirStokController, KasirProfileController, KasirLaporanBisnisController,
KasirLaporanPengeluaranController, KasirModalController, PageOutletController, KasirPerformaController,
KasirNoteController, KasirLanggananController, KasirAkunController, PublicController, PublicEventController,
KasirVoucherController};
use App\Http\Middleware\CheckSubscriptionStatus;
use App\Http\Controllers\{AdminController, AdminPartnerController, AdminAkunPartnerController, PartnerResetPasswordController, AdminProfileController, AdminAktivitasController, AdminEventController, AdminPesertaController};

Route::get('/', [PublicController::class, 'index'])->name('home');

// PWA Splash Screen — start_url untuk PWA, redirect ke login setelah animasi
Route::get('/splash', function () {
    // Jika sudah login, langsung ke dashboard (tidak perlu animasi)
    if (auth()->check()) {
        return redirect()->route('kasir.dashboard');
    }
    return view('splash');
})->name('splash');


Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/waiting-payment/{invoice}/{partner_uuid}', [RegisterController::class, 'showWaitingPayment'])->name('waiting.payment');

Route::get('/kebijakan-privasi', [PublicController::class, 'kebijakanPrivasi'])->name('kebijakan.privasi');
Route::get('/tentang', [PublicController::class, 'tentang'])->name('tentang');
Route::get('/faq', [PublicController::class, 'faq'])->name('faq');
Route::get('/syarat-ketentuan', [PublicController::class, 'SyaratKetentuan'])->name('syarat.ketentuan');

// Public Event Routes (tanpa login)
Route::prefix('event')->name('event.')->group(function () {
    Route::get('/',                              [PublicEventController::class, 'index'])->name('index');
    Route::get('/{identifier}/peserta',          [\App\Http\Controllers\PublicPesertaController::class, 'show'])->name('peserta');
    Route::get('/{identifier}/peserta/search',   [\App\Http\Controllers\PublicPesertaController::class, 'search'])->name('peserta.search');
    Route::get('/{identifier}',                  [PublicEventController::class, 'show'])->name('show');
    Route::post('/{identifier}/daftar',          [PublicEventController::class, 'daftar'])->name('daftar');
});

Route::get('/verifikasi/{id}', [VerifikasiController::class, 'show'])->name('verifikasi.show');
Route::post('/verifikasi/{id}', [VerifikasiController::class, 'verify'])->name('verifikasi.submit');

Route::prefix('panduan')->name('panduan.')->group(function () {
    Route::view('/daftar', 'panduan.daftar')->name('daftar');
    Route::view('/login', 'panduan.login')->name('login');
    Route::view('/menu', 'panduan.menu')->name('menu');
    Route::view('/form', 'panduan.form')->name('form');
    Route::view('/catatan', 'panduan.catatan')->name('catatan');
    Route::view('/pesanan', 'panduan.pesanan')->name('pesanan');
    Route::view('/riwayat-pesanan', 'panduan.riwayat-pesanan')->name('riwayat-pesanan');
    Route::view('/pesanan-batal', 'panduan.pesanan-batal')->name('pesanan-batal');
    Route::view('/laporan-bisnis', 'panduan.laporan-bisnis')->name('laporan-bisnis');
    Route::view('/performa-bisnis', 'panduan.performa-bisnis')->name('performa-bisnis');
    Route::view('/mineai', 'panduan.mineai')->name('mineai');
});

Route::get('/lupa-password', [AuthController::class, 'showLupaPassword'])->name('lupa-password');
Route::post('/lupa-password', [AuthController::class, 'kirimKodeVerifikasi'])->name('lupa-password.kirim');
Route::get('/verifikasi-password', [AuthController::class, 'showVerifikasi'])->name('password.verifikasi');
Route::post('/verifikasi-password', [AuthController::class, 'verifikasiKode'])->name('password.verifikasi.proses');


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/coba-demo/{token}', [AuthController::class, 'showDemoLogin'])->name('demo.login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh-captcha', [AuthController::class, 'refreshCaptcha'])->name('captcha.refresh');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/check-availability', [RegisterController::class, 'checkAvailability']);

Route::get('/kasir/qr-struk/{uuid}', [KasirOrderHistoryController::class, 'showQrStruk'])->name('kasir.qr-struk');

Route::get('/outlet/{page_name}', [PageOutletController::class, 'show'])->name('outlet.show');
Route::get('/outlet/{page_name}/search', [PageOutletController::class, 'search'])->name('outlet.search');
Route::get('/outlet/{page_name}/menu', [PageOutletController::class, 'allMenu'])->name('outlet.menu.all');
Route::post('/outlet/{page_name}/voucher/apply', [PageOutletController::class, 'applyVoucher'])->name('outlet.voucher.apply');

// Proteksi Halaman
Route::middleware(['auth', 'role:kasir', 'cek.langganan'])->group(function () {
    // Route untuk akses file private (bukti bayar & pengeluaran)
    Route::get('/private/bukti_bayar/{filename}', function ($filename) {
        $path = storage_path('app/private/bukti_bayar/' . $filename);
        if (!file_exists($path)) abort(404);
        return response()->file($path);
    })->name('private.bukti_bayar');
    
    Route::get('/private/pengeluaran/{filename}', function ($filename) {
        $path = storage_path('app/private/pengeluaran/' . $filename);
        if (!file_exists($path)) abort(404);
        return response()->file($path);
    })->name('private.pengeluaran');
    
    Route::get('/kasir/index', [KasirController::class, 'index'])->name('kasir.dashboard');
    Route::get('/kasir/gallery-menu', [KasirMenuController::class, 'index'])->name('kasir.menu.index');
    Route::post('/kasir/gallery-menu/store', [KasirMenuController::class, 'store'])->name('kasir.menu.store');
    Route::get('/kasir/menu/search', [KasirMenuController::class, 'search'])->name('kasir.menu.search');
    Route::put('/kasir/menu/{id}', [KasirMenuController::class, 'update'])->name('kasir.menu.update');
    Route::delete('/kasir/menu/{id}', [KasirMenuController::class, 'destroy'])->name('kasir.menu.destroy');
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/kasir/bayar/{order}', [KasirController::class, 'bayar'])->name('kasir.bayar');
    Route::get('/kasir/order/create', [KasirOrderController::class, 'index'])->name('kasir.order.create');
    Route::post('/kasir/order/store', [KasirOrderController::class, 'store'])->name('kasir.order.store');
    Route::post('/kasir/order/store-pay', [KasirOrderController::class, 'storeAndPay'])->name('kasir.order.storeAndPay');
    Route::post('/kasir/order/update/{order}', [KasirOrderController::class, 'update'])->name('kasir.order.update');
    Route::delete('/kasir/order/{order}', [KasirController::class, 'destroy'])->name('kasir.order.destroy');
    Route::get('/kasir/riwayat', [KasirOrderHistoryController::class, 'index'])->name('kasir.riwayat.index');
    Route::get('/kasir/order-batal', [KasirOrderBatalController::class, 'index'])->name('kasir.order-batal.index');
    Route::get('/kasir/print-struk/{uuid}', [KasirOrderHistoryController::class, 'printStruk'])->name('kasir.print-struk');
    Route::get('/kasir/print-bt/{uuid}', [KasirOrderHistoryController::class, 'printBluetooth'])->name('kasir.print-bt');
    Route::get('/kasir/print-kitchen-bt/{order}', [KasirController::class, 'printKitchenBluetooth'])->name('kasir.print-kitchen-bt');
    Route::get('/kasir/pengaturan-form', [KasirFormController::class, 'index'])->name('kasir.pengaturan.index');
    Route::post('/kasir/pengaturan-form/update', [KasirFormController::class, 'update'])->name('kasir.pengaturan.update');
    // Voucher routes
    Route::get('/kasir/voucher', [KasirVoucherController::class, 'index'])->name('kasir.voucher.index');
    Route::post('/kasir/voucher', [KasirVoucherController::class, 'store'])->name('kasir.voucher.store');
    Route::delete('/kasir/voucher/{voucher}', [KasirVoucherController::class, 'destroy'])->name('kasir.voucher.destroy');
    Route::post('/kasir/voucher/check-kode', [KasirVoucherController::class, 'checkKode'])->name('kasir.voucher.check-kode');
    // Cek voucher saat order (validasi + ambil data potongan)
    Route::post('/kasir/voucher/apply', [KasirVoucherController::class, 'applyVoucher'])->name('kasir.voucher.apply');
    Route::get('/stok-management', [KasirStokController::class, 'index'])->name('kasir.stok.index');
    Route::post('/stok/store', [KasirStokController::class, 'store'])->name('kasir.stok.store');
    Route::post('/stok/update/{stok}', [KasirStokController::class, 'update'])->name('kasir.stok.update');
    Route::post('/stok/delete/{stok}', [KasirStokController::class, 'destroy'])->name('kasir.stok.delete');
    Route::post('/stok/update-warna/{stok}', [KasirStokController::class, 'updateWarna'])->name('kasir.stok.updateWarna');
    Route::post('/stok/atur-stok/{stok}', [KasirStokController::class, 'aturStok'])->name('kasir.stok.aturStok');
    Route::post('/kasir/order-batal/{order}/restore', [KasirOrderBatalController::class, 'restore'])->name('kasir.order-batal.restore');
    Route::get('/kasir/profile-outlet', [KasirProfileController::class, 'edit'])->name('kasir.profile-outlet.edit');
    Route::post('/kasir/profile-outlet', [KasirProfileController::class, 'update'])->name('kasir.profile-outlet.update');
    Route::get('/kasir/laporan-bisnis', [KasirLaporanBisnisController::class, 'index'])->name('kasir.laporan.index');
    Route::get('/kasir/laporan-bisnis/export', [KasirLaporanBisnisController::class, 'export'])->name('kasir.laporan.export');
     Route::get('/kasir/laporan-bisnis/chart', [KasirLaporanBisnisController::class, 'getChartData'])->name('kasir.laporan-bisnis.chart');
    Route::get('/kasir/pengeluaran', [KasirLaporanPengeluaranController::class, 'index'])->name('kasir.pengeluaran.index');
    Route::post('/kasir/pengeluaran', [KasirLaporanPengeluaranController::class, 'store'])->name('kasir.pengeluaran.store');
    Route::patch('/kasir/pengeluaran/{id}/restore', [KasirLaporanPengeluaranController::class, 'restore'])->name('kasir.pengeluaran.restore');
    Route::put('/kasir/pengeluaran/{id}', [KasirLaporanPengeluaranController::class, 'update'])->name('kasir.pengeluaran.update');
    Route::delete('/kasir/pengeluaran/{id}', [KasirLaporanPengeluaranController::class, 'destroy'])->name('kasir.pengeluaran.destroy');
    Route::get('/kasir/pengeluaran/export', [KasirLaporanPengeluaranController::class, 'export'])->name('kasir.pengeluaran.export');
    Route::get('/modal', [KasirModalController::class, 'index'])->name('kasir.modal.index');
    Route::post('/modal', [KasirModalController::class, 'store'])->name('kasir.modal.store');
    Route::patch('/modal/{id}/restore', [KasirModalController::class, 'restore'])->name('kasir.modal.restore');
    Route::delete('/modal/{id}', [KasirModalController::class, 'destroy'])->name('kasir.modal.destroy');
    Route::get('/modal/export', [KasirModalController::class, 'export'])->name('kasir.modal.export');
    Route::get('/kasir/profile-outlet', [KasirProfileController::class, 'edit'])->name('kasir.profile-outlet.edit');
    Route::post('/kasir/profile-outlet', [KasirProfileController::class, 'update'])->name('kasir.profile-outlet.update');
    Route::get('/kasir/profile-outlet/download-qr', [KasirProfileController::class, 'downloadQr'])->name('kasir.profile-outlet.download-qr');
    Route::post('/kasir/update-sound', [KasirController::class, 'updateSound'])->name('kasir.update-sound');
    Route::post('/kasir/order/sync', [KasirOrderController::class, 'syncOffline']);
    Route::get('/kasir/csrf-token', function() {
        return response()->json(['token' => csrf_token()]);
    });
    Route::post('/kasir/offline/check-synced', [KasirOrderController::class, 'checkOfflineSynced'])->name('kasir.offline.check-synced');
    Route::post('/kasir/sync-actions', [KasirController::class, 'syncActions'])->name('kasir.sync-actions');
    Route::post('/kasir/konfirmasi-bulk', [KasirController::class, 'bulkKonfirmasi'])->name('kasir.konfirmasi.bulk');
    Route::get('/kasir/performa-bisnis', [KasirPerformaController::class, 'index'])->name('kasir.performa');
    Route::get('/kasir/mineai',                    [\App\Http\Controllers\MineAIController::class, 'index'])->name('kasir.mineai');
    Route::get('/kasir/mineai/initial-insight',    [\App\Http\Controllers\MineAIController::class, 'getInitialInsight'])->name('kasir.mineai.insight');
    Route::get('/kasir/mineai/daily-usage',        [\App\Http\Controllers\MineAIController::class, 'getDailyUsage'])->name('kasir.mineai.daily-usage');
    Route::post('/kasir/mineai/chat',              [\App\Http\Controllers\MineAIController::class, 'chatWithGroq'])->name('kasir.mineai.chat');
    Route::get('/kasir/mineai/session/{uuid}',     [\App\Http\Controllers\MineAIController::class, 'loadSession'])->name('kasir.mineai.session.load');
    Route::delete('/kasir/mineai/session/{uuid}',  [\App\Http\Controllers\MineAIController::class, 'deleteSession'])->name('kasir.mineai.session.delete');
    Route::get('/kasir/notes', [KasirNoteController::class, 'index'])->name('kasir.notes.index');
    Route::post('/kasir/notes', [KasirNoteController::class, 'store'])->name('kasir.notes.store');
    Route::put('/kasir/notes/{note}', [KasirNoteController::class, 'update'])->name('kasir.notes.update');
    Route::delete('/kasir/notes/{note}', [KasirNoteController::class, 'destroy'])->name('kasir.notes.destroy');
    Route::post('/kasir/notes/{note}/toggle', [KasirNoteController::class, 'toggleStatus'])->name('kasir.notes.toggle');
    Route::get('/langganan', [KasirLanggananController::class, 'index'])->name('kasir.langganan.index');
    Route::post('/langganan/subscribe', [KasirLanggananController::class, 'subscribe'])->name('kasir.langganan.subscribe');
    Route::post('/langganan/unsubscribe', [KasirLanggananController::class, 'unsubscribe'])->name('kasir.langganan.unsubscribe');
    Route::get('/langganan/sukses', [KasirLanggananController::class, 'sukses'])->name('kasir.langganan.sukses');
    Route::get('/kasir/pengaturan-akun', [KasirAkunController::class, 'index'])->name('kasir.akun.index');
    Route::post('/kasir/pengaturan-akun/update', [KasirAkunController::class, 'update'])->name('kasir.akun.update');
    Route::post('/kasir/pengaturan-akun/cek-username', [KasirAkunController::class, 'checkUsername'])->name('kasir.akun.cek-username');
});

Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

Route::get('/{page_name}', [PageOutletController::class, 'show'])->name('outlet.public');
Route::post('/{page_name}/order', [PageOutletController::class, 'storeOrder'])->name('outlet.order.store');
Route::get('/{page_name}/konfirmasi/{uuid}', [PageOutletController::class, 'konfirmasi'])->name('outlet.order.konfirmasi');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/index', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/partner-aktif', [AdminPartnerController::class, 'aktif'])->name('partner.aktif');
    Route::get('/partner-jatuh-tempo', [AdminPartnerController::class, 'jatuhTempo'])->name('partner.jatuh_tempo');
    Route::get('/partner-nonaktif', [AdminPartnerController::class, 'nonaktif'])->name('partner.nonaktif');
    Route::get('/partner-tanpa-status', [AdminPartnerController::class, 'tanpaStatus'])->name('partner.tanpa_status');
    Route::get('/partner/{uuid}/detail', [AdminPartnerController::class, 'detail'])->name('partner.detail');
    Route::get('/akun-partner', [AdminAkunPartnerController::class, 'index'])->name('akun_partner.index');
    Route::post('/akun-partner/{uuid}/generate-link', [AdminAkunPartnerController::class, 'generateLink'])->name('akun_partner.generate_link');
    Route::patch('/partner/{uuid}/aktif', [AdminPartnerController::class, 'setAktif'])->name('partner.set_aktif');
    Route::patch('/partner/{uuid}/jatuh-tempo', [AdminPartnerController::class, 'setJatuhTempo'])->name('partner.set_jatuh_tempo');
    Route::patch('/partner/{uuid}/nonaktif', [AdminPartnerController::class, 'setNonaktif'])->name('partner.set_nonaktif');
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::get('/aktivitas', [AdminAktivitasController::class, 'index'])->name('aktivitas.index');
    Route::get('/partner/{uuid}/aktivitas', [AdminAktivitasController::class, 'detail'])->name('partner.aktivitas');

    // Event Management Routes
    Route::get('/event/kelola-event', [AdminEventController::class, 'index'])->name('event.kelola-event');
    Route::post('/event/store', [AdminEventController::class, 'store'])->name('event.store');
    Route::post('/event/update/{uuid}', [AdminEventController::class, 'update'])->name('event.update');
    Route::post('/event/toggle-status/{uuid}', [AdminEventController::class, 'toggleStatus'])->name('event.toggle-status');
    Route::delete('/event/delete/{uuid}', [AdminEventController::class, 'destroy'])->name('event.destroy');

    // Participant Management Routes
    Route::get('/event/kelola-peserta', [AdminPesertaController::class, 'index'])->name('event.kelola-peserta');
    Route::get('/event/data-peserta/{uuid}', [AdminPesertaController::class, 'detail'])->name('event.data-peserta');
    Route::post('/event/peserta/upload-sertifikat/{uuid}', [AdminPesertaController::class, 'uploadSertifikat'])->name('event.upload-sertifikat');
});
Route::get('/reset-password/{token}', [PartnerResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PartnerResetPasswordController::class, 'resetPassword'])->name('password.update');