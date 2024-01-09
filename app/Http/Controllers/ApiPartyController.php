<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Validator\PartyValidation;
use App\Validator\ParticipantValidation;
use App\Models\Participant;
use App\Models\Party;
use App\Interfaces\IMail;

class ApiPartyController extends Controller
{
    private $_request;
    private $_timezone;
    private $_partyValidation;
    private $_participantValidation;
    private $_mail;

    public function __construct(Request $request, 
                                PartyValidation $partyValidation, 
                                ParticipantValidation $participantValidation, 
                                IMail $mail){
        $this->_request = $request;
        $this->_timezone = date_default_timezone_set('America/Fortaleza');
        $this->_partyValidation = $partyValidation;
        $this->_participantValidation = $participantValidation;
        $this->_mail = $mail;
    }

    public function create(){
        try{
            // Validations. //
            $party = $this->_request->only(['date', 'location', 'maximum_value', 'message']);
            $partyValidator = $this->_partyValidation->create($party);
            if($partyValidator){ 
                throw new \Exception(json_encode($partyValidator), 400);
            }
            $participants = $this->_request->input('participants');
            $participantValidator = $this->_participantValidation->create($participants);
            if($participantValidator){   
                throw new \Exception(json_encode($participantValidator), 400);   
            }
            // Database accesses. //
            $createParty = Party::create([
                'date' => $party['date'],
                'location' => $party['location'],
                'maximum_value' => $party['maximum_value'],
                'message' => $party['message']
            ]);
            $createParticipants = $createParty->participants()->createMany($participants);
            $partyOwner = $createParty->participants->first();
            // Organization of a data list. //
            $listOfParticipantsNames = $createParticipants->pluck('name')->all();    
            $listOfSecretFriendsNames = $listOfParticipantsNames;
            // Process to generate the secret friend for each participant and send emails. //
            foreach($listOfParticipantsNames as $index => $participantName){
                $participant = $createParty->participants[$index];
                // This should be the user's token.
                $userToken = \Str::random(60);
                do {
                    $randNumber = rand(0, count($listOfSecretFriendsNames) - 1);
                } while($participantName === $listOfSecretFriendsNames[$randNumber]);
                $update = $createParty->participants[$index]->update(['secret_santa' => $listOfSecretFriendsNames[$randNumber],
                                                                        'token' => $userToken]);
                $sendEmail = $this->_mail->sendPartyInformations($party, 
                                                                    $participant, 
                                                                    $listOfSecretFriendsNames[$randNumber], 
                                                                    $partyOwner['name'],
                                                                    $userToken);
                // Removes the name of the secret friend that has already been chosen.
                array_splice($listOfSecretFriendsNames, $randNumber, 1); 
            }
            return response()->json([
                'party' => $party,
                'participants' => $participants
            ], 200);
        }catch(\Exception $e){
            $errors = json_decode($e->getMessage());
            return response()->json([
                'messages' => $errors
            ], $e->getCode());
        }
    }
    
    public function index($token){
        try{ 
            $participant = Participant::where('token', $token)->first();
            if(!$participant){
                throw new \Exception('Participante não foi localizado.', 400);
            }
            return response()->json([
                'participant' => $participant,
                'wishes' => $participant->wishes
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
    
}