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

    public function send($participants, $party){
        try{
            $this->validateParticipants($participants);
            
            $sendinblueApiKey = env('SENDINBLUE_API_KEY');
            $configuration = $this->_configuration->getDefaultConfiguration()->setApiKey('api-key', $sendinblueApiKey);
            $apiInstance = new $this->_transictionalEmailsApi(new \GuzzleHttp\Client(), $configuration);
            foreach ($participants as $participant) {
                $sendSmtpEmail = new $this->_sendSmtpEmail([
                    'sender' => ['name' => 'Amigo Secreto', 'email' => 'suporte.amigo.secreto2023@gmail.com'],
                    'to' => [['email' => $participant['email']]],
                    'params' => [
                        'participant_name' => $participant['name'], // Nome do participante.
                        'party_host' => $participants[0]['name'], // Anfitrião.
                        'date' => $party['date'], // Data da festa.
                        'location' => $party['location'], // Local da festa.
                        'maximum_value' => $party['maximum_value'], // Valor máximo de presente.
                        'message' => $party['message'] // Messagem.
                    ],
                    'subject' => 'Amigo Secreto.',
                    'templateId' => 3,
                ]);
                $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            }
            return response()->json(['success' => true, 'message' => 'E-mails enviados com sucesso.']);
        }catch(\Exception $e){
            return response()->json(['error' => 'Erro no envio de e-mails.', 'message' => $e->getMessage()], 500);
        }catch(\SendinBlue\Client\ApiException $e){
            return $this->apiException($e);
        }
    }

    private function validateParticipants($participants) {
        if (empty($participants)) {
            throw new \Exception('Os participantes não foram fornecidos para o envio do e-mail.');
        }
    }

    private function apiException(\SendinBlue\Client\ApiException $e) {
        if ($e->getCode() == 401) {
            return response()->json([
                'error' => 'Erro na requisição da API Brevo.',
                'message' => 'Houve um problema no envio do e-mail. Nossa equipe foi notificada e está trabalhando para resolver o problema.'
            ], 500);
        }
    }
}