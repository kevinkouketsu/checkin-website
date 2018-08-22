<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Model\Graduate;
use App\Model\UserAddress;
use App\Model\EventList;
use DB;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'graduate_id', 'username', 'rede_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // -------------------------
    // Relationship
    // -------------------------
    public function graduate()
    {
        return $this->belongsTo(Graduate::class);
    }

    public function addresses()
    {
        return $this->hasOne(UserAddress::class, 'user_id', 'id');  
    }

    public function network()
    {
        return $this->hasMany(User::class);
    }

    public function events()
    {
        return $this->hasMany(EventList::class);
    }

    public function father()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // ------------------------
    // Scopes
    // ------------------------
    public function scopeId($query, $id)
    {
        return $query->where('id', $id);
    }

    public function scopeUsername($query, $username)
    {
        return $query->where('username', $username);
    }

    // ------------------------
    // Get funcs
    // ------------------------
    
    public function getSender(int $sender)
    {
        return $this->where('id', $sender)
             ->get()
             ->first();
    }

    public function getTotalUsers()
    {
        $baseNetwork = DB::where('rede_id', auth()->user()->id)->get();
    }

    public function getNetwork(int $user_id) : array
    {
        $result = User::id($user_id)->get();

        $finalResult = $result->all();
        while(true)
        {
            $new = null;

            foreach($result as $u)
            {
                $network = $u->network;

                foreach($network as $n)
                {
                    $finalResult [] = $n;

                    $new[] = $n;
                }
            }

            $result = $new;

            if(empty($new))
                break;
        }

        return $finalResult;
    }

    public function getNetworkId(int $user_id) : array
    {
        $result = User::id($user_id)->get();

        $r = array();
        $count = 0;
        while(true)
        {
            $count ++;
            $new = null;

            foreach($result as &$u)
            {
                $network = $u->network;

                foreach($network as $n)
                {
                    array_push($r, array('user_id' => $n->id, 'nivel' => $count));

                    $new[] = $n;
                }
            }

            $result = $new;

            if(empty($new))
                break;
        }

        return $r;
    }
    
    public function generateUsername($username)
    {
        $username_parts = array_filter(explode(" ", strtolower($username))); //explode and lowercase name
        $username_parts = array_slice($username_parts, 0, 2); //return only first two arry part

        $part1 = (!empty($username_parts[0]))?substr($username_parts[0], 0,8):""; //cut first name to 8 letters
        $part2 = (!empty($username_parts[1]))?substr($username_parts[1], 0,5):""; //cut second name to 5 letters
        $part3 = ($rand_no)?rand(0, $rand_no):"";

        $username = $part1. $part2. $part3; //str_shuffle to randomly shuffle all characters 
    }

    public function completeRegister($data, $user_address)
    {
        $username = kebab_case($data->name);

        while($this->username($username)->count())
            $username = $this->generateUserName($data->name);
           
        DB::beginTransaction();

        $user = $this->insertGetId ([
            'name' => title_case($data->name),
            'username' => $username,
            'email' => NULL,
            'password' => NULL,
            'graduate_id' => $data->graduation
        ]);

        if(!$user)
        {
            DB::rollBack();

            return false;
        }

        $resultAddress = $user_address->updateAddress($user, $data);
        if($resultAddress)
        { 
            DB::commit();
            return $user;
        }

        return false;
    }

    public function register($data)
    {
        $username = kebab_case($data->name);

        while($this->username($username)->count())
            $username = $this->generateUserName($data->name);
        
        return $this->insert([
            'name' => title_case($data->name),
            'username' => $username,
            'email' => NULL,
            'password' => NULL,
            'graduate_id' => $data->graduation
        ]);
    }

}