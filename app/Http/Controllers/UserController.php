<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Graduate;
use App\Model\State;
use App\Model\UserAddress;
use App\Model\EventType;
use App\User;
use Carbon\Carbon;
use App\Model\EventList;

use Illuminate\Support\Facades\Cache;
use App\Http\Requests\RegisterUserValidation;

use Validator;

class UserController extends Controller
{
    //
    public function cadastrar(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|min:4',
            'pai_id_cadastra'   => 'required|numeric|exists:users,id',
            'graduation'          => 'required|numeric|exists:graduates,id'
        ],
        [
            'name.required'             => 'O campo Nome é obrigatório',
            'name.min'                  => 'O tamanho mínimo para o campo nome é de 4 caracteres',
            'pai_id_cadastra.required'  => 'O campo pai é obrigatório',
            'pai_id_cadastra.exists'    => 'O campo pai está inválido',
            'graduation.required'         => 'O campo graduação é obrigatório',
            'graduation.numeric'          => 'O campo graduação está inválido',
            'graduation.exists'           => 'O campo graduação está inválido'
        ]
        );

        if($validator->passes())
        {
            $success = $user->register($request);

            if($success) 
                return response()->json(['success' => 1]);
        
            return response()->json(['error' => ['msg' => 'Erro no cadastro']]);
        }
        
        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function viewProfile($name, Request $request, EventList $event, User $user, Carbon $date, State $states, EventType $eventsType)
    {
        $profile = $user->username($name)->with('addresses')->get()->first();
        $eventList = $event->getEventList($profile->id, $request);
           
        // get all avaiable states
        $state = $states->all();

        $eventType = Cache::remember('eventsType', 5, function () use ($eventsType) {
            return $eventsType
                        ->all();
        });

        $request->flash();
        return view('usuario.perfil', compact('eventList', 'profile', 'date', 'state', 'eventType'));
    }
    public function listStaff(User $user)
    {
        $staff = $user->getNetwork(auth()->user()->id);

        return view('usuario.listar', compact('staff'));
    }

    public function cadastrarFull(RegisterUserValidation $request, User $user, UserAddress $user_address)
    {
        $user->completeRegister($request, $user_address);
    }

    public function cadastrarView(Graduate $graduate, State $states)
    {
        $graduation = Cache::remember('graduation', 5, function () use ($graduate) {
            return $graduate
                        ->get();
        });

        $state = $states->get();

        return view('usuario.cadastrar', compact('graduation', 'eventType', 'state'));
    }
}
