<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Validator\PartyValidation;
use App\Validator\ParticipantValidation;
use App\Repository\Party\IPartyRepository;

class ApiPartyController extends Controller
{
    private $_request;
    private $_partyValidation;
    private $_participantValidation;
    private $_partyRepository;

    public function __construct(Request $request, 
                                PartyValidation $partyValidation, 
                                ParticipantValidation $participantValidation, 
                                IPartyRepository $partyRepository){
        $this->_request = $request;
        $this->_partyValidation = $partyValidation;
        $this->_partyRepository = $partyRepository;
        $this->_participantValidation = $participantValidation;
    }

    public function create(){
        try{
            $party = $this->_request->only(['date', 'location', 'maximum_value', 'message']);
            $partyValidator = $this->_partyValidation->create($party);
            if($partyValidator){
                throw new \Exception(json_encode($partyValidator->messages()));
            }
            $participants = $this->_request->input('participants');
            $participantValidator = $this->_participantValidation->create($participants);
            if($participantValidator){
                throw new \Exception(json_encode($participantValidator->messages()));      
            }
            $create = $this->_partyRepository->create($party);
            return response()->json([
                'success' => true,
                'party_info' => $party
            ], 200);
        }catch(\Exception $e){
            $errors = json_decode($e->getMessage());
            return response()->json([
                'success' => false,
                'errors' => $errors
            ], 200);
        }
    }
}
