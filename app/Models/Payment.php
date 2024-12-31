<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id', 'amount', 'payment_method', 'status', 'transaction_id', 'processed_at',''
    ];

    protected $dates = ['deleted_at']; 

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
