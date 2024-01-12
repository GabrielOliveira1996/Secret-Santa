<?php

namespace App\Mail;

use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Model\SendSmtpEmail;
use SendinBlue\Client\ApiException;
use App\Interfaces\IMail;

// Brevo is the old Sendinblue. //
class Brevo implements IMail{
    // Constants. //
    const WISHES_URL_BASE = 'http://localhost:5173/wishlist/';
    const COMPANY_NAME = 'Secret Santa';
    const SUPPORT_EMAIL = 'suporte.amigo.secreto2023@gmail.com';
    const TEMPLATE_WITH_PARTY_INFORMATION = 3;
    const TEMPLATE_WITH_SECRET_SANTA_WISH_LIST = 4;

    public function __construct(){
        $this->_sendinblueApiKey = env('SENDINBLUE_API_KEY');
        $this->_configuration = (new Configuration)->getDefaultConfiguration()->setApiKey('api-key', $this->_sendinblueApiKey);
        $this->_sendSmtpEmail = new SendSmtpEmail();
        $this->_apiInstance = new TransactionalEmailsApi(new \GuzzleHttp\Client(), $this->_configuration);
    }

    public function sendPartyInformations($party, $participant, $secretSanta, $partyOwner, $userToken){
        try{
            $date = new \DateTime($party['date']);
            $sendSmtpEmail = new $this->_sendSmtpEmail([
                'sender' => ['name' => self::COMPANY_NAME, 'email' => self::SUPPORT_EMAIL],
                'to' => [['email' => $participant['email']]],
                'params' => [
                    'participant_name' => $participant['name'], // Participant Name.
                    'party_host' => $partyOwner, // Party Owner.
                    'date' => $date->format('d/m/Y'), // Party Date.
                    'location' => $party['location'], // Party Location.
                    'maximum_value' => $party['maximum_value'], // Maximum Gift Value.
                    'message' => $party['message'], // Message.
                    'secret_santa' => $secretSanta, // Secret Santa.
                    'token' => self::WISHES_URL_BASE . "$userToken",
                ],
                'subject' => 'Iniciando o amigo secreto.',
                'templateId' => self::TEMPLATE_WITH_PARTY_INFORMATION,
            ]);
            $result = $this->_apiInstance->sendTransacEmail($sendSmtpEmail);
            return response()->json(['message' => 'E-mails enviados com sucesso.'], 200);
        }catch(ApiException $e){
            return response()->json([
                'message' => 'Houve um problema no envio do e-mail. Tente novamente, caso o erro persista entre em contato com o nosso suporte.'
            ], $e->getCode());
        }
    }

    public function sendWishList($secretSantaName, $secretSantaEmail, $participantToken){
        try{
            $sendSmtpEmail = new $this->_sendSmtpEmail([
                'sender' => ['name' => self::COMPANY_NAME, 'email' => self::SUPPORT_EMAIL],
                'to' => [['email' => $secretSantaEmail]],
                'params' => [
                    'participant_name' => $secretSantaName,
                    'token' => self::WISHES_URL_BASE . "$participantToken", // Secret Santa wishlist url
                ],
                'subject' => 'Lista de desejos do seu amigo',
                'templateId' => self::TEMPLATE_WITH_SECRET_SANTA_WISH_LIST,
            ]);
            $result = $this->_apiInstance->sendTransacEmail($sendSmtpEmail);
            return response()->json(['message' => 'E-mail enviados com sucesso.'], 200);
        }catch(ApiException $e){
            return response()->json([
                'message' => 'Houve um problema no envio do e-mail. Nossa equipe foi notificada e está trabalhando para resolver o problema.'
            ], $e->getCode());
        }
    }
}