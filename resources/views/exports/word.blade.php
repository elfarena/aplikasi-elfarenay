<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta charset="utf-8">
    <title>Laporan Order Teknik</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 0 0 10px 0;
        }
        .header p {
            font-size: 11pt;
            margin: 0;
        }
        .summary {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #000;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 5px 10px;
            text-align: center;
        }
        .summary-table .number {
            font-size: 18pt;
            font-weight: bold;
        }
        h2 {
            font-size: 14pt;
            font-weight: bold;
            margin: 20px 0 10px 0;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px 8px;
            text-align: left;
            font-size: 10pt;
        }
        th {
            background: #f0f0f0;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10pt;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
        @page {
            size: A4 landscape;
            margin: 2cm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN ORDER TEKNIK</h1>
        <p>PT. PDAM Keluhan - Penanggulangan Kebocoran</p>
        @if($dari || $sampai)
        <p>Periode: {{ $dari ? $dari : '-' }} s/d {{ $sampai ? $sampai : '-' }}</p>
        @endif
    </div>

    <div class="summary">
        <table class="summary-table">
            <tr>
                <td>
                    <div class="number">{{ $totalOrders }}</div>
                    <div>Total Order</div>
                </td>
                <td>
                    <div class="number">{{ $completedOrders }}</div>
                    <div>Selesai</div>
                </td>
                <td>
                    <div class="number">{{ $pendingOrders }}</div>
                    <div>Belum Selesai</div>
                </td>
            </tr>
        </table>
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
                <th class="text-center">No</th>
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
