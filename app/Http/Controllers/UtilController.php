<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\EventList;
use App\Model\EventType;
use App\Model\EventCheck;
use App\User;
use App\Model\State;
use App\Model\City;
use Carbon\Carbon;
use DB;

class UtilController extends Controller
{
    //
    public function getCities($state = null, City $cities)
    {
        if($state == null)
            return response()->json([
                'error' => 'Invalid state'
            ]) ;

        $json = json_encode($cities->where('state_uf', $state)->get());
        return response()->json([
            $json
        ]);
    }

    public function getMonitor(User $user, Request $request)
    {
        $json = ($user->where('name', 'LIKE', "%{$request->search}%")->get());
        
        $result = array();
        foreach($json as $j)
        {
            $result[] = ['value' => $j->name, 'data' => $j->id];
        }

        return response()->json([
            'suggestions' => $result
        ]);
    }

    public function totalVendidos(Request $request, EventCheck $check)
    {
        $result = array();

        $poss = $check  ->eventId($request->event_id)
                        ->where('sell', 1)
                        ->select('sell', DB::raw('count(*) as total'))
                        ->groupBy('sell')
                        ->get();
                        
        if($poss->count())
        {
            $totalPoss = $poss[0]->total;

            array_push($result, 
                [
                    0 => 'Possibilidades',
                    1 => $totalPoss
                ]
            );
        }

        $sold = $check->eventId($request->event_id)
                        ->where('sold', 1)
                        ->select('sold', DB::raw('count(*) as total'))
                        ->groupBy('sold')
                        ->get();

        if($sold->count())
        {
            array_push($result, 
                [
                    0 => 'Vendidos',
                    1 => $sold[0]->total
                ]
            );
        }

        return response()->json([
            'data' =>  $result
        ]);
    }

    public function graduadosContador(Request $request, EventCheck $check)
    {
        $counter = $check ->eventId($request->event_id)
                            ->with('graduate')
                            ->select('graduate_id', DB::raw('count(*) as total'))
                            ->groupBy('graduate_id')
                            ->get();

        $result = array();

        foreach($counter as $c) {
            $result[] = [
                $c->graduate->name,
                $c->total
            ];
        }

        return response()->json([
            'data' =>  $result
        ]);
    }

    public function listaVendas(Request $request, EventCheck $check)
    {
        $eventId = $request->event_id;

        $checkList = $check->eventId($eventId)
                            ->where('sell', 1)
                            ->get();
        
        $i = 1;
        $result = array();
        
        foreach($checkList as $r) {
            $result[] = [
                $i, 
                $r->user->name,
                $r->user->graduate->name,
                ($r->sold) ? 'Vendido' : 'Não vendido',
                ($r->invited != NULL) ? $r->invited->name : '-',
                ($r->user->user_id != null) ? $r->user->father->name : '-',
                $r->id
            ];

            $i++;
        }
        
        return response()->json([
            'data' =>  $result
        ]);
    }

    public function listaConvidados(Request $request, EventCheck $check)
    {
        $eventId = $request->event_id;

        $checkList = $check->eventId($eventId)->get();
        
        $i = 1;
        $result = array();
        foreach($checkList as $r) {
            $result[] = [
                $i, 
                $r->user->name,
                $r->user->graduate->name,
                $r->time_arrived->format('d/m/Y H:i'),
                ($r->invited != NULL) ? $r->invited->name : '-',
                ($r->user->user_id != null) ? $r->user->father->name : '-',
                $r->id
            ];

            $i++;
        }

        return response()->json([
            'data' =>  $result
        ]);
    }
}
