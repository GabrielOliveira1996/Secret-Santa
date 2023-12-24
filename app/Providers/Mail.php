<?php

namespace App\Providers;

use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Model\SendSmtpEmail;

class Mail{
    private $_configuration;
    private $_transictionalEmailsApi;
    private $_sendSmtpEmail;

    public function __construct(Configuration $configuration, 
                                TransactionalEmailsApi $transictionalEmailsApi,
                                SendSmtpEmail $sendSmtpEmail){
        $this->_configuration = $configuration;
        $this->_transictionalEmailsApi = $transictionalEmailsApi;
        $this->_sendSmtpEmail = $sendSmtpEmail;
    }

    public function send($party, $participant, $secretSanta, $partyOwner){
        try{
            $this->validateParticipant($participant);
            
            $sendinblueApiKey = env('SENDINBLUE_API_KEY');
            $configuration = $this->_configuration->getDefaultConfiguration()->setApiKey('api-key', $sendinblueApiKey);
            $apiInstance = new $this->_transictionalEmailsApi(new \GuzzleHttp\Client(), $configuration);
            $date = explode('/', $party['date']);
            $sendSmtpEmail = new $this->_sendSmtpEmail([
                'sender' => ['name' => 'Amigo Secreto', 'email' => 'suporte.amigo.secreto2023@gmail.com'],
                'to' => [['email' => $participant['email']]],
                'params' => [
                    'participant_name' => $participant['name'], // Nome do participante.
                    'party_host' => $partyOwner, // Anfitrião.
                    'date' => $date[2] . "/" . $date[1] . "/" . $date[0], // Data da festa.
                    'location' => $party['location'], // Local da festa.
                    'maximum_value' => $party['maximum_value'], // Valor máximo de presente.
                    'message' => $party['message'], // Messagem.
                    'secret_santa' => $secretSanta // Amigo secreto.
                ],
                'subject' => 'Amigo Secreto.',
                'templateId' => 3,
            ]);
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            return response()->json(['status' => 'success', 'message' => 'E-mails enviados com sucesso.']);
        }catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }catch(\SendinBlue\Client\ApiException $e){
            return $this->apiException($e);
        }
    }

    private function validateParticipant($participant) {
        if (empty($participant)) {
            throw new \Exception('Os participantes não foram fornecidos para o envio do e-mail.');
        }
    }

    private function apiException(\SendinBlue\Client\ApiException $e) {
        if ($e->getCode() === 401) {
            return response()->json([
                'status' => 'error',
                'messageToDeveloper' => 'Erro na requisição da API Brevo. Código do erro 401. Acesso não foi autorizado.',
                'message' => 'Houve um problema no envio do e-mail. Nossa equipe foi notificada e está trabalhando para resolver o problema.'
            ], 500);
        }
        if($e->getCode() === 429){
            return response()->json([
                'status' => 'error',
                'messageToDeveloper' => 'Erro na requisição da API Brevo. Código do erro 429. O cliente ultrapassou o limite de taxa ou cota permitido para o envio de emails em seu acesso.',
                'message' => 'Houve um problema no envio do e-mail. Nossa equipe foi notificada e está trabalhando para resolver o problema.'
            ], 500);
        }
    }
}