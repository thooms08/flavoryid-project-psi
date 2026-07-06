<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\KasirLanggananController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RegisterController as ApiRegisterController;
use App\Http\Controllers\Api\VerificationController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\SettingFormController;
use App\Http\Controllers\Api\OrderHistoryController;
use App\Http\Controllers\Api\OrderTransactionController;
use App\Http\Controllers\Api\OrderFailController;
use App\Http\Controllers\Api\NoteStrukController;
use App\Http\Controllers\Api\BusinessReportController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\CapitalController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\PerformanceController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\SubscribeController;
// Existing webhook & check routes (keep as-is)
Route::post('/wijayapay/notification', [RegisterController::class, 'wijayapayNotification']);
Route::get('orders/{uuid}/receipt-digital', [OrderHistoryController::class, 'receiptDigitalData']);

Route::get('/check-payment-status/{invoice}', function($invoice) {
    $status = \App\Models\MediaQr::where('invoice_number', $invoice)->value('payment_status');
    return response()->json(['status' => $status]);
});

Route::get('/check-subscription-status/{invoice}', [KasirLanggananController::class, 'checkStatus']);

Route::get('/check-subscription-status/{invoice}', function($invoice) {
    $status = \App\Models\Subscription::where('invoice_number', $invoice)->value('status');
    return response()->json(['status' => $status ?? 'pending']);
});

// API v1 - structured for mobile apps
Route::prefix('v1')->middleware('force.json')->group(function () {
    // Public routes (no auth)
    Route::post('auth/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::post('auth/register', [ApiRegisterController::class, 'register']);
    Route::get('auth/check-availability', [RegisterController::class, 'checkAvailability']);
    Route::post('auth/verify/{uuid}', [VerificationController::class, 'verify']);
    
    Route::post('auth/forgot-password', [ForgotPasswordController::class, 'sendResetCode']);
    Route::post('auth/forgot-password/verify', [ForgotPasswordController::class, 'verifyCode']);
  
    

    // Public read-only product listing
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);

    // Protected routes (require Sanctum token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);

        // Resource-style endpoints: create / update / delete products
        Route::apiResource('products', ProductController::class)->only(['store', 'update', 'destroy']);
        Route::post('orders/sync-offline', [OrderController::class, 'syncOffline']);
        Route::apiResource('orders', OrderController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('orders/history', [OrderHistoryController::class, 'index']);
        Route::get('orders/fail', [OrderFailController::class, 'index']);
        Route::post('orders/fail/{id}/restore', [OrderFailController::class, 'restore']);
        Route::post('orders/transaction', [OrderTransactionController::class, 'processTransaction']);
        Route::post('orders/sync-transactions', [OrderTransactionController::class, 'syncOfflineTransactions']);
        
        Route::get('menus', [MenuController::class, 'index']);
        Route::post('menus', [MenuController::class, 'store']);
        Route::delete('menus/{id}', [MenuController::class, 'destroy']);
        Route::post('menus/{id}', [MenuController::class, 'update']);
        
        Route::get('settings/form', [SettingFormController::class, 'index']);
        Route::post('settings/form/update', [SettingFormController::class, 'update']);
        
        Route::get('notes', [NoteStrukController::class, 'index']);
        Route::post('notes', [NoteStrukController::class, 'store']);
        Route::put('notes/{id}', [NoteStrukController::class, 'update']);
        Route::delete('notes/{id}', [NoteStrukController::class, 'destroy']);
        Route::patch('notes/{id}/toggle', [NoteStrukController::class, 'toggleStatus']);
        
        Route::get('reports/business', [BusinessReportController::class, 'index']);
        Route::get('reports/business/chart', [BusinessReportController::class, 'getChartData']);
        
        Route::get('/pengeluaran', [ExpenseController::class, 'index']);
        Route::post('/pengeluaran', [ExpenseController::class, 'store']);
        Route::put('/pengeluaran/{id}', [ExpenseController::class, 'update']);
        Route::patch('/pengeluaran/{id}/status', [ExpenseController::class, 'updateStatus']);
        
        // Rute Modal Usaha
        Route::get('/capital', [CapitalController::class, 'index']);
        Route::post('/capital', [CapitalController::class, 'store']);
        Route::patch('/capital/{id}/status', [CapitalController::class, 'updateStatus']);
        Route::get('/capital/export', [CapitalController::class, 'export']);
        
                // Profile Outlet
        Route::get('/profile-outlet', [ProfileController::class, 'index']);
        Route::post('/profile-outlet', [ProfileController::class, 'update']);
        Route::get('/profile-outlet/qr', [ProfileController::class, 'downloadQr']);
        
        // API Management Stok
        Route::get('stocks', [StockController::class, 'index']);
        Route::post('stocks', [StockController::class, 'store']);
        Route::put('stocks/{stok}', [StockController::class, 'update']);
        Route::delete('stocks/{stok}', [StockController::class, 'destroy']);
        Route::post('stocks/{stok}/warna', [StockController::class, 'updateWarna']);
        Route::post('stocks/{stok}/atur-stok', [StockController::class, 'aturStok']);
        
        Route::get('performance', [PerformanceController::class, 'index']);
        Route::get('settings/account', [AccountController::class, 'index']);
        Route::post('settings/account/update', [AccountController::class, 'update']);
        Route::post('settings/account/check-username', [AccountController::class, 'checkUsername']);
        
        Route::get('subscribe', [SubscribeController::class, 'index']);
        Route::post('subscribe', [SubscribeController::class, 'subscribe']);
        Route::post('unsubscribe', [SubscribeController::class, 'unsubscribe']);
                
        Route::get('orders/{uuid}/receipt-bt', [OrderHistoryController::class, 'receiptBtData']);
        
    });
   Route::get('reports/business/export', [BusinessReportController::class, 'export']);
});