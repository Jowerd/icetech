<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceBuyer extends Model
{
    protected $fillable = [
        'name', 'id_number', 'address',
        'phone', 'email', 'bank', 'account',
    ];
}
