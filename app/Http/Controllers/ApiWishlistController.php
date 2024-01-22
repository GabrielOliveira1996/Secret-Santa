<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\IMail;
use App\Models\Wish;
use App\Models\Participant;

class ApiWishlistController extends Controller
{
    private $_timezone;
    private $_request;
    private $_mail;

    public function __construct(Request $request, IMail $mail){
        $this->_timezone = date_default_timezone_set('America/Fortaleza');
        $this->_request = $request;
        $this->_mail = $mail;
    }

    public function create($token){
        try{
            $wishes = $this->_request->input('wishes');
            if(!$wishes){
                throw new \Exception('Nenhum desejo foi enviado.', 400);
            }
            $participant = Participant::where('token', $token)->first();
            if(!$participant){
                throw new \Exception('Nenhum participante foi encontrado.', 400);
            }
            $createWishlist = $participant->wishes()->createMany($wishes);
            $secretSanta = Participant::where('party_id', $participant['party_id'])->where('name', $participant['secret_santa'])->first();
            $sendEmail = $this->_mail->sendWishList($secretSanta['name'], $secretSanta['email'], $wishes);
            return response()->json([
                'wishes' => $wishes
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
    
}