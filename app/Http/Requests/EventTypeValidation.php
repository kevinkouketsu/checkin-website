<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventTypeValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'name'      => 'required|min:4|unique:event_types,name'
        ];
    }
    
    public function messages()
    {
        return [
            'name.required'     => 'O campo nome é obrigatório',
            'name.min'          => 'O tamanho mínimo para o nome do evento é de 4 caracteres',
            'name.unique'       => 'O nome já está sendo utilizado'
        ];
    }
}
