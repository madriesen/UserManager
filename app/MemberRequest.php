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

    /**
     * @return array
     */
    public function format()
    {
        return [
            'member_request_id' => $this->id,
            'first_name' => $this->first_name,
            'name' => $this->name,
            'for_email_address' => $this->email->address
        ];
    }


    /**
     * @return bool
     */
    public function getRespondedAttribute(): bool
    {
        return ($this->approved || $this->refused);
    }

    /**
     * @return bool
     */
    public function getApprovedAttribute(): bool
    {
        return !empty($this->approved_at);
    }

    /**
     * @return bool
     */
    public function getRefusedAttribute(): bool
    {
        return !empty($this->refused_at);
    }
}
