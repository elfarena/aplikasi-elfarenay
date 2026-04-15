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
}

