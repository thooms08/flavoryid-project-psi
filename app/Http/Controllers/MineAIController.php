<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\StokBarang;
use App\Models\MineAiSession;
use App\Models\MineAiMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class MineAIController extends Controller
{
    // ─────────────────────────────────────────────
    //  INDEX — tampilkan halaman MineAI
    //  Kirim daftar sesi milik partner ini
    // ─────────────────────────────────────────────
    public function index()
    {
        $partnerId = Auth::user()->partner->id;
        $sessions  = MineAiSession::where('partner_id', $partnerId)
            ->withCount('messages')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('kasir.mineai', compact('sessions'));
    }

    // ─────────────────────────────────────────────
    //  LOAD SESSION — ambil pesan dari sesi tertentu
    // ─────────────────────────────────────────────
    public function loadSession($uuid)
    {
        $partnerId = Auth::user()->partner->id;
        $session   = MineAiSession::where('uuid', $uuid)
            ->where('partner_id', $partnerId)
            ->with('messages')
            ->firstOrFail();

        return response()->json([
            'uuid'             => $session->uuid,
            'title'            => $session->title,
            'business_context' => $session->business_context,
            'messages'         => $session->messages->map(fn($m) => [
                'role'    => $m->role,
                'content' => $m->content,
            ]),
        ]);
    }

    // ─────────────────────────────────────────────
    //  DELETE SESSION
    // ─────────────────────────────────────────────
    public function deleteSession($uuid)
    {
        $partnerId = Auth::user()->partner->id;
        $session   = MineAiSession::where('uuid', $uuid)
            ->where('partner_id', $partnerId)
            ->firstOrFail();

        $session->delete();
        return response()->json(['success' => true]);
    }

    // ─────────────────────────────────────────────
    //  GET INITIAL INSIGHT  (AJAX GET)
    // ─────────────────────────────────────────────
    public function getInitialInsight()
    {
        $tz        = 'Asia/Jakarta';
        $partnerId = Auth::user()->partner->id;

        $end   = Carbon::today($tz)->endOfDay();
        $start = Carbon::today($tz)->subDays(29)->startOfDay();

        $orders = Order::with('orderDetails.menu')
            ->where('partner_id', $partnerId)
            ->where('status', 'success')
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $prevEnd    = $start->copy()->subSecond();
        $prevStart  = $prevEnd->copy()->subDays(29)->startOfDay();
        $prevOrders = Order::where('partner_id', $partnerId)
            ->where('status', 'success')
            ->whereBetween('created_at', [$prevStart, $prevEnd])
            ->get();

        $stokBarangList = StokBarang::where('partner_id', $partnerId)->get();
        $stokTipis = $stokBarangList->filter(function ($stok) {
            $terakhir = $stok->stokHarian()->orderBy('tanggal', 'desc')->first();
            if (!$terakhir) return false;
            return $terakhir->jumlah <= ($stok->min_merah ?? 5);
        });

        $omzetC  = (float) $orders->sum('total_harga');
        $omzetP  = (float) $prevOrders->sum('total_harga');
        $countC  = (int)   $orders->count();
        $avgC    = $countC > 0 ? $omzetC / $countC : 0;
        $discC   = (float) $orders->sum('nominal_diskon');
        $discRat = $omzetC > 0 ? ($discC / $omzetC) * 100 : 0;

        $growthRate = 0;
        if ($omzetP > 0)       $growthRate = (($omzetC - $omzetP) / $omzetP) * 100;
        elseif ($omzetC > 0)   $growthRate = 100;

        $menuStats = [];
        foreach ($orders as $order) {
            foreach ($order->orderDetails as $d) {
                $name = $d->menu->nama_menu ?? 'Menu Terhapus';
                $menuStats[$name] = $menuStats[$name] ?? ['nama' => $name, 'qty' => 0, 'rev' => 0];
                $menuStats[$name]['qty'] += (int) $d->qty;
                $menuStats[$name]['rev'] += (float) $d->qty * $d->harga;
            }
        }
        usort($menuStats, fn($a, $b) => $b['qty'] <=> $a['qty']);
        $topMenu   = $menuStats[0] ?? ['nama' => '-', 'qty' => 0, 'rev' => 0];
        $totalQty  = array_sum(array_column($menuStats, 'qty'));
        $top3Qty   = array_sum(array_column(array_slice($menuStats, 0, 3), 'qty'));
        $top3Share = $totalQty > 0 ? ($top3Qty / $totalQty) * 100 : 0;

        $byHour = []; $byDay = [];
        foreach ($orders as $o) {
            $d = Carbon::parse($o->created_at)->timezone($tz);
            $byHour[$d->format('H:00')] = ($byHour[$d->format('H:00')] ?? 0) + 1;
            $byDay[$d->translatedFormat('l')] = ($byDay[$d->translatedFormat('l')] ?? 0) + 1;
        }
        $bestHour = !empty($byHour) ? array_key_first(array_filter($byHour, fn($v) => $v === max($byHour))) : '-';
        $bestDay  = !empty($byDay)  ? array_key_first(array_filter($byDay,  fn($v) => $v === max($byDay)))  : '-';

        $trend      = $growthRate > 10 ? 'naik' : ($growthRate < -10 ? 'turun' : 'stabil');
        $namaOutlet = Auth::user()->partner->outlet->nama_outlet ?? 'outlet kamu';

        $parts = [];
        if ($countC === 0) {
            $parts[] = "Belum ada transaksi dalam 30 hari terakhir. Yuk mulai catat setiap penjualan di Flavory.id biar MineAI bisa kasih analisis yang akurat!";
        } else {
            $parts[] = "Hei Bos! 👋 Dalam 30 hari terakhir, **{$namaOutlet}** berhasil meraup omzet **" . $this->rp($omzetC) . "** dari **{$countC} transaksi**. ";
            if ($omzetP > 0) {
                $dir = $growthRate >= 0 ? 'naik' : 'turun';
                $parts[] = "Dibanding 30 hari sebelumnya (" . $this->rp($omzetP) . "), omzet {$dir} **" . number_format(abs($growthRate), 1) . "%** " . ($growthRate >= 0 ? '🚀' : '⚠️') . ". ";
            }
            if ($omzetC >= 50_000_000)      $parts[] = "Omzet di atas 50 juta — luar biasa Bos! 🎉 Ini udah masuk kategori outlet performa tinggi. ";
            elseif ($omzetC >= 10_000_000)  $parts[] = "Solid Bos! Omzet sudah melampaui 10 juta. ";
            elseif ($omzetC > 0)            $parts[] = "Omzet masih dalam tahap tumbuh, potensinya masih besar. ";
            if ($topMenu['qty'] > 0)        $parts[] = "Menu bintang: **\"{$topMenu['nama']}\"** ({$topMenu['qty']} porsi). ";
            if ($avgC > 0)                  $parts[] = "Rata-rata transaksi: **" . $this->rp($avgC) . "**. ";
            if ($discRat > 12)              $parts[] = "⚠️ Diskon **" . number_format($discRat, 1) . "%** — coba ganti ke bundling agar margin terjaga. ";
            if ($stokTipis->count() > 0)    $parts[] = "🔴 Stok tipis: **" . $stokTipis->take(3)->pluck('nama_barang')->implode(', ') . "**. Segera restock! ";
            if ($bestHour !== '-')          $parts[] = "Jam puncak: **{$bestHour}**. ";
            if ($bestDay  !== '-')          $parts[] = "Hari tersibuk: **{$bestDay}**. ";
            if ($top3Share >= 65)           $parts[] = "Top 3 menu = **" . number_format($top3Share, 1) . "%** penjualan — pertimbangkan diversifikasi. ";
            $parts[] = match($trend) {
                'naik'  => "Momentum bagus Bos — gas poll! 💪",
                'turun' => "Tren melambat, saatnya evaluasi strategi! 🛠️",
                default => "Bisnis stabil — waktunya inovasi kecil! ✨",
            };
        }

        $insightTeks     = implode('', $parts);
        $businessContext = [
            'periode' => '30 hari terakhir', 'omzet' => $omzetC, 'omzet_prev' => $omzetP,
            'growth_rate' => round($growthRate, 2), 'jumlah_order' => $countC,
            'avg_ticket' => round($avgC), 'diskon_persen' => round($discRat, 1),
            'top_menu' => $topMenu['nama'], 'top_menu_qty' => $topMenu['qty'],
            'jam_puncak' => $bestHour, 'hari_puncak' => $bestDay,
            'top3_share' => round($top3Share, 1),
            'stok_tipis' => $stokTipis->pluck('nama_barang')->toArray(),
            'trend' => $trend, 'nama_outlet' => $namaOutlet,
        ];

        return response()->json([
            'insight_awal'     => $insightTeks,
            'business_context' => $businessContext,
        ]);
    }

    // ─────────────────────────────────────────────
    //  CHAT WITH GROQ + simpan ke DB
    // ─────────────────────────────────────────────
    public function chatWithGroq(Request $request)
    {
        $request->validate([
            'message'          => 'required|string|max:2000',
            'chat_history'     => 'nullable|array',
            'business_context' => 'nullable|array',
            'session_uuid'     => 'nullable|string',
        ]);

        $partnerId       = Auth::user()->partner->id;
        $userMessage     = $request->input('message');
        $chatHistory     = $request->input('chat_history', []);
        $businessContext = $request->input('business_context', []);
        $sessionUuid     = $request->input('session_uuid');

        // ── Temukan atau buat sesi ──
        $session = null;
        if ($sessionUuid) {
            $session = MineAiSession::where('uuid', $sessionUuid)
                ->where('partner_id', $partnerId)
                ->first();
        }
        if (!$session) {
            // Buat judul otomatis dari 5 kata pertama pesan user
            $words = explode(' ', $userMessage);
            $title = implode(' ', array_slice($words, 0, 6));
            $title = mb_substr($title, 0, 80);

            $session = MineAiSession::create([
                'partner_id'       => $partnerId,
                'title'            => $title,
                'business_context' => $businessContext ?: null,
            ]);
        }

        // Simpan pesan user
        MineAiMessage::create([
            'mine_ai_session_id' => $session->id,
            'role'               => 'user',
            'content'            => $userMessage,
        ]);

        // Update timestamp sesi
        $session->touch();

        // ── System prompt ──
        $ctxText = '';
        if (!empty($businessContext)) {
            $ctxText  = "\n\n📊 DATA BISNIS REAL (30 hari terakhir):\n";
            $ctxText .= "• Outlet: " . ($businessContext['nama_outlet'] ?? '-') . "\n";
            $ctxText .= "• Omzet: Rp " . number_format($businessContext['omzet'] ?? 0, 0, ',', '.') . "\n";
            $ctxText .= "• Pertumbuhan: " . ($businessContext['growth_rate'] ?? 0) . "%\n";
            $ctxText .= "• Order: " . ($businessContext['jumlah_order'] ?? 0) . " transaksi\n";
            $ctxText .= "• Avg ticket: Rp " . number_format($businessContext['avg_ticket'] ?? 0, 0, ',', '.') . "\n";
            $ctxText .= "• Menu terlaris: " . ($businessContext['top_menu'] ?? '-') . "\n";
            $ctxText .= "• Jam puncak: " . ($businessContext['jam_puncak'] ?? '-') . "\n";
            $ctxText .= "• Tren: " . ($businessContext['trend'] ?? '-') . "\n";
            $stokTipis = $businessContext['stok_tipis'] ?? [];
            if (!empty($stokTipis)) $ctxText .= "• Stok tipis: " . implode(', ', $stokTipis) . "\n";
        }

        $systemPrompt = "Kamu adalah **MineAI** — asisten bisnis kuliner cerdas milik Flavory.id. "
            . "Panggil user 'Kak' atau 'Bos', bahasa kasual Indonesia, pakai emoji secukupnya, "
            . "berikan 1–2 aksi konkret di tiap jawaban bisnis. "
            . "Boleh ngobrol santai juga, tapi tetap hangat dan asik.{$ctxText}";

        $messages = [['role' => 'system', 'content' => $systemPrompt]];
        foreach (array_slice($chatHistory, -10) as $msg) {
            if (isset($msg['role'], $msg['content']) && in_array($msg['role'], ['user','assistant'])) {
                $messages[] = ['role' => $msg['role'], 'content' => (string) $msg['content']];
            }
        }
        $messages[] = ['role' => 'user', 'content' => $userMessage];

        try {
            $response = Http::withToken(env('GROQ_API_KEY'))
                ->timeout(30)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model'       => env('GROQ_DEFAULT_MODEL', 'llama-3.3-70b-versatile'),
                    'messages'    => $messages,
                    'max_tokens'  => 1024,
                    'temperature' => 0.75,
                ]);

            $reply = $response->successful()
                ? ($response->json()['choices'][0]['message']['content'] ?? 'Hmm, gak ada respon dari server.')
                : 'Aduh Bos, MineAI lagi gangguan. Coba lagi bentar! 🙏';

        } catch (\Exception $e) {
            $reply = 'Bos, koneksi ke AI bermasalah. Coba refresh! 🔄';
        }

        // Simpan reply AI ke DB
        MineAiMessage::create([
            'mine_ai_session_id' => $session->id,
            'role'               => 'assistant',
            'content'            => $reply,
        ]);

        return response()->json([
            'reply'        => $reply,
            'session_uuid' => $session->uuid,
            'session_title'=> $session->title,
        ]);
    }

    private function rp(float $val): string
    {
        return 'Rp ' . number_format($val, 0, ',', '.');
    }
}
