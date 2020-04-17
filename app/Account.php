<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Account extends Model
{
    use Authenticatable, HasApiTokens;

    protected $guarded = [];
    protected $hidden = ['password'];

    public function account_type()
    {
        return $this->belongsTo('App\AccountType');
    }

    public function email()
    {
        return $this->hasOne('App\Email', 'id');
    }

    public function profile()
    {
        return $this->hasOne('App\Profile');
    }


    public function format()
    {
        return [
            "id" => $this->id,
            "active" => empty($this->inactive_since),
            "primary_email" => $this->email->address,
            "account_type_id" => $this->account_type_id,
//            "created_at" => $this->created_at,
//            "updated_at" => $this->updated_at,
        ];
    }
}
