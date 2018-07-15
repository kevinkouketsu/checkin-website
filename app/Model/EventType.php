<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    //
    public function events()
    {
        return $this->hasMany(EventType::class);
    }

    public function scopeId($query, $id)
    {
        return $query->where('id', $id);
    }

    public function edit($id, $name)
    {
    }
}
