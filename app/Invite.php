<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    public function email()
    {
        return $this->hasOne('App\Email');
    }

    /**
     * @return bool
     */
    public function getRespondedAttribute(): bool
    {
        return ($this->accepted || $this->declined);
    }

    /**
     * @return bool
     */
    public function getAcceptedAttribute(): bool
    {
        return !empty($this->accepted_at);
    }

    /**
     * @return bool
     */
    public function getDeclinedAttribute(): bool
    {
        return !empty($this->declined_at);
    }

}
