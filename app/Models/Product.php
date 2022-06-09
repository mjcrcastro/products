<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    use HasFactory;

    protected $fillable = [
        'barcode', 'description', 'price'
    ];
    public static $createRules = array(
        'barcode' => 'required|unique:products,description,null,{{$id}}',
        'description' => 'required|unique:products,description,null,{{$id}}'
    );

}
