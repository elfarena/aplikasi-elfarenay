<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Input Petugas</title>
    <style>
        :root {
            --primary: #0f3f9a;
            --primary-soft: #eaf1ff;
            --line: #d7deeb;
            --bg: #f4f6fb;
            --text: #223047;
            --muted: #65758f;
            --white: #ffffff;
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

        .header-meta {
            text-align: right;
            font-size: 13px;
            opacity: 0.95;
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
            align-items: stretch;
        }

        .sidebar {
            background: linear-gradient(180deg, #0f1219 0%, #181d27 100%);
            border: 1px solid #242b38;
            border-radius: 14px;
            padding: 12px;
            box-shadow: 0 12px 24px rgba(5, 9, 16, 0.35);
            position: sticky;
            top: 8px;
            align-self: start;
            height: auto;
            min-height: calc(100vh - 80px);
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
            background: #ffde29;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            line-height: 1;
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

        .menu-left {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            min-width: 0;
        }

        .menu-icon {
            width: 24px;
            height: 24px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 14px;
            background: #2b3850;
            color: #dbe8ff;
        }

        .menu-badge {
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 999px;
            background: #295fcc;
            color: #fff;
            font-weight: 700;
        }

        .menu-link.active {
            border-color: #3a7aff;
            background: linear-gradient(180deg, #1f5fcc 0%, #1c4ea7 100%);
            color: #fff;
        }

        .menu-link.active .menu-icon {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .flash,
        .errors {
            margin-bottom: 10px;
            border-radius: 8px;
            padding: 9px 10px;
            font-size: 13px;
        }

        .flash {
            border: 1px solid #b8e8cc;
            background: #e7fff1;
            color: #096a42;
        }

        .errors {
            border: 1px solid #f3c3c3;
            background: #fff1f1;
            color: #9d2020;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 10px;
            margin-bottom: 12px;
        }

        .search-card {
            background: var(--white);
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 240px;
            border: 1px solid var(--line);
            border-radius: 7px;
            padding: 10px 11px;
            font-size: 14px;
            background: #fff;
        }

        .date-input {
            width: 140px;
            border: 1px solid var(--line);
            border-radius: 7px;
            padding: 10px 11px;
            font-size: 14px;
            background: #fff;
        }

        .stat {
            background: var(--white);
            border: 1px solid var(--line);
            border-left: 4px solid var(--primary);
            border-radius: 8px;
            padding: 12px 14px;
        }

        .stat small {
            display: block;
            color: var(--muted);
            margin-bottom: 6px;
            font-size: 13px;
        }

        .stat strong {
            font-size: 26px;
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

        .form-grid {
            display: grid;
            grid-template-columns: repeat(12, minmax(0, 1fr));
            gap: 10px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 5px;
            grid-column: span 6;
        }

        .field.wide { grid-column: span 12; }

        .field label {
            font-size: 13px;
            font-weight: 700;
        }

        .field input,
        .field select {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 10px 11px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            background: #fff;
        }

        .field select,
        .custom-select {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            background: #fff;
        }

        .custom-select {
            position: relative;
        }

        .custom-select-trigger {
            padding: 10px 35px 10px 11px;
            cursor: pointer;
            position: relative;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .custom-select-trigger::after {
            content: '';
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid var(--text);
        }

        .custom-select.open .custom-select-trigger::after {
            border-top: none;
            border-bottom: 5px solid var(--text);
        }

        .custom-select-options {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 6px;
            margin-top: 4px;
            max-height: none;
            overflow-y: visible;
            display: none;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .custom-select.open .custom-select-options {
            display: block;
        }

        .custom-option {
            padding: 10px 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid #f0f0f0;
        }

        .custom-option:last-child {
            border-bottom: none;
        }

        .custom-option:hover,
        .custom-option.focused {
            background: var(--primary-soft);
        }

        .custom-option.selected {
            font-weight: 700;
        }

        .option-bg {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            flex-shrink: 0;
        }

        /* Status colors */
        .option-bg.masuk { background: #fff4da; border: 2px solid #f59e0b; }
        .option-bg.proses { background: #dbeafe; border: 2px solid #3b82f6; }
        .option-bg.selesai { background: #dff8ea; border: 2px solid #22c55e; }

        /* Sumber colors */
        .option-bg.sumber-1 { background: #e0e7ff; border: 2px solid #6366f1; }
        .option-bg.sumber-2 { background: #fce7f3; border: 2px solid #ec4899; }
        .option-bg.sumber-3 { background: #d1fae5; border: 2px solid #10b981; }
        .option-bg.sumber-4 { background: #fef3c7; border: 2px solid #f59e0b; }
        .option-bg.sumber-5 { background: #e0f2fe; border: 2px solid #0ea5e9; }
        .option-bg.sumber-6 { background: #f3e8ff; border: 2px solid #a855f7; }

        .actions {
            margin-top: 10px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            border: 0;
            border-radius: 7px;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-light {
            background: #e9edf6;
            color: #2e3d57;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            min-width: 1100px;
        }

        th, td {
            border-bottom: 1px solid var(--line);
            padding: 10px 8px;
            text-align: left;
            vertical-align: top;
            white-space: nowrap;
        }

        /* Center align for specific columns */
        td:nth-child(1),  /* Tanggal Masuk */
        td:nth-child(2),  /* No Pelanggan */
        td:nth-child(5),  /* Kode Order */
        td:nth-child(7),  /* Stan Meter */
        td:nth-child(8),  /* Tanggal Realisasi */
        td:nth-child(9),  /* Pelaksana */
        td:nth-child(10), /* Status */
        td:nth-child(12)  /* Aksi */ {
            text-align: center;
        }

        th {
            background: #f8fafd;
            font-size: 12px;
            text-transform: uppercase;
        }

        .table-wrap {
            overflow-x: auto;
            border-radius: 8px;
        }

        .badge {
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            display: inline-block;
        }

        .badge-proses {
            background: #fff4da;
            color: #9b6205;
        }

        .badge-selesai {
            background: #dff8ea;
            color: #0d7d4f;
        }

        @media (min-width: 1080px) {
            .field {
                grid-column: span 4;
            }
            .field.wide {
                grid-column: span 8;
            }
        }

        @media (max-width: 860px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
            }

            .field,
            .field.wide {
                grid-column: span 12;
            }
        }

        @media (max-width: 640px) {
            .header-inner {
                flex-direction: column;
                align-items: flex-start;
            }
            .header-meta {
                text-align: left;
            }
            .container {
                padding: 0 8px 8px;
            }
        }
        /* Pagination */
        .pagination-wrap {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            padding: 12px 0 4px;
        }

        .page-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            color: var(--primary);
            background: #fff;
            border: 1px solid var(--line);
            transition: 0.15s;
        }

        .page-btn:hover {
            background: var(--primary-soft);
            border-color: var(--primary);
        }

        .page-active {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
            pointer-events: none;
        }

        .page-disabled {
            color: #adb5c9;
            pointer-events: none;
            background: #f8fafd;
        }

        .page-info {
            font-size: 12px;
            color: var(--muted);
            margin-left: 6px;
        }

        .btn-danger {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fca5a5;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-danger:hover {
            background: #dc2626;
            color: #fff;
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
            <div class="header-meta">
                Surat Perintah Order Kerja - Penanggulangan Kebocoran
            </div>
        </div>
    </header>

    <main class="container">
        @if (session('success'))
            <div class="flash">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="errors">{{ $errors->first() }}</div>
        @endif

        <div class="layout">
            <aside class="sidebar">
                <div class="sidebar-profile">
                    <div class="avatar" style="background: #e0f2fe; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <img src="/images/pipa.png" width="40" height="40 " alt="Logo" style="object-fit: cover;">
                    </div>
                    <div>
                        <p class="profile-name">{{ auth()->user()->name }}</p>
                        <p class="profile-role">Admin</p>
                    </div>
                </div>
                <h3 class="sidebar-title">Menu Order</h3>
                <a class="menu-link {{ ($menu ?? 'semua') === 'sudah' ? 'active' : '' }}" href="/?menu=sudah&q={{ urlencode($q ?? '') }}&dari={{ urlencode($dari ?? '') }}&sampai={{ urlencode($sampai ?? '') }}">
                    <span class="menu-left">
                        <span class="menu-icon">✔</span>
                        <span>Order sudah direalisasi</span>
                    </span>
                    <span class="menu-badge">SELESAI</span>
                </a>
                <a class="menu-link {{ ($menu ?? 'semua') === 'belum' ? 'active' : '' }}" href="/?menu=belum&q={{ urlencode($q ?? '') }}&dari={{ urlencode($dari ?? '') }}&sampai={{ urlencode($sampai ?? '') }}">
                    <span class="menu-left">
                        <span class="menu-icon">⏳</span>
                        <span>Order belum direalisasi</span>
                    </span>
                    <span class="menu-badge">BELUM</span>
                </a>
                <a class="menu-link {{ ($menu ?? 'semua') === 'semua' ? 'active' : '' }}" href="/?q={{ urlencode($q ?? '') }}&dari={{ urlencode($dari ?? '') }}&sampai={{ urlencode($sampai ?? '') }}">
                    <span class="menu-left">
                        <span class="menu-icon">📋</span>
                        <span>Semua order</span>
                    </span>
                    <span class="menu-badge">SEMUA</span>
                </a>
                <a class="menu-link" href="/report">
                    <span class="menu-left">
                        <span class="menu-icon">📊</span>
                        <span>Report & Statistik</span>
                    </span>
                </a>
                <div style="border-top: 1px solid rgba(255, 255, 255, 0.08); margin: 15px 0 10px;"></div>
                <a class="menu-link" href="/logout" style="border-color: #dc2626; background: rgba(220, 38, 38, 0.1);">
                    <span class="menu-left">
                        <span class="menu-icon" style="background: #dc2626; color: #fff;">🚪</span>
                        <span>Logout</span>
                    </span>
                </a>
            </aside>

            <div>
                <form class="search-card" method="GET" action="/">
                    <input type="hidden" name="menu" value="{{ $menu ?? 'semua' }}">
                    <input
                        class="search-input"
                        type="text"
                        name="q"
                        value="{{ $q ?? '' }}"
                        placeholder="Cari cepat: no pelanggan, pelapor, alamat, kode order, pelaksana, status, sumber..."
                    >
                    <div style="display: flex; gap: 8px; flex-wrap: wrap; width: 100%;">
                        <div style="display: flex; align-items: center; gap: 4px;">
                            <small style="color: var(--muted); font-size: 11px;">Tgl Masuk:</small>
                            <input class="date-input" type="date" name="tgl_masuk_dari" value="{{ $tgl_masuk_dari ?? '' }}" style="width: 130px;">
                            <input class="date-input" type="date" name="tgl_masuk_sampai" value="{{ $tgl_masuk_sampai ?? '' }}" style="width: 130px;">
                        </div>
                        <div style="display: flex; align-items: center; gap: 4px;">
                            <small style="color: var(--muted); font-size: 11px;">Tgl Realisasi:</small>
                            <input class="date-input" type="date" name="tgl_realisasi_dari" value="{{ $tgl_realisasi_dari ?? '' }}" style="width: 130px;">
                            <input class="date-input" type="date" name="tgl_realisasi_sampai" value="{{ $tgl_realisasi_sampai ?? '' }}" style="width: 130px;">
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Cari</button>
                </form>

                <section class="stats">
                    <div class="stat">
                        <small>Total Data</small>
                        <strong>{{ $totalData }}</strong>
                    </div>
                    <div class="stat">
                        <small>Masuk / Proses</small>
                        <strong>{{ $belumSelesai }}</strong>
                    </div>
                    <div class="stat">
                        <small>Selesai</small>
                        <strong>{{ $sudahSelesai }}</strong>
                    </div>
                </section>

                @if(($menu ?? 'semua') === 'semua')
                <section class="card">
                    <div class="card-head">Input Data Order Kerja Petugas</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('order-teknik.store') }}">
                            @csrf
                            <div class="form-grid">
                        <div class="field">
                            <label for="tanggal_masuk">Tanggal Masuk</label>
                            <input id="tanggal_masuk" name="tanggal_masuk" type="date" value="{{ old('tanggal_masuk', now()->format('Y-m-d')) }}">
                        </div>
                        <div class="field">
                            <label for="no_pelanggan">Nomor Pelanggan</label>
                            <input id="no_pelanggan" name="no_pelanggan" type="text" value="{{ old('no_pelanggan') }}">
                        </div>
                        <div class="field">
                            <label for="pelapor">Pelapor</label>
                            <input id="pelapor" name="pelapor" type="text" value="{{ old('pelapor') }}">
                        </div>
                        <div class="field wide">
                            <label for="alamat">Alamat</label>
                            <input id="alamat" name="alamat" type="text" value="{{ old('alamat') }}">
                        </div>
                        <div class="field">
                            <label for="kode_order">Kode Order</label>
                            <input id="kode_order" name="kode_order" type="text" value="{{ old('kode_order') }}">
                        </div>
                        <div class="field wide">
                            <label for="keterangan">Keterangan</label>
                            <input id="keterangan" name="keterangan" type="text" value="{{ old('keterangan') }}">
                        </div>
                        <div class="field">
                            <label for="stan_meter">Stan Meter</label>
                            <input id="stan_meter" name="stan_meter" type="text" value="{{ old('stan_meter') }}">
                        </div>
                        <div class="field">
                            <label for="tanggal_realisasi">Tanggal Realisasi</label>
                            <input id="tanggal_realisasi" name="tanggal_realisasi" type="date" value="{{ old('tanggal_realisasi') }}">
                        </div>
                        <div class="field">
                            <label for="pelaksana">Pelaksana</label>
                            <input id="pelaksana" name="pelaksana" type="text" value="{{ old('pelaksana') }}">
                        </div>
                        <div class="field">
                            <label for="status_realisasi">Status Realisasi</label>
                            <div class="custom-select" id="status_realisasi_wrapper">
                                <div class="custom-select-trigger" tabindex="0" data-value="{{ old('status_realisasi', 'proses') }}">{{ old('status_realisasi', 'proses') === 'proses' ? 'Belum Selesai' : ucfirst(old('status_realisasi', 'proses')) }}</div>
                                <div class="custom-select-options">
                                    <div class="custom-option {{ old('status_realisasi') === 'proses' ? 'selected' : '' }}" data-value="proses"><span class="option-bg proses"></span>Belum Selesai</div>
                                    <div class="custom-option {{ old('status_realisasi') === 'selesai' ? 'selected' : '' }}" data-value="selesai"><span class="option-bg selesai"></span>Selesai</div>
                                </div>
                                <input type="hidden" id="status_realisasi" name="status_realisasi" value="{{ old('status_realisasi', 'proses') }}">
                            </div>
                        </div>
                        <div class="field">
                            <label for="sumber">Sumber</label>
                            <div class="custom-select" id="sumber_wrapper">
                                <div class="custom-select-trigger" tabindex="0" data-value="{{ old('sumber', 'Call center') }}">{{ old('sumber', 'Call center') }}</div>
                                <div class="custom-select-options">
                                    <div class="custom-option {{ old('sumber') === 'Call center' ? 'selected' : '' }}" data-value="Call center"><span class="option-bg sumber-1"></span>Call center</div>
                                    <div class="custom-option {{ old('sumber') === 'Wab' ? 'selected' : '' }}" data-value="Wab"><span class="option-bg sumber-2"></span>Wab</div>
                                    <div class="custom-option {{ old('sumber') === 'Hublang' ? 'selected' : '' }}" data-value="Hublang"><span class="option-bg sumber-3"></span>Hublang</div>
                                    <div class="custom-option {{ old('sumber') === 'Mpp' ? 'selected' : '' }}" data-value="Mpp"><span class="option-bg sumber-4"></span>Mpp</div>
                                    <div class="custom-option {{ old('sumber') === 'Pembaca meter' ? 'selected' : '' }}" data-value="Pembaca meter"><span class="option-bg sumber-5"></span>Pembaca meter</div>
                                    <div class="custom-option {{ old('sumber') === 'Sim' ? 'selected' : '' }}" data-value="Sim"><span class="option-bg sumber-6"></span>Sim</div>
                                </div>
                                <input type="hidden" id="sumber" name="sumber" value="{{ old('sumber', 'Call center') }}">
                            </div>
                        </div>
                            </div>

                            <div class="actions">
                                <button class="btn btn-primary" type="submit">Simpan Laporan Petugas</button>
                            </div>
                        </form>
                    </div>
                </section>
                @endif

                <section class="card">
                    <div class="card-head">Monitoring Realisasi Petugas</div>
                    <div class="card-body">
                        <div class="table-wrap">
                            <table>
                        <thead>
                            <tr>
                                <th>Tanggal Masuk</th>
                                <th>No Pelanggan</th>
                                <th>Pelapor</th>
                                <th>Alamat</th>
                                <th>Kode Order</th>
                                <th>Keterangan</th>
                                <th>Stan Meter</th>
                                <th>Tanggal Realisasi</th>
                                <th>Pelaksana</th>
                                <th>Status</th>
                                <th>Sumber</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rows as $row)
                                <tr>
                                    <td>{{ $row->tanggal_masuk?->format('d/m/Y') }}</td>
                                    <td>{{ $row->no_pelanggan }}</td>
                                    <td>{{ $row->pelapor }}</td>
                                    <td>{{ $row->alamat }}</td>
                                    <td>{{ $row->kode_order }}</td>
                                    <td>{{ $row->keterangan }}</td>
                                    <td>{{ $row->stan_meter }}</td>
                                    <td>{{ $row->tanggal_realisasi?->format('d/m/Y') }}</td>
                                    <td>{{ $row->pelaksana }}</td>
                                    <td>
                                        @if($row->status_realisasi === 'selesai')
                                            <span class="badge badge-selesai">Selesai</span>
                                        @else
                                            <span class="badge badge-proses">Belum</span>
                                        @endif
                                    </td>
                                    <td>{{ $row->sumber }}</td>
                                    <td>
                                        <a href="{{ route('order-teknik.edit', $row->id) }}" class="btn-edit" style="background: var(--primary); color: #fff; padding: 4px 10px; border-radius: 4px; text-decoration: none; font-size: 12px;">Edit</a>
                                        <form method="POST" action="{{ route('order-teknik.destroy', $row->id) }}" style="display:inline;" onsubmit="return confirm('Hapus data ini? Tindakan tidak bisa dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" style="text-align: center; color: #64748b;">Belum ada data tersimpan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                            </table>
                        </div>
                        {{ $rows->links('partials.pagination') }}
                    </div>
                </section>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const textInputs = document.querySelectorAll('#tanggal_masuk, #no_pelanggan, #pelapor, #alamat, #kode_order, #keterangan, #stan_meter, #tanggal_realisasi, #pelaksana');
            const form = document.querySelector('form[method="POST"]');

            // Handle Enter navigation for text inputs

            // Handle Enter navigation for text inputs
            textInputs.forEach((input, index) => {
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const nextInput = textInputs[index + 1];
                        if (nextInput) {
                            nextInput.focus();
                        } else {
                            // Last text input, focus to first custom select
                            document.querySelector('#status_realisasi_wrapper .custom-select-trigger').focus();
                        }
                    }
                });
            });

            // Custom Select functionality
            document.querySelectorAll('.custom-select').forEach(select => {
                const trigger = select.querySelector('.custom-select-trigger');
                const options = select.querySelector('.custom-select-options');
                const optionElements = select.querySelectorAll('.custom-option');
                const hiddenInput = select.querySelector('input[type="hidden"]');
                let focusedIndex = -1;

                function openSelect() {
                    select.classList.add('open');
                    // Find currently selected option
                    optionElements.forEach((opt, i) => {
                        if (opt.classList.contains('selected')) {
                            focusedIndex = i;
                        }
                    });
                }

                function closeSelect() {
                    select.classList.remove('open');
                    focusedIndex = -1;
                    optionElements.forEach(opt => opt.classList.remove('focused'));
                }

                function selectOption(option) {
                    optionElements.forEach(opt => opt.classList.remove('selected'));
                    option.classList.add('selected');
                    const value = option.dataset.value;
                    const text = option.textContent.trim();
                    trigger.textContent = text;
                    trigger.dataset.value = value;
                    hiddenInput.value = value;

                    // Re-add the colored dot
                    const dot = document.createElement('span');
                    dot.className = 'option-bg ' + option.querySelector('.option-bg').className.split(' ')[1];
                    trigger.insertBefore(dot, trigger.firstChild);
                }

                // Click to open
                trigger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (select.classList.contains('open')) {
                        closeSelect();
                    } else {
                        // Close other open selects
                        document.querySelectorAll('.custom-select.open').forEach(s => s.classList.remove('open'));
                        openSelect();
                    }
                });

                // Keyboard navigation on trigger
                trigger.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        if (!select.classList.contains('open')) {
                            openSelect();
                        } else {
                            // Select currently focused option and move to next
                            if (focusedIndex >= 0 && optionElements[focusedIndex]) {
                                selectOption(optionElements[focusedIndex]);
                                closeSelect();
                                // Move to next select or submit
                                const currentSelectId = select.id;
                                if (currentSelectId === 'status_realisasi_wrapper') {
                                    document.querySelector('#sumber_wrapper .custom-select-trigger').focus();
                                } else {
                                    form.submit();
                                }
                            }
                        }
                    } else if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        if (!select.classList.contains('open')) {
                            openSelect();
                        }
                        focusedIndex = Math.min(focusedIndex + 1, optionElements.length - 1);
                        updateFocus();
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        if (!select.classList.contains('open')) {
                            openSelect();
                        }
                        focusedIndex = Math.max(focusedIndex - 1, 0);
                        updateFocus();
                    } else if (e.key === 'Escape') {
                        closeSelect();
                    } else if (e.key === 'Tab') {
                        closeSelect();
                    }
                });

                function updateFocus() {
                    optionElements.forEach((opt, i) => {
                        opt.classList.toggle('focused', i === focusedIndex);
                    });
                }

                // Option click
                optionElements.forEach((option, index) => {
                    option.addEventListener('click', function(e) {
                        e.stopPropagation();
                        selectOption(option);
                        closeSelect();
                    });

                    option.addEventListener('mouseenter', function() {
                        focusedIndex = index;
                        updateFocus();
                    });
                });

                // Close on outside click
                document.addEventListener('click', function(e) {
                    if (!select.contains(e.target)) {
                        closeSelect();
                    }
                });
            });
        });
    </script>
</body>
</html>
