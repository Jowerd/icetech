<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 'issue_date', 'status',
        'seller_name', 'seller_id_number', 'seller_address',
        'seller_phone', 'seller_email', 'seller_bank', 'seller_account',
        'seller_bank2', 'seller_account2',
        'buyer_name', 'buyer_id_number', 'buyer_address',
        'buyer_phone', 'buyer_email', 'buyer_bank', 'buyer_account',
        'notes', 'total',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'total'      => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public static function generateNumber(): string
    {
        $year  = date('Y');
        $last  = static::whereYear('created_at', $year)->max('id') ?? 0;
        return 'INV-' . $year . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }
}
