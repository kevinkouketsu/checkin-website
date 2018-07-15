<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    //
    public function cities()
    {
        return $this->hasMany(City::class, 'state_uf', 'uf');
    }

    public function states()
    {
        return $this->hasMany(State::class);
    }
}
