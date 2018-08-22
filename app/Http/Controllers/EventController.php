<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\EventList;
use App\Model\EventType;
use App\Model\EventCheck;
use App\Model\State;
use App\Model\Graduate;
use App\User;

use App\Http\Requests\StoreEventValidation;
use App\Http\Requests\EventTypeValidation;

use Illuminate\Support\Facades\Cache;
use DB;
use Validator;
use Carbon\Carbon;

class EventController extends Controller
{
    //    
    public function eventListFilter(EventList $event, EventType $eventsType, Carbon $date, State $states, Request $request)
    {
        $eventList = $event->getEventList(auth()->user()->id, $request);
           
        // get all avaiable states
        $state = $states->all();

        // dataForm to append on pagination links 
        $dataForm = $request->except('_token');
        $eventType = Cache::remember('eventsType', 5, function () use ($eventsType) {
            return $eventsType
                        // Especifiques as colunas que irá utilizar na view
                        // descartando todo o resto.
                        // Me parece que aqui você pode utilizar cache, p.x:
                        // https://laravel.com/docs/5.6/cache
                        ->all();
        });

        $request->flash();
        return view('events.list', compact('eventList', 'eventType', 'date', 'dataForm', 'state'));
    }

    public function editTypes(Request $request, EventType $type)
    {
        $validator = Validator::make(['id' => $request->edit_type, 'name' => $request->edit_name], [
                'name'              => 'required|min:4|unique:event_types,name',
                'id'                => 'required|exists:event_types,id'],
            [
                'name.required'     => 'O campo nome é obrigatório',
                'name.min'          => 'O tamanho mínimo para o nome do evento é de 4 caracteres',
                'name.unique'       => 'O nome já está sendo utilizado',
                'id.required'       => 'O tipo de evento é inválido',
                'id.exists'         => 'Este tipo de evento não existe'
            ]
        )->validate();
        
        //
        if($type->updateType($request->edit_type, $request->edit_name))
            return response()->json(['error' => -1, 'msg' => 'Atualizado com sucesso']);
        
        return response()->json(['error' => 1, 'msg' => 'Falha ao atualizar']);
    }

    public function newTypes(EventTypeValidation $request, EventType $type) 
    {
        if($type->new($request->name))
            $success = array('msg' => 'Tipo criado com sucesso');

        return redirect(route('eventos.tipos'))->with('success', 'Criado com sucesso');
    }

    public function getTypes(EventType $type)
    {
        return response()->json(['info' => $type]);
    }

    public function types(EventType $type)
    {
        $type = $type->paginate(15);

        return view('events.types', compact('type'));
    }

    public function deletEvent(Request $request, EventList $list)
    {
        if($list->deletEvent($request->event_id))
            return response()->json(['error' => -1, 'url' => route('eventos.listar')]);

        return response()->json(['error' => 1]);
    }

    public function eventView($name, $id, Graduate $graduate)
    {
        // initialize object EventList 
        $event = new EventList;

        if(!$event->event($id)->exists())
            return redirect(route('eventos.listar'));
            
        // get eventInfo
        $eventInfo = $event->getEventInfo($id);

        $graduate = $graduate->get();
        return view('events.view', compact('eventInfo', 'graduate'));
    }

    public function createEvent(EventType $type, State $states)
    {
        $eventType = $type->all();
        $state = $states->all();

        return view('events.create', compact('state', 'eventType'));
    }

    public function createEventStore(StoreEventValidation $request, EventList $list)
    {
        $result = $list->insertEvent($request->except('_token', 'states'));

        if($result) {
            $eventInfo = $list->getEventInfo($result->id);
    
            return redirect(route('eventos.visualizar', ['nome'=> kebab_case ($eventInfo->name), 'id'=>$eventInfo->id]));
        }
        else    
            return redirect()
                    ->back();   
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
