<?php

namespace App\Repository\Party;

use App\Models\Party;
use App\Repository\Party\IPartyRepository;

class PartyRepository implements IPartyRepository{
    private $_party;

    public function __construct(Party $party){
        $this->_party = $party;
    }

    public function create($party) :Party { 
        return $this->_party->create([
            'date' => $party['date'],
            'location' => $party['location'],
            'maximum_value' => $party['maximum_value'],
            'message' => $party['message'],
            'token' => $party['token']
        ]);
    }
}