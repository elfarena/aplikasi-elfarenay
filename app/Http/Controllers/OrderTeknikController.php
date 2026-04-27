<?php

namespace App\Http\Controllers;

use App\Models\OrderTeknik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;
use Dompdf\Dompdf;
use Dompdf\Options;

class OrderTeknikController extends Controller
{
    public function index(Request $request): View
    {
        $menu              = $request->query('menu', 'semua');
        $q                 = trim((string) $request->query('q', ''));
        $tgl_masuk_dari    = trim((string) $request->query('tgl_masuk_dari', ''));
        $tgl_masuk_sampai  = trim((string) $request->query('tgl_masuk_sampai', ''));
        $tgl_realisasi_dari   = trim((string) $request->query('tgl_realisasi_dari', ''));
        $tgl_realisasi_sampai = trim((string) $request->query('tgl_realisasi_sampai', ''));

        $query = OrderTeknik::query()
            ->filterMenu($menu)
            ->filterSearch($q)
            ->filterTanggalMasuk($tgl_masuk_dari, $tgl_masuk_sampai)
            ->filterTanggalRealisasi($tgl_realisasi_dari, $tgl_realisasi_sampai);

        // Hitung statistik sebelum pagination
        $totalData    = $query->clone()->count();
        $belumSelesai = $query->clone()->whereIn('status_realisasi', ['masuk', 'proses'])->count();
        $sudahSelesai = $query->clone()->where('status_realisasi', 'selesai')->count();

        $rows = $query->latest()->paginate(25)->withQueryString();

        return view('welcome', compact(
            'rows', 'menu', 'q',
            'tgl_masuk_dari', 'tgl_masuk_sampai',
            'tgl_realisasi_dari', 'tgl_realisasi_sampai',
            'totalData', 'belumSelesai', 'sudahSelesai'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pelanggan'   => ['nullable', 'string', 'max:255'],
            'pelapor'          => ['nullable', 'string', 'max:255'],
            'alamat'           => ['nullable', 'string', 'max:255'],
            'tanggal'          => ['nullable', 'date'],
            'tanggal_masuk'    => ['nullable', 'date'],
            'kelurahan'        => ['nullable', 'string', 'max:255'],
            'kecamatan'        => ['nullable', 'string', 'max:255'],
            'zona'             => ['nullable', 'string', 'max:20'],
            'kode_order'       => ['nullable', 'string', 'max:20'],
            'realisasi_order'  => ['nullable', 'string', 'max:255'],
            'perbaikan'        => ['nullable', 'string', 'max:255'],
            'diameter'         => ['nullable', 'string', 'max:20'],
            'keterangan'       => ['nullable', 'string', 'max:255'],
            'stan_meter'       => ['nullable', 'string', 'max:30'],
            'tanggal_realisasi'=> ['nullable', 'date'],
            'pelaksana'        => ['nullable', 'string', 'max:50'],
            'status_realisasi' => ['nullable', 'in:masuk,proses,selesai'],
            'sumber'           => ['nullable', 'string', 'max:50'],
            'no_pelanggan'     => ['nullable', 'string', 'max:30'],
        ]);

        if (empty($validated['status_realisasi'])) {
            $validated['status_realisasi'] = 'masuk';
        }

        if (empty($validated['tanggal'])) {
            $validated['tanggal'] = now()->toDateString();
        }

        if (empty($validated['tanggal_masuk'])) {
            $validated['tanggal_masuk'] = now()->toDateString();
        }

        $validated['kelurahan']       = $validated['kelurahan'] ?? '-';
        $validated['kecamatan']       = $validated['kecamatan'] ?? '-';
        $validated['zona']            = $validated['zona'] ?? '-';
        $validated['realisasi_order'] = $validated['realisasi_order'] ?? '-';
        $validated['perbaikan']       = $validated['perbaikan'] ?? '-';
        $validated['diameter']        = $validated['diameter'] ?? '-';

        OrderTeknik::create($validated);

        return redirect('/')->with('success', 'Baris data berhasil disimpan.');
    }

    public function edit(OrderTeknik $orderTeknik): View
    {
        return view('edit', compact('orderTeknik'));
    }

