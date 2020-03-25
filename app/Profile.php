<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function email()
    {
        return $this->belongsTo('App\Email');
    }
}
