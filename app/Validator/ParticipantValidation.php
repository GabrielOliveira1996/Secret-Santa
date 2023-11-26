<?php

namespace App\Validator;

use Illuminate\Support\Facades\Validator;

class ParticipantValidation{
    private $_validator;

    public function __construct(Validator $validator){
        $this->_validator = $validator;
    }

    public function create($participants){
        $rules = [
            'participants' => 'required|array|min:2',
            'participants.*.name' => 'required',
            'participants.*.email' => 'required'
        ];
        $messages = [
            'participants.required' => 'Necessário escrever as informações de todos os participantes.',
            'participants.min' => 'Necessário que tenha pelo menos 2 participantes.',
            'participants.*.name' => 'Necessário passar o nome do participante.',
            'participants.*.email' => 'Necessário passar o email do participante.'
        ];
        $validation = Validator::make(['participants' => $participants], $rules, $messages);
        if($validation->fails()){
            return $validation->messages();
        }
    }
}