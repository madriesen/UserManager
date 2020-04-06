<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberRequest extends Model
{
    protected $fillable = ['name', 'first_name'];

    public function email()
    {
        return $this->hasOne('App\Email');
    }
}
