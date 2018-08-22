<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventValidation extends FormRequest
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
            'name'              => 'required|max:255|min:4',
            'eventtype_id'      => 'required|numeric',
            'data'              => 'required|date',
            'cities'            => 'required|exists:cities,code',
            'description'       => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required'             => 'O campo nome é obrigatório',
            'name.max'                  => 'O tamanho máximo para o nome do evento é de 255 caracteres',
            'name.min'                  => 'O tamanho mínimo para o nome do evento é de 4 caracteres',
            'eventtype_id.required'     => 'O tipo do evento é obrigatório',
            'eventtype_id.numeric'      => 'Preenchimento incorreto do tipo do evento',
            'data.required'             => 'O campo data é obrigatório',
            'data.date'                 => 'Data em formato incorreto',
            'cities.required'           => 'O campo Cidade é obrigatório',
            'cities.exists'             => 'Preenchimento da cidade é obrigatório',
            'description.required'      => 'O campo descrição é obrigatório'
        ];
    }
}
