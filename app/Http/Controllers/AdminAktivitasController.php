<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Order;
use App\Models\Pengeluaran;
use App\Models\Modal;
use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAktivitasController extends Controller
{
    public function index()
    {
        // Ambil partner dengan user (role kasir)
        $partners = Partner::whereHas('user', function ($q) {
            $q->where('role', 'kasir');
        })->with(['user', 'outlet'])->get();

        // Siapkan data aktivitas 7 hari terakhir untuk grafik mini tiap partner
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $last7Days[] = Carbon::today()->subDays($i)->toDateString();
        }

        foreach ($partners as $partner) {
            // Hitung jumlah order per hari selama 7 hari terakhir
            $activityData = [];
            foreach ($last7Days as $date) {
                $count = Order::where('partner_id', $partner->id)
                    ->whereDate('created_at', $date)
                    ->count();
                $activityData[] = $count;
            }
            $partner->activity_chart = $activityData;
        }

        return view('admin.aktivitas', compact('partners', 'last7Days'));
    }

    public function detail(Request $request, $uuid)
    {
        $partner = Partner::where('uuid', $uuid)->with(['user', 'outlet'])->firstOrFail();

        // 1. FILTER WAKTU (Default hari ini)
        $startDateStr = $request->input('start_date', Carbon::today()->toDateString());
        $endDateStr = $request->input('end_date', Carbon::today()->toDateString());

        $start = Carbon::parse($startDateStr)->startOfDay();
        $end = Carbon::parse($endDateStr)->endOfDay();

        // 2. DATA TRANSAKSI & PENJUALAN
        $orders = Order::where('partner_id', $partner->id)
            ->where('status', 'success')
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $transactionCount = $orders->count();
        $totalSales = $orders->sum('total_harga');

        // 3. DATA PENGELUARAN
        $totalExpenses = Pengeluaran::where('partner_id', $partner->id)
            ->whereBetween('created_at', [$start, $end])
            ->sum('total');

        // 4. DATA MODAL USAHA
        $totalCapital = Modal::where('partner_id', $partner->id)
            ->whereBetween('created_at', [$start, $end])
            ->sum('nominal');

        // 5. DATA MENU
        $menuCount = Menu::where('partner_id', $partner->id)->count();
        $menuActive = Menu::where('partner_id', $partner->id)->where('status_menu', 'tersedia')->count();

        // 6. LOGIKA GRAFIK AKTIVITAS
        $chartLabels = [];
        $chartData = [];

        $diffInDays = $start->diffInDays($end);

        if ($diffInDays <= 1) {
            // Jika rentang <= 24 jam (sehari), tampilkan per jam
            for ($h = 0; $h < 24; $h++) {
                $hourStr = sprintf('%02d:00', $h);
                $chartLabels[] = $hourStr;
                
                $count = $orders->filter(function($o) use ($h) {
                    return Carbon::parse($o->created_at)->hour == $h;
                })->count();
                
                $chartData[] = $count;
            }
        } else {
            // Jika lebih dari sehari, tampilkan per tanggal
            $currentDate = $start->copy();
            while ($currentDate <= $end) {
                $dateStr = $currentDate->toDateString();
                $chartLabels[] = $currentDate->translatedFormat('d M Y');
                
                $count = $orders->filter(function($o) use ($dateStr) {
                    return Carbon::parse($o->created_at)->toDateString() == $dateStr;
                })->count();
                
                $chartData[] = $count;
                $currentDate->addDay();
            }
        }

        return view('admin.detail-aktivitas', compact(
            'partner',
            'startDateStr',
            'endDateStr',
            'transactionCount',
            'totalSales',
            'totalExpenses',
            'totalCapital',
            'menuCount',
            'menuActive',
            'chartLabels',
            'chartData'
        ));
    }
}
