<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    //
    protected $fillable = ['name'];
    public $timestamps = false;
    
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

    /**
     * Cria um novo tipo de evneto
     * @param  [string] $name Nome do Evento
     * @return [bool]       true = sucesso, false = falha
     */
    public function new($name) 
    {
        return $this->create(['name' => $name]);
    }

    public function updateType ($id, $name)
    {
        return $this->where('id', $id)
                    ->update(['name' => $name]);
    }
}
