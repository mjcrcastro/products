<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'invoicenumber_mobile', 
        'mobile_id', 
        'customername',
        'invoicedate',
        'invoicetotal'
        
    ];
    
    public function invoiceDetails() {
        return $this->hasMany(InvoiceDetail::class);
    }
}
