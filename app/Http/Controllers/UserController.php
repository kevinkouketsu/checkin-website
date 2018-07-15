<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;

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
        else
        {
            return response()->json(['error'=>$validator->errors()->all()]);
        }
    }
}
