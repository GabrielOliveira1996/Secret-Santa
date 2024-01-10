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
            'participants.array' => 'Necessário que os participantes sejam passados dentro de array.',
            'participants.min' => 'Necessário que tenha pelo menos 2 participantes.',
            'participants.*.name.required' => 'Necessário passar o nome do participante.',
            'participants.*.email.required' => 'Necessário passar o email do participante.'
        ];
        $validation = Validator::make(['participants' => $participants], $rules, $messages);
        if($validation->fails()){
            return $validation->errors();
        }
    }

    public function thereAreParticipantsWithTheSameName($participants){
        $uniqueNames = [];
        $duplicateNames = [];
        foreach ($participants as $participant) {
            $name = $participant['name'];
            if (in_array($name, $uniqueNames)) {
                // Duplicate name found. //
                $duplicateNames[] = $name;
            } else {
                // Unique name, add to the list of unique names. //
                $uniqueNames[] = $name;
            }
        }
        if (!empty($duplicateNames)) {
            // There are duplicate names. //
            return ["participant" => "Existem nomes iguais em lista de participantes."];
        } else {
            // There are no duplicate names. //
            return null;
        }
    }
}