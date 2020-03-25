<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $guarded = [];

    public function account_type(){
        return $this->belongsTo('App\AccountType');
    }

    public function email(){
        return $this->hasOne('App\Email');
    }

    public function profile(){
        return $this->hasOne('App\Profile');
    }
}