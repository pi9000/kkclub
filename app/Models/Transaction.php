<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'tb_transaksi';

    public function User()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