    public function update(Request $request, OrderTeknik $orderTeknik): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pelanggan'   => ['nullable', 'string', 'max:255'],
            'pelapor'          => ['nullable', 'string', 'max:255'],
            'alamat'           => ['nullable', 'string', 'max:255'],
            'tanggal'          => ['nullable', 'date'],
            'tanggal_masuk'    => ['nullable', 'date'],
            'kelurahan'        => ['nullable', 'string', 'max:255'],
            'kecamatan'        => ['nullable', 'string', 'max:255'],
            'zona'             => ['nullable', 'string', 'max:20'],
            'kode_order'       => ['nullable', 'string', 'max:20'],
            'realisasi_order'  => ['nullable', 'string', 'max:255'],
            'perbaikan'        => ['nullable', 'string', 'max:255'],
            'diameter'         => ['nullable', 'string', 'max:20'],
            'keterangan'       => ['nullable', 'string', 'max:255'],
            'stan_meter'       => ['nullable', 'string', 'max:30'],
            'tanggal_realisasi'=> ['nullable', 'date'],
            'pelaksana'        => ['nullable', 'string', 'max:50'],
            'status_realisasi' => ['nullable', 'in:masuk,proses,selesai'],
            'sumber'           => ['nullable', 'string', 'max:50'],
            'no_pelanggan'     => ['nullable', 'string', 'max:30'],
        ]);

        if (empty($validated['status_realisasi'])) {
            $validated['status_realisasi'] = 'masuk';
        }

        if (empty($validated['tanggal'])) {
            $validated['tanggal'] = now()->toDateString();
        }

        if (empty($validated['tanggal_masuk'])) {
            $validated['tanggal_masuk'] = now()->toDateString();
        }

        $validated['kelurahan']       = $validated['kelurahan'] ?? $orderTeknik->kelurahan ?? '-';
        $validated['kecamatan']       = $validated['kecamatan'] ?? $orderTeknik->kecamatan ?? '-';
        $validated['zona']            = $validated['zona'] ?? $orderTeknik->zona ?? '-';
        $validated['realisasi_order'] = $validated['realisasi_order'] ?? $orderTeknik->realisasi_order ?? '-';
        $validated['perbaikan']       = $validated['perbaikan'] ?? $orderTeknik->perbaikan ?? '-';
        $validated['diameter']        = $validated['diameter'] ?? $orderTeknik->diameter ?? '-';

        $orderTeknik->update($validated);

        return redirect('/')->with('success', 'Data order berhasil diupdate.');
    }

    public function destroy(OrderTeknik $orderTeknik): RedirectResponse
    {
        $orderTeknik->delete();

        return redirect('/')->with('success', 'Data order berhasil dihapus.');
    }

    // =========================================================
    // Report & Export — filter via Query Scopes
    // =========================================================

    private function buildReportQuery(Request $request)
    {
        $dari       = trim((string) $request->query('dari', ''));
        $sampai     = trim((string) $request->query('sampai', ''));
        $tanggal    = trim((string) $request->query('tanggal', ''));
        $kode_order = trim((string) $request->query('kode_order', ''));
        $status     = trim((string) $request->query('status', ''));

        return [
            'dari'       => $dari,
            'sampai'     => $sampai,
            'tanggal'    => $tanggal,
            'kode_order' => $kode_order,
            'status'     => $status,
            'query'      => OrderTeknik::query()
                ->filterStatus($status)
                ->filterTanggalRealisasi($dari, $sampai)
                ->filterTanggal($tanggal)
                ->filterKodeOrder($kode_order),
        ];
    }

    private function buildReportStats($query): array
    {
        $statsByStatus = $query->clone()
            ->selectRaw('status_realisasi, COUNT(*) as total')
            ->groupBy('status_realisasi')
            ->pluck('total', 'status_realisasi')
            ->toArray();

        $statsBySource = $query->clone()
            ->selectRaw('sumber, COUNT(*) as total')
            ->groupBy('sumber')
            ->pluck('total', 'sumber')
            ->toArray();

        $statsByPelaksana = $query->clone()
            ->selectRaw('pelaksana, COUNT(*) as total')
            ->whereNotNull('pelaksana')
            ->where('pelaksana', '!=', '')
            ->groupBy('pelaksana')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('total', 'pelaksana')
            ->toArray();

        $totalOrders     = $query->count();
        $completedOrders = $query->clone()->where('status_realisasi', 'selesai')->count();
        $pendingOrders   = $query->clone()->whereIn('status_realisasi', ['masuk', 'proses'])->count();

        return compact(
            'statsByStatus', 'statsBySource', 'statsByPelaksana',
            'totalOrders', 'completedOrders', 'pendingOrders'
        );
    }

    public function report(Request $request): View
    {
        $ctx   = $this->buildReportQuery($request);
        $query = $ctx['query'];
        $stats = $this->buildReportStats($query);

        // Monthly statistics (last 6 months)
        $monthlyStats = $query->clone()
            ->selectRaw("DATE_FORMAT(tanggal_realisasi, '%Y-%m') as month, COUNT(*) as total")
            ->whereNotNull('tanggal_realisasi')
            ->where('tanggal_realisasi', '>=', now()->subMonths(6)->toDateString())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $orders = $query->orderBy('id', 'desc')->get();

        return view('report', array_merge($stats, [
            'monthlyStats' => $monthlyStats,
            'orders'       => $orders,
            'dari'         => $ctx['dari'],
            'sampai'       => $ctx['sampai'],
            'tanggal'      => $ctx['tanggal'],
            'kode_order'   => $ctx['kode_order'],
            'status'       => $ctx['status'],
        ]));
    }

    public function exportPdf(Request $request): Response
    {
        $ctx    = $this->buildReportQuery($request);
        $query  = $ctx['query'];
        $stats  = $this->buildReportStats($query);
        $orders = $query->orderBy('id', 'desc')->get();

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $html   = view('exports.pdf', array_merge($stats, [
            'orders' => $orders,
            'dari'   => $ctx['dari'],
            'sampai' => $ctx['sampai'],
        ]))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'laporan_order_' . date('Y-m-d_His') . '.pdf';

        return response($dompdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $ctx   = $this->buildReportQuery($request);
        $query = $ctx['query'];
        $stats = $this->buildReportStats($query);

        $headers = [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="laporan_statistik_' . date('Y-m-d_His') . '.csv"',
        ];

        $callback = function () use ($stats, $ctx) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['LAPORAN STATISTIK ORDER TEKNIK']);
            fputcsv($file, ['PT. PDAM Keluhan - Penanggulangan Kebocoran']);
            if ($ctx['dari'] || $ctx['sampai']) {
                fputcsv($file, ['Periode:', $ctx['dari'] ?: '-', 's/d', $ctx['sampai'] ?: '-']);
            }
            fputcsv($file, []);

            fputcsv($file, ['RINGKASAN']);
            fputcsv($file, ['Total Order', $stats['totalOrders']]);
            fputcsv($file, ['Selesai', $stats['completedOrders']]);
            fputcsv($file, ['Belum Selesai', $stats['pendingOrders']]);
            fputcsv($file, []);

            fputcsv($file, ['STATISTIK BERDASARKAN STATUS']);
            fputcsv($file, ['Status', 'Jumlah', 'Persentase']);
            foreach ($stats['statsByStatus'] as $status => $total) {
                $pct = $stats['totalOrders'] > 0 ? round(($total / $stats['totalOrders']) * 100, 1) . '%' : '0%';
                fputcsv($file, [ucfirst($status), $total, $pct]);
            }
            fputcsv($file, []);

            fputcsv($file, ['STATISTIK BERDASARKAN SUMBER']);
            fputcsv($file, ['Sumber', 'Jumlah', 'Persentase']);
            foreach ($stats['statsBySource'] as $sumber => $total) {
                $pct = $stats['totalOrders'] > 0 ? round(($total / $stats['totalOrders']) * 100, 1) . '%' : '0%';
                fputcsv($file, [$sumber, $total, $pct]);
            }
            fputcsv($file, []);

            if (count($stats['statsByPelaksana']) > 0) {
                fputcsv($file, ['TOP 10 PELAKSANA']);
                fputcsv($file, ['No', 'Nama Pelaksana', 'Jumlah Order']);
                $no = 1;
                foreach ($stats['statsByPelaksana'] as $pelaksana => $total) {
                    fputcsv($file, [$no++, $pelaksana, $total]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportWord(Request $request): Response
    {
        $ctx    = $this->buildReportQuery($request);
        $query  = $ctx['query'];
        $stats  = $this->buildReportStats($query);
        $orders = $query->orderBy('id', 'desc')->get();

        $html = view('exports.word', array_merge($stats, [
            'orders' => $orders,
            'dari'   => $ctx['dari'],
            'sampai' => $ctx['sampai'],
        ]))->render();

        $filename = 'laporan_order_' . date('Y-m-d_His') . '.doc';

        return response($html, 200, [
            'Content-Type'        => 'application/msword',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // =========================================================
    // Auth
    // =========================================================

    public function showLogin(): View
    {
        return view('login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'Selamat datang, ' . Auth::user()->name);
        }

        return back()->with('error', 'Email atau password salah');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout');
    }
}
