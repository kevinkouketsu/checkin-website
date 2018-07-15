<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    public function state()
    {
        return $this->belongsTo(State::class, 'state_uf', 'uf');
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
