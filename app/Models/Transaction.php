<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'tb_transactions';

    protected $fillable = [
        'no_nota',
        'user_id',
        'total_harga',
        'bayar',
        'kembali',
        'metode_pembayaran',
        'catatan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
