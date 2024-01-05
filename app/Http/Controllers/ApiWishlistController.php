<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\Mail;
use App\Models\Wish;
use App\Models\Participant;

class ApiWishlistController extends Controller
{
    private $_timezone;
    private $_request;
    private $_mail;

    public function __construct(Request $request, Mail $mail){
        $this->_timezone = date_default_timezone_set('America/Fortaleza');
        $this->_request = $request;
        $this->_mail = $mail;
    }

    public function create($token){
        try{
            $wishes = $this->_request->input('wishes');
            if(!$wishes){
                throw new \Exception('Nenhum desejo foi enviado.');
            }
            $participant = Participant::where('token', $token)->first();
            if(!$participant){
                throw new \Exception('Nenhum participante foi encontrado.');
            }
            $createWishlist = $participant->wishes()->createMany($wishes);
            $secretSanta = Participant::where('party_id', $participant['party_id'])->where('name', $participant['secret_santa'])->first();
            $sendEmail = $this->_mail->sendWishes($participant, $secretSanta['email'], $token);
            return response()->json([
                'status' => 'success',
                'party' => $wishes
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'messages' => $e->getMessage()
            ], 200);
        }
    }
    
}