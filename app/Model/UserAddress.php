<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\City;

class UserAddress extends Model
{
    //
    protected $fillable = ['user_id', 'address', 'street', 'complement', 'number', 'city_code'];
    public $timestamps = false;
    public function user()
    {
        return $this->belongsTo(User::class);
    }   

    public function city()
    {
        return $this->belongsTo(City::class, 'city_code', 'code');
    }

    public function updateAddress($user_id, $data)
    {
        return $this->updateOrCreate([
            'user_id'   => $user_id,
            'address'   => $data->address,
            'street'    => $data->neighborhood,
            'number'    => $data->number,
            'city_code' => $data->cities,
            'complement'=> $data->complement
        ]);
    }

}
