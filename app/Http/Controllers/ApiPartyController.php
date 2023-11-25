<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Validator\PartyValidation;
use App\Repository\Party\IPartyRepository;

class ApiPartyController extends Controller
{
    private $_request;
    private $_partyValidation;
    private $_partyRepository;

    public function __construct(Request $request, PartyValidation $partyValidation, IPartyRepository $partyRepository){
        $this->_request = $request;
        $this->_partyValidation = $partyValidation;
        $this->_partyRepository = $partyRepository;
    }

    public function create(){
        try{
            $party = $this->_request->only([
                'date',
                'location',
                'maximum_value',
                'message'
            ]);
            $validator = $this->_partyValidation->create($party);
            if($validator){
                throw new \Exception(json_encode($validator->messages()));
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
