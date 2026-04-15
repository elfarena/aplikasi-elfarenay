<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Order Teknik</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #0f3f9a;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #0f3f9a;
            font-size: 16pt;
            margin: 0 0 5px 0;
        }
        .header p {
            color: #64748b;
            font-size: 9pt;
            margin: 0;
        }
        .filter-info {
            background: #f8fafd;
            padding: 8px 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 9pt;
        }
        .summary-cards {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .summary-card {
            flex: 1;
            text-align: center;
            padding: 10px;
            border-radius: 4px;
            background: #f8fafd;
            border: 1px solid #e2e8f0;
        }
        .summary-card .number {
            font-size: 18pt;
            font-weight: bold;
            color: #0f3f9a;
        }
        .summary-card .label {
            font-size: 8pt;
            color: #64748b;
        }
        h2 {
            color: #0f3f9a;
            font-size: 12pt;
            margin: 20px 0 10px 0;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9pt;
        }
        th, td {
            border: 1px solid #e2e8f0;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background: #f8fafd;
            font-weight: bold;
            color: #0f3f9a;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
        }
        .badge-selesai {
            background: #dff8ea;
            color: #0d7d4f;
        }
        .badge-belum {
            background: #fff4da;
            color: #9b6205;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8pt;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN ORDER TEKNIK</h1>
        <p>PT. PDAM Keluhan - Penanggulangan Kebocoran</p>
    </div>

    @if($dari || $sampai)
    <div class="filter-info">
        <strong>Filter Periode:</strong>
        {{ $dari ? 'Dari: ' . $dari : '' }}
        {{ $sampai ? 'Sampai: ' . $sampai : '' }}
    </div>
    @endif

    <div class="summary-cards">
        <div class="summary-card">
            <div class="number">{{ $totalOrders }}</div>
            <div class="label">Total Order</div>
        </div>
        <div class="summary-card">
            <div class="number">{{ $completedOrders }}</div>
            <div class="label">Selesai</div>
        </div>
        <div class="summary-card">
            <div class="number">{{ $pendingOrders }}</div>
            <div class="label">Belum Selesai</div>
        </div>
    </div>

    <h2>Statistik Berdasarkan Status</h2>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach($statsByStatus as $status => $total)
            <tr>
                <td>{{ $status === 'proses' ? 'Belum Selesai' : ucfirst($status) }}</td>
                <td class="text-center">{{ $total }}</td>
                <td class="text-center">{{ $totalOrders > 0 ? round(($total / $totalOrders) * 100, 1) : 0 }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Statistik Berdasarkan Sumber</h2>
    <table>
        <thead>
            <tr>
                <th>Sumber</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach($statsBySource as $sumber => $total)
            <tr>
                <td>{{ $sumber }}</td>
                <td class="text-center">{{ $total }}</td>
                <td class="text-center">{{ $totalOrders > 0 ? round(($total / $totalOrders) * 100, 1) : 0 }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if(count($statsByPelaksana) > 0)
    <h2>Top 10 Pelaksana</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelaksana</th>
                <th class="text-center">Jumlah Order</th>
            </tr>
        </thead>
        <tbody>
            @foreach($statsByPelaksana as $pelaksana => $total)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $pelaksana }}</td>
                <td class="text-center">{{ $total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        Dicetak pada {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>
