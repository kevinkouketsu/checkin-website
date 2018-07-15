<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Graduate extends Model
{
    //
    public function members()
    {
        return $this->hasMany(User::class);
    }
}
