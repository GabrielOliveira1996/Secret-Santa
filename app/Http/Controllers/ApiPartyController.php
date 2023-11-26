<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Validator\PartyValidation;
use App\Validator\ParticipantValidation;
use App\Repository\Party\IPartyRepository;
use App\Providers\Mail;

class ApiPartyController extends Controller
{
    private $_request;
    private $_partyValidation;
    private $_participantValidation;
    private $_partyRepository;
    private $_mail;

    public function __construct(Request $request, 
                                PartyValidation $partyValidation, 
                                ParticipantValidation $participantValidation, 
                                IPartyRepository $partyRepository,
                                Mail $mail){
        $this->_request = $request;
        $this->_partyValidation = $partyValidation;
        $this->_partyRepository = $partyRepository;
        $this->_participantValidation = $participantValidation;
        $this->_mail = $mail;
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
            $createParty = $this->_partyRepository->create($party);
            $createParticipant = $createParty->participants()->createMany($participants);
            $sendEmail = $this->_mail->send($participants, $party);
            return response()->json([
                'success' => true,
                'party' => $party,
                'participants' => $participants
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
