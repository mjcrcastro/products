<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'buyer_name'
    ];
    public static $createRules = array(
        'buyer_name' => 'required|unique:buyers,buyer_name,null,{{$id}}'
    );
    
    public function invoiceDetails() {
        return $this->hasMany(Purchase::class);
    }
    
}
