<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserValidation extends FormRequest
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
            'email'             => 'nullable|email',
            'date'              => 'nullable|date',
            'graduation'        => 'exists:graduates,id',
            'cities'            => 'exists:cities,code',
            'neighborhood'      => 'max:255',
            'address'           => 'max:255',
            'number'            => 'nullable|numeric',
            'complement'        => 'max:255'
        ];
    }

    public function messages()
    {
        return [
            'name.required'             => 'O campo nome é obrigatório',
            'name.max'                  => 'O tamanho máximo para o nome do evento é de 255 caracteres',
            'name.min'                  => 'O tamanho mínimo para o nome do evento é de 4 caracteres',
            'graduation.exists'         => 'O campo graduação está inválido',
            'date.date'                 => 'Data em formato incorreto',
            'cities.exists'             => 'Preenchimento da cidade é obrigatório',
            'email.email'               => 'Digite um e-mail válido',
            'neihgborhood.max'          => 'O tamanho máximo para o endereço é de 255 caracteres',
            'address.max'               => 'O tamanho máximo para o bairro é de 255 caracteres',
            'number.numeric'            => 'Somente números no campo Número',
            'complement.max'            => 'O tamanho máximo para o complemento é de 255 caracteres'
        ];
    }
}
