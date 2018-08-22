<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\EventList;
use App\Model\EventType;
use App\Model\EventCheck;
use App\User;
use App\Model\State;
use Carbon\Carbon;
use DB;

class ReportController extends Controller
{
    //
    public function staffOnEventGraph(Request $request, EventCheck $check, User $user)
    {
        $pai_id = $request->pai_id_staff;
        $eventId = $request->event_id;

        if($request->type == 1)
        {
            $pai = $user->id($pai_id)->get()->first();

            $network = $user->getNetworkId($pai_id);
            
            $checkList  = $check->eventId($eventId)
                                ->get();
            
            $staff      = $check->eventId($eventId)
                                ->network(array_column($network, 'user_id'))
                                ->get();
                                
            $result = array();
        
            array_push($result, [   0 => 'Convidados fora da equipe',
                                    1 => $checkList->count() - count($staff) - 1    ]
                        );

            array_push($result, [   0 => "Convidados de {$pai->name}",
                                    1 => count($staff)
                                ]
                        );

            return response()->json([
                'data' => $result
            ]);
        }
        else if($request->type == 2)
        {
            $network = $user->getNetworkId($pai_id);
            
            $sell = $check->eventId($eventId)
                            ->sell(1)
                            ->sold(0)
                            ->network(array_column($network, 'user_id'))
                            ->get();
                            
            $sold = $check->eventId($eventId)
                            ->sold(1)
                            ->network(array_column($network, 'user_id'))
                            ->get();
            
            $result = array(
                    [
                        0       => 'Possibilidades',
                        1       => $sell->count()
                    ],
                    [
                        0       => 'Vendas',
                        1       => $sold->count()
                    ]);

            
            return response()->json([
                'data' =>  $result
            ]);
        }
    }

    public function staffOnEventList(Request $request, EventCheck $check, User $user)
    {
        $pai        = $request->pai_id_staff;
        $eventId    = $request->event_id;

        $network    = $user->getNetworkId($pai);
        $checkList  = $check->eventId($eventId)
                            ->network(array_column($network, 'user_id'))
                            ->get();
        $i          = 1;
        $result     = array();
        
        foreach($checkList as $r) {
            $result[] = [
                $i, 
                $r->user->name,
                $r->user->graduate->name,
                $r->time_arrived->format('d/m/Y H:i'),
                ($r->invited != NULL) ? $r->invited->name : '-',
                ($r->user->user_id != null) ? $r->user->father->name : '-',
            ];

            $i++;
        }

        return response()->json([
            'data' =>  $result
        ]);
    }
}
