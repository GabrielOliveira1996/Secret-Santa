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
    private $_timezone;
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
        $this->_timezone = date_default_timezone_set('America/Fortaleza');
        $this->_partyValidation = $partyValidation;
        $this->_partyRepository = $partyRepository;
        $this->_participantValidation = $participantValidation;
        $this->_mail = $mail;
    }

    public function create(){
        try{
            $party = $this->_request->only(['date', 'location', 'maximum_value', 'message']);
            $participants = $this->_request->input('participants');
            $partyToken = $party['token'] = \Str::random(60);
            // Validations. //
            $partyValidator = $this->_partyValidation->create($party);
            if($partyValidator){ 
                throw new \Exception(json_encode($partyValidator));
            }
            $participantValidator = $this->_participantValidation->create($participants);
            if($participantValidator){   
                throw new \Exception(json_encode($participantValidator));   
            }
            // Database accesses. //
            $createParty = $this->_partyRepository->create($party);
            $createParticipant = $createParty->participants()->createMany($participants);
            $partyOwner = $createParty->participants->first();
            $updatePartyOwner = $partyOwner->update(['party_owner' => true]);
            // Organization of a data list. //
            $listOfParticipantsNames = $createParticipant->pluck('name')->all();    
            $secondaryListOfParticipantsNames = $listOfParticipantsNames;
            // Process to generate the secret friend for each participant and send emails. //
            foreach($listOfParticipantsNames as $index => $participantName){
                $participant = $createParty->participants[$index];
                do {
                    $randNumber = rand(0, count($secondaryListOfParticipantsNames) - 1);
                } while($participantName === $secondaryListOfParticipantsNames[$randNumber]);
                $update = $createParty->participants[$index]->update(['secret_santa' => $secondaryListOfParticipantsNames[$randNumber]]);
                $sendEmail = $this->_mail->send($party, $participant, $secondaryListOfParticipantsNames[$randNumber], $partyOwner['name']);
                array_splice($secondaryListOfParticipantsNames, $randNumber, 1);
            }
            return response()->json([
                'status' => 'success',
                'party' => $party,
                'participants' => $participants
            ], 200);
        }catch(\Exception $e){
            $errors = json_decode($e->getMessage());
            return response()->json([
                'status' => 'error',
                'messages' => $errors
            ], 200);
        }
    }
}
