<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ChartOfAccount extends Model {

    use HasFactory;

    public function parent() {
        return $this->belongsTo(ChartOfAccount::class, 'account_parent_id');
    }

    public function child() {
        return $this->hasMany(self::class, 'account_parent_id');
    }

    public function fullDescription($account_id) {
        
        //Log::info(print_r($account->account_description, true));
        
        $account = ChartOfAccount::find($account_id);
        
        if ( empty( $account->account_parent_id ) ) { //return this description it if has no parent 
            return $account->account_description;
        } else {
            return self::fullDescription( $account->account_parent_id ) . '-' . $account->account_description;
        }
    }
    
    public function fullCode($account_id) {
        
        //Log::info(print_r($account->account_description, true));
        
        $account = ChartOfAccount::find($account_id);
        
        if ( empty( $account->account_parent_id ) ) { //return this description it if has no parent 
            return $account->account_code;
        } else {
            return self::fullCode( $account->account_parent_id ) . '-' . $account->account_code;
        }
    }

}
