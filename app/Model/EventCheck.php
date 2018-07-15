<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;
use DB;
use Carbon\Carbon;

class EventCheck extends Model
{
    //
    protected $dates = ['time_arrived'];
    protected $fillable = ['sold'];
    public $timestamps = false;
    public function event()
    {
        return $this->belongsTo(EventList::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invited()
    {
        return $this->belongsTo(User::class, 'invited_id', 'id');
    }

    public function graduate()
    {
        return $this->belongsTo(Graduate::class, 'graduate_id', 'id');
    }

    // ----------------
    // Scopes 
    // ----------------
    public function scopeEventId($query, $eventId)
    {
        return $query->where('eventlist_id', $eventId);
    }

    public function scopeNetwork($query, $network)
    {
        return $query->whereIn('user_id', $network);
    }

    public function scopeSell($query, $sell)
    {
        return $query->where('sell', $sell);
    }
    
    public function scopeSold($query, $sold)
    {
        return $query->where('sold', $sold);
    }

    public function removeCheck($id)
    {
        DB::beginTransaction();

        $result = DB::table('event_checks')
                        ->where('id', $id)
                        ->delete();
            
        DB::commit();
    }

    public function sold($id, $type)
    {
        $info = $this->where('id', $id)->get()->first();
        if($info->sell == 0)
            return [
                'error' => 1,
                'msg'   => 'Este usuário não está marcado como possibilidade de venda'
            ];
        
        if($info->sold == $type)  
            return [
                'error' => 1,
                'msg'   => 'Este usuário já está marcado como vendido'
            ];
            
        if($info->sold == $type)  
            return [
                'error' => 1,
                'msg'   => 'Este usuário já está marcado como não vendido'
            ];
        
        $this
            ->where('id', $id)
            ->update(['sold' => $type]);

        return  [
            'error' => -1
        ];
    }

    public function doCheck($userId, $invitedId, $eventId, $sell)
    {
        // Instancia a classe User
        $user       = new User;
        $event      = new EventList;

        // Pegamos as informações da DB respectiva dos usuários
        $pai        = $user->getSender($userId);
        $invited    = ($invitedId) 
                        ? $user->getSender($invitedId) 
                        : null;
        
        if($pai === null)
            return array('error' => 1, 'errorMsg' => 'Informe um convidado corretamente');
        
        if(!$event->where('id', $eventId)->exists())
            return array('error' => 1, 'errorMsg' => 'Este evento não existe. Favor recarregar a página ou voltar para a lista de eventos!');

        $result = $this->where('user_id', $userId)
                       ->eventId($eventId)
                       ->exists();

        if($result) 
            return array('error' => 1, 'errorMsg' => 'Este usuário já está na lista de convidados');

        DB::beginTransaction();

        DB::table('event_checks')->insert([
            'user_id'       => $pai->id, 
            'invited_id'    => ($invited != null) ? $invited->id : NULL, 
            'eventlist_id'  => $eventId,
            'time_arrived'  => DB::raw('now()'),
            'graduate_id'   => $pai->graduate_id,
            'sell'          => $sell,
            'sold'          => 0
            ]
        );

        $result = array(
            'checkin' => [
                'user_id'          => $pai->name,
                'type'             => $pai->graduate->name,
                'invited'          => ($invited != null) ? $invited->name : '-',
                'nomeEquipe'       => ($pai->user_id) ? $pai->father->name : '-',
                'time_arrived'     => now()->format('d/m/Y H:i')
            ],
            'error'                => -1,
            'successMsg'           => "Checkin feito com sucesso para o jogador {$pai->name}");

        DB::commit();
        return $result;
    }
}
