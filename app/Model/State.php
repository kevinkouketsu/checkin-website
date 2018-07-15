<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    //
    public function cities()
    {
        return $this->hasMany(City::class, 'state_uf', 'uf');
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
