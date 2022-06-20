<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id', 
        'amount', 
        'price'
    ];
    
    public function invoice(){
        return $this->belongsTo(Purchase::class);
    }
    
    public function product() {
        return $this->belongsTo(Product::class);
    }
}
