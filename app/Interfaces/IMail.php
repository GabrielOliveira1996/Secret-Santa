<?php

namespace App\Interfaces;

interface IMail{
    public function sendPartyInformations($party, $participant, $secretSanta, $partyOwner, $userToken);
    public function sendWishList($participantName, $secretSantaEmail, $participantToken);
}