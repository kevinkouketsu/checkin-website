<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EventList extends Model
{
    //
    protected $casts = [
        'data' => 'datetime: Y'
    ];

    protected $fillable = ['data', 'name', 'description', 'eventtype_id', 'city_code'];
    protected $perPage = 10;

    public function checks()
    {
        return $this->hasMany(EventCheck::class, 'eventlist_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(EventType::class, 'eventtype_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_code', 'code');
    }

    // Scopes
    public function scopeEvent($query, $id)
    {
        return $query->where('id', $id);
    }

    public function deletEvent($id)
    {
        $event = auth()->user()->events()->event($id)->get()->first();

        if(!$event) 
            return false;

        return $event->delete();
    }

    public function getEventInfo($id) : EventList
    {
        return $this->where('id', $id)
                    ->get()
                    ->first();
    }

    public function insertEvent($data) 
    {
        $insert = auth()->user()->events()->create([
            'data'              => $data['data'],
            'name'              => $data['name'],
            'description'       => $data['description'],
            'eventtype_id'      => $data['eventtype_id'],
            'city_code'         => $data['cities']
        ]);

        return $insert;
    }  
    
    public function getEventList($id, $request = null)
    {
        if($request !== null)
        {
            $eventList = $this->where('user_id', $id)->where(function($query) use ($request) {
                if(isset($request->name))
                    $query->where('name', 'LIKE', "%{$request->name}%");

                if(isset($request->dateInitial))
                    $query->where('data', '>=', $request->dateInitial." 23:59:59");
                
                if(isset($request->dateInitial))
                    $query->where('data', '>=', $request->dateInitial." 23:59:59");
                
                if(isset($request->dateMax))
                    $query->where('data', '<=', $request->dateMax . " 23:59:59");

                if(isset($request->type) && $request->type != -1)
                    $query->where('eventtype_id', $request->type);

                if(isset($request->cities))
                    $query->where('city_code', $request->cities);
            })->orderBy('data', 'DESC')
              ->with('city')
              ->paginate($this->perPage);
        }
        else
            $eventList = $this->where('user_id', $id)
                              ->orderBy('data', 'DESC')
                              ->with('city')
                              ->paginate($this->perPage);
    
        return $eventList;
    }
}
