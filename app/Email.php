<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $guarded = [];

    public function member_request()
    {
        return $this->belongsTo('App\MemberRequest');
    }

    public function invite()
    {
        return $this->belongsTo('App\Invite');
    }

    public function account()
    {
        return $this->belongsTo('App\Account', 'id');
    }
}
