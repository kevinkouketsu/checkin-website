<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    /**
     * [get type]
     * @param  [type] $query [description]
     * @param  [type] $type  [description]
     * @return [type]        [description]
     */
    public function scopeType($query, $type)
    {
        return $query->where('eventtype_id', $type);
    }

    /**
     * [scopeCity description]
     * @param  [type] $query [description]
     * @param  [type] $city  [description]
     * @return [type]        [description]
     */
    public function scopeCity($query, $city)
    {
        return $query->where('city_code', $city);
    }

    public function scopeOnUsers($query, $ids)
    {
        $id = is_array($ids) ? $ids : array(0 => $ids);
        if(count($id)) 
            $query->whereIn('user_id', $id);
        

        return $query;
    }

    public function deletEvent(EventList $event)
    {
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
    
    /**
     * [scopeOnDateBetween description]
     * @param  [type] $dateInitial [description]
     * @param  [type] $dateFinal   [description]
     * @return [type]              [description]
    */
    public function scopeOnDateBetween($query, $dateInitial, $dateFinal)
    {
        if( !is_null($dateInitial) ) {
            $dateInitial = (($dateInitial instanceof Carbon)
                            ? $dateInitial
                            : Carbon::parse($dateInitial))
                                ->setTime(23, 59, 59)
                                ->toDateTimeString();

            // como podemos passar apenas a data de inicio e não final, devemos prever isso
            $dateFinal = (($dateFinal instanceof Carbon)
                            ? $dateFinal
                            : ((is_string($dateFinal))
                                ? Carbon::parse($dateFinal)
                                : Carbon::now()))
                                ->setTime(23, 59, 59)
                                ->toDateTimeString();

            $query->whereBetween('data', [$dateInitial, $dateFinal]);
        }
    }

    public function getEventList($id, $request = null)
    {
        $query = $this->newQuery();
        $query = $query->onUsers($id); // scope: método scopeOnUsers

        $dateInitial = isset($request->dateInitial) ? $request->dateInitial : NULL;
        $dateMax = isset($request->dateMax) ? $request->dateMax : NULL;

        if(isset($request->cities) && $request->cities != -1)
            $query->city($request->cities);

        if(isset($request->type) && $request->type != -1)
            $query->type($request->type);

        $query = $query->onDateBetween($dateInitial, $dateMax);

        return $query->with('city')->paginate($this->perPage);
    }
}
