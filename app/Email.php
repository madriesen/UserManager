<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $guarded = [];

    public function member_request(){
        return $this->belongsTo('App\MemberRequest');
    }
}
