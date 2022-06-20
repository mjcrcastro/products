<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'provider_id', 
        'purchase_date', 
        'purchase_invoice_number',
        'buyer_id'
        
    ];
    
    public function purchaseDetails() {
        return $this->hasMany(PurchaseDetail::class);
    }
    
    public function buyer() {
        return $this->belongsTo(Buyer::class);
    }
    
    public function provider() {
        return $this->belongsTo(Provider::class);
    }
}
