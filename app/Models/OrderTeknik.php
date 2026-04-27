<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTeknik extends Model
{
    protected $fillable = [
        'tanggal',
        'tanggal_masuk',
        'nama_pelanggan',
        'pelapor',
        'alamat',
        'kelurahan',
        'kecamatan',
        'zona',
        'kode_order',
        'realisasi_order',
        'perbaikan',
        'diameter',
        'keterangan',
        'stan_meter',
        'tanggal_realisasi',
        'pelaksana',
        'status_realisasi',
        'sumber',
        'no_pelanggan',
    ];

    protected $casts = [
        'tanggal'           => 'date',
        'tanggal_masuk'     => 'date',
        'tanggal_realisasi' => 'date',
    ];

    // === QUERY SCOPES ===

    /**
     * Filter berdasarkan tab menu (semua/sudah/belum).
     */
    public function scopeFilterMenu($query, string $menu)
    {
        if ($menu === 'sudah') {
            $query->where('status_realisasi', 'selesai');
        } elseif ($menu === 'belum') {
            $query->whereIn('status_realisasi', ['masuk', 'proses']);
        }

        return $query;
    }

    /**
     * Filter pencarian teks (no pelanggan, pelapor, alamat, dsb).
     */
    public function scopeFilterSearch($query, string $q)
    {
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('no_pelanggan', 'like', "%{$q}%")
                    ->orWhere('pelapor', 'like', "%{$q}%")
                    ->orWhere('alamat', 'like', "%{$q}%")
                    ->orWhere('kode_order', 'like', "%{$q}%")
                    ->orWhere('keterangan', 'like', "%{$q}%")
                    ->orWhere('stan_meter', 'like', "%{$q}%")
                    ->orWhere('pelaksana', 'like', "%{$q}%")
                    ->orWhere('status_realisasi', 'like', "%{$q}%")
                    ->orWhere('sumber', 'like', "%{$q}%");
            });
        }

        return $query;
    }

    /**
     * Filter rentang tanggal masuk (format yyyy-mm-dd dari date input).
     */
    public function scopeFilterTanggalMasuk($query, string $dari, string $sampai)
    {
        if ($dari !== '') {
            $query->whereDate('tanggal_masuk', '>=', $dari);
        }
        if ($sampai !== '') {
            $query->whereDate('tanggal_masuk', '<=', $sampai);
        }

        return $query;
    }

    /**
     * Filter rentang tanggal realisasi (format yyyy-mm-dd dari date input).
     */
    public function scopeFilterTanggalRealisasi($query, string $dari, string $sampai)
    {
        if ($dari !== '') {
            $query->whereDate('tanggal_realisasi', '>=', $dari);
        }
        if ($sampai !== '') {
            $query->whereDate('tanggal_realisasi', '<=', $sampai);
        }

        return $query;
    }

    /**
     * Filter tanggal spesifik (yyyy-mm-dd).
     */
    public function scopeFilterTanggal($query, string $tanggal)
    {
        if ($tanggal !== '') {
            $query->where(function ($q) use ($tanggal) {
                $q->whereDate('tanggal_masuk', $tanggal)
                  ->orWhereDate('tanggal_realisasi', $tanggal);
            });
        }

        return $query;
    }

    /**
     * Filter status realisasi.
     */
    public function scopeFilterStatus($query, string $status)
    {
        if ($status !== '' && in_array($status, ['proses', 'selesai'])) {
            $query->where('status_realisasi', $status);
        }

        return $query;
    }

    /**
     * Filter kode order (LIKE search).
     */
    public function scopeFilterKodeOrder($query, string $kode)
    {
        if ($kode !== '') {
            $query->where('kode_order', 'LIKE', '%' . $kode . '%');
        }

        return $query;
    }
}
