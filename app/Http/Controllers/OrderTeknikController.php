<?php

namespace App\Http\Controllers;

use App\Models\OrderTeknik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;
use Dompdf\Dompdf;
use Dompdf\Options;

class OrderTeknikController extends Controller
{
    public function index(Request $request): View
    {
        $menu = $request->query('menu', 'semua');
        $q = trim((string) $request->query('q', ''));
        
        // Filter Tanggal Masuk
        $tgl_masuk_dari = trim((string) $request->query('tgl_masuk_dari', ''));
        $tgl_masuk_sampai = trim((string) $request->query('tgl_masuk_sampai', ''));
        
        // Filter Tanggal Realisasi
        $tgl_realisasi_dari = trim((string) $request->query('tgl_realisasi_dari', ''));
        $tgl_realisasi_sampai = trim((string) $request->query('tgl_realisasi_sampai', ''));

        $query = OrderTeknik::query();

        if ($menu === 'sudah') {
            $query->where('status_realisasi', 'selesai');
        } elseif ($menu === 'belum') {
            $query->whereIn('status_realisasi', ['masuk', 'proses']);
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('no_pelanggan', 'like', "%{$q}%")
                    ->orWhere('pelapor', 'like', "%{$q}%")
                    ->orWhere('alamat', 'like', "%{$q}%")
                    ->orWhere('kode_order', 'like', "%{$q}%")
                    ->orWhere('keterangan', 'like', "%{$q}%")
                    ->orWhere('stan_meter', 'like', "%{$q}%")
                    ->orWhere('tanggal_realisasi', 'like', "%{$q}%")
                    ->orWhere('pelaksana', 'like', "%{$q}%")
                    ->orWhere('status_realisasi', 'like', "%{$q}%")
                    ->orWhere('sumber', 'like', "%{$q}%");
            });
        }

        // Filter Tanggal Masuk
        if ($tgl_masuk_dari !== '' && preg_match('/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/', $tgl_masuk_dari)) {
            $query->whereRaw(
                "STR_TO_DATE(tanggal_masuk, '%d/%m/%Y') >= STR_TO_DATE(?, '%d/%m/%Y')",
                [$tgl_masuk_dari]
            );
        }

        if ($tgl_masuk_sampai !== '' && preg_match('/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/', $tgl_masuk_sampai)) {
            $query->whereRaw(
                "STR_TO_DATE(tanggal_masuk, '%d/%m/%Y') <= STR_TO_DATE(?, '%d/%m/%Y')",
                [$tgl_masuk_sampai]
            );
        }

        // Filter Tanggal Realisasi
        if ($tgl_realisasi_dari !== '' && preg_match('/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/', $tgl_realisasi_dari)) {
            $query->whereRaw(
                "STR_TO_DATE(tanggal_realisasi, '%d/%m/%Y') >= STR_TO_DATE(?, '%d/%m/%Y')",
                [$tgl_realisasi_dari]
            );
        }

        if ($tgl_realisasi_sampai !== '' && preg_match('/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/', $tgl_realisasi_sampai)) {
            $query->whereRaw(
                "STR_TO_DATE(tanggal_realisasi, '%d/%m/%Y') <= STR_TO_DATE(?, '%d/%m/%Y')",
                [$tgl_realisasi_sampai]
            );
        }

        $rows = $query
            ->latest()
            ->limit(50)
            ->get();

        return view('welcome', compact('rows', 'menu', 'q', 'tgl_masuk_dari', 'tgl_masuk_sampai', 'tgl_realisasi_dari', 'tgl_realisasi_sampai'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pelanggan' => ['nullable', 'string', 'max:255'],
            'pelapor' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'tanggal' => ['nullable', 'regex:/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/'],
            'tanggal_masuk' => ['nullable', 'regex:/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/'],
            'kelurahan' => ['nullable', 'string', 'max:255'],
            'kecamatan' => ['nullable', 'string', 'max:255'],
            'zona' => ['nullable', 'string', 'max:20'],
            'kode_order' => ['nullable', 'string', 'max:20'],
            'realisasi_order' => ['nullable', 'string', 'max:255'],
            'perbaikan' => ['nullable', 'string', 'max:255'],
            'diameter' => ['nullable', 'string', 'max:20'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'stan_meter' => ['nullable', 'string', 'max:30'],
            'tanggal_realisasi' => ['nullable', 'regex:/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/'],
            'pelaksana' => ['nullable', 'string', 'max:50'],
            'status_realisasi' => ['nullable', 'in:masuk,proses,selesai'],
            'sumber' => ['nullable', 'string', 'max:50'],
            'no_pelanggan' => ['nullable', 'string', 'max:30'],
        ]);

        if (empty($validated['status_realisasi'])) {
            $validated['status_realisasi'] = 'masuk';
        }

        if (empty($validated['tanggal'])) {
            $validated['tanggal'] = now()->format('d/m/Y');
        }

        if (empty($validated['tanggal_masuk'])) {
            $validated['tanggal_masuk'] = now()->format('d/m/Y');
        }

        // Default values for fields not in form
        $validated['kelurahan'] = $validated['kelurahan'] ?? '-';
        $validated['kecamatan'] = $validated['kecamatan'] ?? '-';
        $validated['zona'] = $validated['zona'] ?? '-';
        $validated['realisasi_order'] = $validated['realisasi_order'] ?? '-';
        $validated['perbaikan'] = $validated['perbaikan'] ?? '-';
        $validated['diameter'] = $validated['diameter'] ?? '-';

        OrderTeknik::create($validated);

        return redirect('/')
            ->with('success', 'Baris data berhasil disimpan.');
    }

    public function edit(OrderTeknik $orderTeknik): View
    {
        return view('edit', compact('orderTeknik'));
    }

    public function update(Request $request, OrderTeknik $orderTeknik): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pelanggan' => ['nullable', 'string', 'max:255'],
            'pelapor' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'tanggal' => ['nullable', 'regex:/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/'],
            'tanggal_masuk' => ['nullable', 'regex:/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/'],
            'kelurahan' => ['nullable', 'string', 'max:255'],
            'kecamatan' => ['nullable', 'string', 'max:255'],
            'zona' => ['nullable', 'string', 'max:20'],
            'kode_order' => ['nullable', 'string', 'max:20'],
            'realisasi_order' => ['nullable', 'string', 'max:255'],
            'perbaikan' => ['nullable', 'string', 'max:255'],
            'diameter' => ['nullable', 'string', 'max:20'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'stan_meter' => ['nullable', 'string', 'max:30'],
            'tanggal_realisasi' => ['nullable', 'regex:/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/'],
            'pelaksana' => ['nullable', 'string', 'max:50'],
            'status_realisasi' => ['nullable', 'in:masuk,proses,selesai'],
            'sumber' => ['nullable', 'string', 'max:50'],
            'no_pelanggan' => ['nullable', 'string', 'max:30'],
        ]);

        // Default values for fields not in form
        if (empty($validated['status_realisasi'])) {
            $validated['status_realisasi'] = 'masuk';
        }

        if (empty($validated['tanggal'])) {
            $validated['tanggal'] = now()->format('d/m/Y');
        }

        if (empty($validated['tanggal_masuk'])) {
            $validated['tanggal_masuk'] = now()->format('d/m/Y');
        }

        $validated['kelurahan'] = $validated['kelurahan'] ?? $orderTeknik->kelurahan ?? '-';
        $validated['kecamatan'] = $validated['kecamatan'] ?? $orderTeknik->kecamatan ?? '-';
        $validated['zona'] = $validated['zona'] ?? $orderTeknik->zona ?? '-';
        $validated['realisasi_order'] = $validated['realisasi_order'] ?? $orderTeknik->realisasi_order ?? '-';
        $validated['perbaikan'] = $validated['perbaikan'] ?? $orderTeknik->perbaikan ?? '-';
        $validated['diameter'] = $validated['diameter'] ?? $orderTeknik->diameter ?? '-';

        $orderTeknik->update($validated);

        return redirect('/')
            ->with('success', 'Data order berhasil diupdate.');
    }

    public function report(Request $request): View
    {
        $dari = trim((string) $request->query('dari', ''));
        $sampai = trim((string) $request->query('sampai', ''));
        $tanggal = trim((string) $request->query('tanggal', ''));
        $kode_order = trim((string) $request->query('kode_order', ''));
        $status = trim((string) $request->query('status', ''));

        $query = OrderTeknik::query();

        // Filter by status if provided
        if ($status !== '' && in_array($status, ['proses', 'selesai'])) {
            $query->where('status_realisasi', $status);
        }

        // Filter by date range if provided
        if ($dari !== '' && preg_match('/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/', $dari)) {
            $query->whereRaw(
                "STR_TO_DATE(tanggal_realisasi, '%d/%m/%Y') >= STR_TO_DATE(?, '%d/%m/%Y')",
                [$dari]
            );
        }

        if ($sampai !== '' && preg_match('/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/', $sampai)) {
            $query->whereRaw(
                "STR_TO_DATE(tanggal_realisasi, '%d/%m/%Y') <= STR_TO_DATE(?, '%d/%m/%Y')",
                [$sampai]
            );
        }

        // Filter by specific date (tanggal masuk or tanggal realisasi)
        if ($tanggal !== '' && preg_match('/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/', $tanggal)) {
            $query->where(function($q) use ($tanggal) {
                $q->where('tanggal_masuk', $tanggal)
                  ->orWhereRaw(
                      "STR_TO_DATE(tanggal_realisasi, '%d/%m/%Y') = STR_TO_DATE(?, '%d/%m/%Y')",
                      [$tanggal]
                  );
            });
        }

        // Filter by kode order
        if ($kode_order !== '') {
            $query->where('kode_order', 'LIKE', '%' . $kode_order . '%');
        }

        // Statistics by status
        $statsByStatus = $query->clone()
            ->selectRaw('status_realisasi, COUNT(*) as total')
            ->groupBy('status_realisasi')
            ->pluck('total', 'status_realisasi')
            ->toArray();

        // Statistics by source
        $statsBySource = $query->clone()
            ->selectRaw('sumber, COUNT(*) as total')
            ->groupBy('sumber')
            ->pluck('total', 'sumber')
            ->toArray();

        // Statistics by pelaksana
        $statsByPelaksana = $query->clone()
            ->selectRaw('pelaksana, COUNT(*) as total')
            ->whereNotNull('pelaksana')
            ->where('pelaksana', '!=', '')
            ->groupBy('pelaksana')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('total', 'pelaksana')
            ->toArray();

        // Total counts
        $totalOrders = $query->count();
        $completedOrders = $query->clone()->where('status_realisasi', 'selesai')->count();
        $pendingOrders = $query->clone()->whereIn('status_realisasi', ['masuk', 'proses'])->count();

        // Monthly statistics (last 6 months)
        $monthlyStats = $query->clone()
            ->selectRaw("DATE_FORMAT(STR_TO_DATE(tanggal_realisasi, '%d/%m/%Y'), '%Y-%m') as month, COUNT(*) as total")
            ->whereRaw("STR_TO_DATE(tanggal_realisasi, '%d/%m/%Y') >= DATE_SUB(NOW(), INTERVAL 6 MONTH)")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Get orders for display
        $orders = $query->orderBy('id', 'desc')->get();

        return view('report', compact(
            'statsByStatus',
            'statsBySource',
            'statsByPelaksana',
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'monthlyStats',
            'orders',
            'dari',
            'sampai',
            'tanggal',
            'kode_order',
            'status'
        ));
    }

    public function exportPdf(Request $request): Response
    {
        $dari = trim((string) $request->query('dari', ''));
        $sampai = trim((string) $request->query('sampai', ''));
        $tanggal = trim((string) $request->query('tanggal', ''));
        $kode_order = trim((string) $request->query('kode_order', ''));
        $status = trim((string) $request->query('status', ''));

        $query = OrderTeknik::query();

        // Filter by status if provided
        if ($status !== '' && in_array($status, ['proses', 'selesai'])) {
            $query->where('status_realisasi', $status);
        }

        if ($dari !== '' && preg_match('/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/', $dari)) {
            $query->whereRaw(
                "STR_TO_DATE(tanggal_realisasi, '%d/%m/%Y') >= STR_TO_DATE(?, '%d/%m/%Y')",
                [$dari]
            );
        }

        if ($sampai !== '' && preg_match('/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/', $sampai)) {
            $query->whereRaw(
                "STR_TO_DATE(tanggal_realisasi, '%d/%m/%Y') <= STR_TO_DATE(?, '%d/%m/%Y')",
                [$sampai]
            );
        }

        if ($tanggal !== '' && preg_match('/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/', $tanggal)) {
            $query->where(function($q) use ($tanggal) {
                $q->where('tanggal_masuk', $tanggal)
                  ->orWhereRaw(
                      "STR_TO_DATE(tanggal_realisasi, '%d/%m/%Y') = STR_TO_DATE(?, '%d/%m/%Y')",
                      [$tanggal]
                  );
            });
        }

        if ($kode_order !== '') {
            $query->where('kode_order', 'LIKE', '%' . $kode_order . '%');
        }

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

        $totalOrders = $query->count();
        $completedOrders = $query->clone()->where('status_realisasi', 'selesai')->count();
        $pendingOrders = $query->clone()->whereIn('status_realisasi', ['masuk', 'proses'])->count();

        $orders = $query->orderBy('id', 'desc')->get();

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        $html = view('exports.pdf', compact(
            'statsByStatus',
            'statsBySource',
            'statsByPelaksana',
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'orders',
            'dari',
            'sampai'
        ))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'laporan_order_' . date('Y-m-d_His') . '.pdf';

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $dari = trim((string) $request->query('dari', ''));
        $sampai = trim((string) $request->query('sampai', ''));
        $tanggal = trim((string) $request->query('tanggal', ''));
        $kode_order = trim((string) $request->query('kode_order', ''));
        $status = trim((string) $request->query('status', ''));

        $query = OrderTeknik::query();

        // Filter by status if provided
        if ($status !== '' && in_array($status, ['proses', 'selesai'])) {
            $query->where('status_realisasi', $status);
        }

        if ($dari !== '' && preg_match('/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/', $dari)) {
            $query->whereRaw(
                "STR_TO_DATE(tanggal_realisasi, '%d/%m/%Y') >= STR_TO_DATE(?, '%d/%m/%Y')",
                [$dari]
            );
        }

        if ($sampai !== '' && preg_match('/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/', $sampai)) {
            $query->whereRaw(
                "STR_TO_DATE(tanggal_realisasi, '%d/%m/%Y') <= STR_TO_DATE(?, '%d/%m/%Y')",
                [$sampai]
            );
        }

        if ($tanggal !== '' && preg_match('/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/', $tanggal)) {
            $query->where(function($q) use ($tanggal) {
                $q->where('tanggal_masuk', $tanggal)
                  ->orWhereRaw(
                      "STR_TO_DATE(tanggal_realisasi, '%d/%m/%Y') = STR_TO_DATE(?, '%d/%m/%Y')",
                      [$tanggal]
                  );
            });
        }

        if ($kode_order !== '') {
            $query->where('kode_order', 'LIKE', '%' . $kode_order . '%');
        }

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

        $totalOrders = $query->count();
        $completedOrders = $query->clone()->where('status_realisasi', 'selesai')->count();
        $pendingOrders = $query->clone()->whereIn('status_realisasi', ['masuk', 'proses'])->count();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="laporan_statistik_' . date('Y-m-d_His') . '.csv"',
        ];

        $callback = function () use ($statsByStatus, $statsBySource, $statsByPelaksana, $totalOrders, $completedOrders, $pendingOrders, $dari, $sampai) {
            $file = fopen('php://output', 'w');
            
            // Header Info
            fputcsv($file, ['LAPORAN STATISTIK ORDER TEKNIK']);
            fputcsv($file, ['PT. PDAM Keluhan - Penanggulangan Kebocoran']);
            if ($dari || $sampai) {
                fputcsv($file, ['Periode:', $dari ? $dari : '-', 's/d', $sampai ? $sampai : '-']);
            }
            fputcsv($file, []);
            
            // Ringkasan
            fputcsv($file, ['RINGKASAN']);
            fputcsv($file, ['Total Order', $totalOrders]);
            fputcsv($file, ['Selesai', $completedOrders]);
            fputcsv($file, ['Belum Selesai', $pendingOrders]);
            fputcsv($file, []);
            
            // Statistik Status
            fputcsv($file, ['STATISTIK BERDASARKAN STATUS']);
            fputcsv($file, ['Status', 'Jumlah', 'Persentase']);
            foreach ($statsByStatus as $status => $total) {
                fputcsv($file, [ucfirst($status), $total, $totalOrders > 0 ? round(($total / $totalOrders) * 100, 1) . '%' : '0%']);
            }
            fputcsv($file, []);
            
            // Statistik Sumber
            fputcsv($file, ['STATISTIK BERDASARKAN SUMBER']);
            fputcsv($file, ['Sumber', 'Jumlah', 'Persentase']);
            foreach ($statsBySource as $sumber => $total) {
                fputcsv($file, [$sumber, $total, $totalOrders > 0 ? round(($total / $totalOrders) * 100, 1) . '%' : '0%']);
            }
            fputcsv($file, []);
            
            // Top Pelaksana
            if (count($statsByPelaksana) > 0) {
                fputcsv($file, ['TOP 10 PELAKSANA']);
                fputcsv($file, ['No', 'Nama Pelaksana', 'Jumlah Order']);
                $no = 1;
                foreach ($statsByPelaksana as $pelaksana => $total) {
                    fputcsv($file, [$no++, $pelaksana, $total]);
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportWord(Request $request): Response
    {
        $dari = trim((string) $request->query('dari', ''));
        $sampai = trim((string) $request->query('sampai', ''));
        $tanggal = trim((string) $request->query('tanggal', ''));
        $kode_order = trim((string) $request->query('kode_order', ''));
        $status = trim((string) $request->query('status', ''));

        $query = OrderTeknik::query();

        // Filter by status if provided
        if ($status !== '' && in_array($status, ['proses', 'selesai'])) {
            $query->where('status_realisasi', $status);
        }

        if ($dari !== '' && preg_match('/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/', $dari)) {
            $query->whereRaw(
                "STR_TO_DATE(tanggal_realisasi, '%d/%m/%Y') >= STR_TO_DATE(?, '%d/%m/%Y')",
                [$dari]
            );
        }

        if ($sampai !== '' && preg_match('/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/', $sampai)) {
            $query->whereRaw(
                "STR_TO_DATE(tanggal_realisasi, '%d/%m/%Y') <= STR_TO_DATE(?, '%d/%m/%Y')",
                [$sampai]
            );
        }

        if ($tanggal !== '' && preg_match('/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/', $tanggal)) {
            $query->where(function($q) use ($tanggal) {
                $q->where('tanggal_masuk', $tanggal)
                  ->orWhereRaw(
                      "STR_TO_DATE(tanggal_realisasi, '%d/%m/%Y') = STR_TO_DATE(?, '%d/%m/%Y')",
                      [$tanggal]
                  );
            });
        }

        if ($kode_order !== '') {
            $query->where('kode_order', 'LIKE', '%' . $kode_order . '%');
        }

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

        $totalOrders = $query->count();
        $completedOrders = $query->clone()->where('status_realisasi', 'selesai')->count();
        $pendingOrders = $query->clone()->whereIn('status_realisasi', ['masuk', 'proses'])->count();

        $orders = $query->orderBy('id', 'desc')->get();

        $html = view('exports.word', compact(
            'statsByStatus',
            'statsBySource',
            'statsByPelaksana',
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'orders',
            'dari',
            'sampai'
        ))->render();

        $filename = 'laporan_order_' . date('Y-m-d_His') . '.doc';

        return response($html, 200, [
            'Content-Type' => 'application/msword',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // Login methods
    public function showLogin(): View
    {
        return view('login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Hardcoded credentials for simplicity (username: admin, password: admin123)
        if ($credentials['username'] === 'admin' && $credentials['password'] === 'admin123') {
            session(['authenticated' => true, 'username' => $credentials['username']]);
            return redirect('/')->with('success', 'Selamat datang, ' . $credentials['username']);
        }

        return back()->with('error', 'Username atau password salah');
    }

    public function logout(): RedirectResponse
    {
        session()->forget(['authenticated', 'username']);
        return redirect('/login')->with('success', 'Berhasil logout');
    }
}

