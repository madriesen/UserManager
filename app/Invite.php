<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    public function email()
    {
        return $this->hasOne('App\Email');
    }

}
