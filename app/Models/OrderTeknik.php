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
                    ->orWhere('tanggal_realisasi', 'like', "%{$q}%")
                    ->orWhere('pelaksana', 'like', "%{$q}%")
                    ->orWhere('status_realisasi', 'like', "%{$q}%")
                    ->orWhere('sumber', 'like', "%{$q}%");
            });
        }

        return $query;
    }

    /**
     * Filter rentang tanggal masuk (format dd/mm/yyyy).
     */
    public function scopeFilterTanggalMasuk($query, string $dari, string $sampai)
    {
        $pattern = '/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/';

        if ($dari !== '' && preg_match($pattern, $dari)) {
            $query->whereRaw(
                "STR_TO_DATE(tanggal_masuk, '%d/%m/%Y') >= STR_TO_DATE(?, '%d/%m/%Y')",
                [$dari]
            );
        }

        if ($sampai !== '' && preg_match($pattern, $sampai)) {
            $query->whereRaw(
                "STR_TO_DATE(tanggal_masuk, '%d/%m/%Y') <= STR_TO_DATE(?, '%d/%m/%Y')",
                [$sampai]
            );
        }

        return $query;
    }

    /**
     * Filter rentang tanggal realisasi (format dd/mm/yyyy).
     */
    public function scopeFilterTanggalRealisasi($query, string $dari, string $sampai)
    {
        $pattern = '/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/';

        if ($dari !== '' && preg_match($pattern, $dari)) {
            $query->whereRaw(
                "STR_TO_DATE(tanggal_realisasi, '%d/%m/%Y') >= STR_TO_DATE(?, '%d/%m/%Y')",
                [$dari]
            );
        }

        if ($sampai !== '' && preg_match($pattern, $sampai)) {
            $query->whereRaw(
                "STR_TO_DATE(tanggal_realisasi, '%d/%m/%Y') <= STR_TO_DATE(?, '%d/%m/%Y')",
                [$sampai]
            );
        }

        return $query;
    }

    /**
     * Filter tanggal spesifik (cocok ke tanggal_masuk atau tanggal_realisasi).
     */
    public function scopeFilterTanggal($query, string $tanggal)
    {
        $pattern = '/^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/';

        if ($tanggal !== '' && preg_match($pattern, $tanggal)) {
            $query->where(function ($q) use ($tanggal) {
                $q->where('tanggal_masuk', $tanggal)
                  ->orWhereRaw(
                      "STR_TO_DATE(tanggal_realisasi, '%d/%m/%Y') = STR_TO_DATE(?, '%d/%m/%Y')",
                      [$tanggal]
                  );
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
