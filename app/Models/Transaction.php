<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $guarded = ['id'];

    // INI YANG PENTING AGAR DATA SINKRON
    protected $casts = [
        'items' => 'array',       // Agar JSON dari database otomatis jadi Array PHP
        'start_date' => 'date',   // Agar bisa diformat tanggalnya (d M Y)
        'total_price' => 'integer',
        'days' => 'integer',
    ];
}
