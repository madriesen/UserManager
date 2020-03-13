<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberRequest extends Model
{
    public function email()
    {
        return $this->hasOne('App\Email');
    }
}
