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
        try{
            $rules = [
                'date' => 'required|date',
                'location' => 'required|string',
                'maximum_value' => 'required|numeric',
                'message' => 'max:200',
                'token' => 'required|min:60|unique:participants'
            ];
            $messages = [
                'date.required' => 'Necessário escrever a data da festa.',
                'date.date' => 'Valor precisa ser uma data.',
                'location.required' => 'Necessário escrever o local da festa.',
                'location.string' => 'Valor precisa conter apenas letras.',
                'maximum_value.required' => 'Necessário que seja passado o valor máximo do presente.',
                'maximum_value.numeric' => 'Valor deve conter números.',
                'message.max' => 'Mensagem deve conter no máximo 200 caracteres.',
                'token.required' => 'Necessário passar o token da festa.',
                'token.min' => 'O token deve ter pelo menos 60 caracteres.',
                'token.unique' => 'Erro ao gerar token, token deve ser único para festa.'
            ];
            $validation = Validator::make($party, $rules, $messages);
            if($validation->fails()){
                throw new ValidationException($validation);
            }
        }catch(ValidationException $e){
            $errors = $e->validator->messages()->toArray();
            return response()->json([
                'status' => 'error',
                'messages' => $errors
            ], 422);
        }
    }
}