<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\EventList;
use App\Model\EventType;
use App\Model\EventCheck;
use App\User;
use App\Model\State;
use Carbon\Carbon;
use App\Model\Graduate;
use DB;
use Validator;
use App\Http\Requests\StoreEventValidation;

class EventController extends Controller
{
    //
    public function eventListFilter(Request $request, EventList $event)
    {
        $eventList = $event->getEventList(auth()->user()->id, $request);
           
        $eventType = EventType::all();
        $state = State::all();
        $date = Carbon::now();
        
        // set 'old' value to view
        $request->flash();

        // dataForm to append on pagination links 
        $dataForm = $request->except('_token');

        return view('events.list', compact('eventList', 'eventType', 'date', 'dataForm', 'state'));
    }

    public function editTypes($id, Request $request, EventType $type)
    {
        dd($request->all());
        $validator = Validator::make(['id' => $id, 'name' => $name], [
            'name'              => 'required|min:4',
        ]);
    }

    public function editGet($id, EventType $type)
    {
        $info = $type->id($id)->get()->first();

        return response()->json(['info' => $info]);
    }

    public function types()
    {
        $type = EventType::all();

        return view('events.types', compact('type'));
    }

    public function deletEvent(Request $request, EventList $list)
    {
        if($list->deletEvent($request->event_id))
            return response()->json(['error' => -1, 'url' => route('eventos.listar')]);

        return response()->json(['error' => 1]);
    }

    public function eventView($name, $id)
    {
        // initialize object EventList 
        $event = new EventList;

        if(!$event->event($id)->exists())
            return redirect(route('eventos.listar'));
            
        // get eventInfo
        $eventInfo = $event->getEventInfo($id);

        $graduate = Graduate::all();
        return view('events.view', compact('eventInfo', 'graduate'));
    }

    public function createEvent()
    {
        $eventType = EventType::all();
        $state = State::all();

        return view('events.create', compact('state', 'eventType'));
    }

    public function createEventStore(StoreEventValidation $request, EventList $list)
    {
        $result = $list->insertEvent($request->except('_token', 'states'));

        if($result) {
            // initialize object EventList 
            $event = new EventList;
    
            $eventInfo = $event->getEventInfo($result->id);
    
            return redirect(route('eventos.visualizar', ['nome'=> kebab_case ($eventInfo->name), 'id'=>$eventInfo->id]));
        }
        else {
            
        return redirect()
                ->back();   
        }
    }

    public function doCheckin(Request $request, User $user, EventCheck $check)
    {
        // setamos as variáveis 
        $eventId    = $request->event_id;
        $userId     = $request->pai_id;
        $invitedId  = $request->convidado_id;
        $sell       = $request->sell;

        // chamamos a função responsável pelo checkin
        // e retornamos o valor para retn
        $retn = $check->doCheck($userId,$invitedId, $eventId, $sell);
        
        // Retornamos uma ARRAY e ess array é convertida
        // para json 
        return response()->json(json_encode($retn));
    }

    public function sold(Request $request, EventCheck $check)
    {
        $id     = $request->id;
        $type   = $request->type;

        $retn = $check->sold($id, $type);

        return response()->json(json_encode(
            $retn
        ));
    }

    public function removeCheckin(Request $request, EventCheck $check)
    {
        $id     = $request->id;

        $retn = $check->removeCheck($id);

        return response()->json(json_encode(
            ['error' => -1,
            'msg' => 'ok']
        ));
    }
}
