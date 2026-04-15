<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Report & Statistik - PDAM Kebocoran</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #0f3f9a;
            --primary-soft: #eaf1ff;
            --line: #d7deeb;
            --bg: #f4f6fb;
            --text: #223047;
            --muted: #65758f;
            --white: #ffffff;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text);
            background: var(--bg);
            line-height: 1.45;
        }

        .header {
            background: linear-gradient(180deg, #0f1219 0%, #181d27 100%);
            color: var(--white);
            padding: 12px 10px;
            border-bottom: 1px solid #242b38;
        }

        .header-inner {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .brand-logo {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand h1 {
            margin: 0;
            font-size: clamp(1.15rem, 1rem + 1.1vw, 1.6rem);
            line-height: 1.2;
        }

        .container {
            width: 100%;
            margin: 10px 0 0;
            padding: 0 10px 10px;
        }

        .layout {
            display: grid;
            grid-template-columns: 240px minmax(0, 1fr);
            gap: 10px;
            align-items: start;
        }

        .sidebar {
            background: linear-gradient(180deg, #0f1219 0%, #181d27 100%);
            border: 1px solid #242b38;
            border-radius: 14px;
            padding: 12px;
            box-shadow: 0 12px 24px rgba(5, 9, 16, 0.35);
            position: sticky;
            top: 8px;
        }

        .sidebar-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 8px 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            margin-bottom: 10px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e0f2fe;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-name {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
        }

        .profile-role {
            margin: 2px 0 0;
            font-size: 12px;
            color: #9da9be;
        }

        .sidebar-title {
            margin: 0 0 10px;
            font-size: 12px;
            color: #90a0bd;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            padding: 0 6px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: #d4dcef;
            border: 1px solid #2a3344;
            border-radius: 10px;
            padding: 10px 10px;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 700;
            background: #1a2130;
            transition: 0.18s;
        }

        .menu-link:hover {
            border-color: #3b4b66;
            background: #232d40;
        }

        .menu-link.active {
            border-color: #3a7aff;
            background: linear-gradient(180deg, #1f5fcc 0%, #1c4ea7 100%);
            color: #fff;
        }

        .menu-left {
            display: inline-flex;
            align-items: center;
            gap: 9px;
        }

        .menu-icon {
            width: 24px;
            height: 24px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            background: #2b3850;
            color: #dbe8ff;
        }

        .menu-link.active .menu-icon {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .menu-badge {
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 999px;
            background: #295fcc;
            color: #fff;
            font-weight: 700;
        }

        .card {
            background: var(--white);
            border: 1px solid var(--line);
            border-radius: 10px;
            margin-bottom: 12px;
            box-shadow: 0 4px 12px rgba(15, 63, 154, 0.06);
        }

        .card-head {
            background: var(--primary-soft);
            border-bottom: 1px solid var(--line);
            padding: 12px 14px;
            font-weight: 700;
            font-size: 16px;
        }

        .card-body {
            padding: 14px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: var(--white);
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 16px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .stat-card.primary {
            border-left: 4px solid var(--primary);
        }

        .stat-card.success {
            border-left: 4px solid var(--success);
        }

        .stat-card.warning {
            border-left: 4px solid var(--warning);
        }

        .stat-card.danger {
            border-left: 4px solid var(--danger);
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--muted);
            font-weight: 600;
        }

        .chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 12px;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .filter-card {
            background: var(--white);
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 12px;
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-input {
            width: 140px;
            border: 1px solid var(--line);
            border-radius: 7px;
            padding: 10px 11px;
            font-size: 14px;
            background: #fff;
        }

        .btn {
            border: 0;
            border-radius: 7px;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-light {
            background: #e9edf6;
            color: #2e3d57;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .data-table th,
        .data-table td {
            border-bottom: 1px solid var(--line);
            padding: 10px 12px;
            text-align: left;
        }

        .data-table th {
            background: #f8fafd;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 700;
        }

        .data-table td {
            text-align: center;
        }

        @media (max-width: 860px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
            }

            .chart-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .chart-grid {
                grid-template-columns: 1fr;
            }

            .filter-card {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-input {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-inner">
            <div class="brand">
                <div class="brand-logo" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C9.2 5.7 6 9.2 6 13.1C6 16.4 8.7 19 12 19C15.3 19 18 16.4 18 13.1C18 9.2 14.8 5.7 12 2Z" fill="#D9EBFF"/>
                        <path d="M9 13.5C9 12.2 9.8 11 11 10.1" stroke="#0F3F9A" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <h1>Dashboard Sistem Input Data Petugas</h1>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="layout">
            <aside class="sidebar">
                <div class="sidebar-profile">
                    <div class="avatar" style="padding: 2px;">
                        <img src="/images/pipa.png" alt="Logo PDAM" style="width: 100%; height: 100%; object-fit: contain; border-radius: 50%;">
                    </div>
                    <div>
                        <p class="profile-name">Kebocoran Admin</p>
                        <p class="profile-role">Admin</p>
                    </div>
                </div>
                <h3 class="sidebar-title">Menu Order</h3>
                <a class="menu-link" href="/">
                    <span class="menu-left">
                        <span class="menu-icon">📋</span>
                        <span>Input Data</span>
                    </span>
                </a>
                <a class="menu-link active" href="/report">
                    <span class="menu-left">
                        <span class="menu-icon">📊</span>
                        <span>Report & Statistik</span>
                    </span>
                </a>
            </aside>

            <div>
                <form class="filter-card" method="GET" action="/report" style="flex-wrap: wrap; gap: 10px;">
                    <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                        <span style="font-weight: 700; color: var(--text);">Periode:</span>
                        <input class="filter-input" type="text" name="dari" value="{{ $dari }}" placeholder="Dari (dd/mm/yyyy)">
                        <span>s/d</span>
                        <input class="filter-input" type="text" name="sampai" value="{{ $sampai }}" placeholder="Sampai (dd/mm/yyyy)">
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                        <span style="font-weight: 700; color: var(--text);">Kode Order:</span>
                        <input class="filter-input" type="text" name="kode_order" value="{{ $kode_order }}" placeholder="Contoh: 3" style="width: 80px;">
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                        <span style="font-weight: 700; color: var(--text);">Status:</span>
                        <select name="status" class="filter-input" style="width: 140px;">
                            <option value="" {{ $status == '' ? 'selected' : '' }}>Semua</option>
                            <option value="proses" {{ $status == 'proses' ? 'selected' : '' }}>Belum Selesai</option>
                            <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: center; margin-left: auto;">
                        <button class="btn btn-primary" type="submit">Filter</button>
                    </div>
                    <div style="display: flex; gap: 8px; width: 100%; justify-content: flex-end; margin-top: 10px;">
                        <a class="btn btn-light" href="{{ route('report.export.pdf', ['dari' => $dari, 'sampai' => $sampai, 'kode_order' => $kode_order, 'status' => $status]) }}" style="text-decoration:none; background: #dc2626; color: #fff;">📄 PDF</a>
                        <a class="btn btn-light" href="{{ route('report.export.csv', ['dari' => $dari, 'sampai' => $sampai, 'kode_order' => $kode_order, 'status' => $status]) }}" style="text-decoration:none; background: #16a34a; color: #fff;">📊 Excel</a>
                        <a class="btn btn-light" href="{{ route('report.export.word', ['dari' => $dari, 'sampai' => $sampai, 'kode_order' => $kode_order, 'status' => $status]) }}" style="text-decoration:none; background: #2563eb; color: #fff;">📝 Word</a>
                    </div>
                </form>

                <div class="stats-grid">
                    <div class="stat-card primary">
                        <div class="stat-value">{{ $totalOrders }}</div>
                        <div class="stat-label">Total Order</div>
                    </div>
                    <div class="stat-card success">
                        <div class="stat-value">{{ $completedOrders }}</div>
                        <div class="stat-label">Order Selesai</div>
                    </div>
                    <div class="stat-card warning">
                        <div class="stat-value">{{ $pendingOrders }}</div>
                        <div class="stat-label">Order Belum Selesai</div>
                    </div>
                    <div class="stat-card danger">
                        <div class="stat-value">{{ $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0 }}%</div>
                        <div class="stat-label">% Penyelesaian</div>
                    </div>
                </div>

                <div class="chart-grid">
                    <div class="card">
                        <div class="card-head">Statistik Berdasarkan Status</div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="statusChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-head">Statistik Berdasarkan Sumber</div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="sourceChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                @if(count($statsByPelaksana) > 0)
                <div class="card">
                    <div class="card-head">Top 10 Pelaksana</div>
                    <div class="card-body">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pelaksana</th>
                                    <th>Jumlah Order</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($statsByPelaksana as $pelaksana => $total)
                                <tr>
                                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                                    <td style="text-align: left;">{{ $pelaksana }}</td>
                                    <td style="text-align: center;">{{ $total }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <div class="card" style="margin-top: 20px;">
                    <div class="card-head">Daftar Order ({{ $orders->count() }} data)</div>
                    <div class="card-body">
                        <div style="overflow-x: auto;">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tgl Masuk</th>
                                        <th>No Pelanggan</th>
                                        <th>Pelapor</th>
                                        <th>Alamat</th>
                                        <th>Kode Order</th>
                                        <th>Tgl Realisasi</th>
                                        <th>Pelaksana</th>
                                        <th>Status</th>
                                        <th>Sumber</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $order->tanggal_masuk }}</td>
                                        <td>{{ $order->no_pelanggan }}</td>
                                        <td style="text-align: left;">{{ $order->pelapor }}</td>
                                        <td style="text-align: left;">{{ $order->alamat }}</td>
                                        <td>{{ $order->kode_order }}</td>
                                        <td>{{ $order->tanggal_realisasi }}</td>
                                        <td>{{ $order->pelaksana }}</td>
                                        <td>
                                            @if($order->status_realisasi === 'selesai')
                                                <span style="color: #16a34a; font-weight: 600;">Selesai</span>
                                            @else
                                                <span style="color: #dc2626; font-weight: 600;">Belum</span>
                                            @endif
                                        </td>
                                        <td style="text-align: left;">{{ $order->sumber }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" style="text-align: center; color: #64748b;">Tidak ada data yang sesuai dengan filter.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Belum Selesai', 'Selesai'],
                datasets: [{
                    data: [
                        {{ $statsByStatus['proses'] ?? 0 }},
                        {{ $statsByStatus['selesai'] ?? 0 }}
                    ],
                    backgroundColor: ['#3b82f6', '#22c55e'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Source Chart
        const sourceCtx = document.getElementById('sourceChart').getContext('2d');
        new Chart(sourceCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($statsBySource)) !!},
                datasets: [{
                    label: 'Jumlah Order',
                    data: {!! json_encode(array_values($statsBySource)) !!},
                    backgroundColor: '#0f3f9a',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

    </script>
</body>
</html>
