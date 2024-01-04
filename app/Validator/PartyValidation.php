<?php

namespace App\Validator;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PartyValidation{
    private $_validator;

    public function __construct(Validator $validator){
        $this->_validator = $validator;
    }

    public function create($party){
        $rules = [
            'date' => 'required|date',
            'location' => 'required|string',
            'maximum_value' => 'required|numeric|min:1',
            'message' => 'max:200'
        ];
        $messages = [
            'date.required' => 'Preencha a data da festa.',
            'date.date' => 'Preencha a data corretamente.',
            'location.required' => 'Preencha o local da festa.',
            'location.string' => 'Preencha o local da festa corretamente. Deve ter apenas letras.',
            'maximum_value.required' => 'Preencha o valor máximo do presente.',
            'maximum_value.numeric' => 'Valor do presente deve conter apenas números.',
            'maximum_value.min' => 'Valor do presente precisa ser maior que 0.',
            'message.max' => 'Mensagem deve conter no máximo 200 caracteres.'
        ];
        $validation = Validator::make($party, $rules, $messages);
        if($validation->fails()){
            return $validation->errors();
        }
    }
}