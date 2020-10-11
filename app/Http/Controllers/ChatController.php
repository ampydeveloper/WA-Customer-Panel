<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Request;
use LRedis;

class ChatController extends Controller {

    public function __construct() {
        
    }

    public function publicChat() {
        if (Auth::check()) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://" . env('SOCKET_SERVER_IP') . ":" . env('SOCKET_SERVER_PORT'));
            // SSL important
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $output = curl_exec($ch);
            curl_close($ch);

            // dd($output);
            $messages = json_decode($output);
            $messages = array_reverse($messages);
            return view('chat.public', ['messages' => $messages]);
        }

        session()->flush();
        return redirect(route('frontend.enterLiveStreaming'));
    }

    public function privateChat() {
        $token = "fdbfdbfd";
        $username = auth()->user()->username;

        $userId = auth()->user()->id;
        $modelId = 1; // this is we get by match token in db with models  // for testing i user admin account
        $getUniqueID = $this->getUniqueID($userId, $modelId);

        //
        $data = array(
            'uniqueid' => $getUniqueID,
        );
        $postData = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://" . env('SOCKET_SERVER_IP') . ":" . env('SOCKET_SERVER_PORT') . "/private-chat");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        // curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $output = curl_exec($ch);
        //
        $messages = json_decode($output);
        $messages = array_reverse($messages);

        return view('frontend.chat.private', ['token' => $token, 'username' => $username, 'getUniqueID' => $getUniqueID, 'messages' => $messages]);
    }

    public function modelChat() {
        $token = "fdbfdbfd";
        $username = "modelname";

        $modelId = auth()->user()->id;
        $userId = 121; // this is we get by match token in db with models
        $getUniqueID = $this->getUniqueID($userId, $modelId);

        //
        $data = array(
            'uniqueid' => $getUniqueID,
        );
        $postData = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://" . env('SOCKET_SERVER_IP') . ":" . env('SOCKET_SERVER_PORT') . "/private-chat");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        // curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $output = curl_exec($ch);
        //
        $messages = json_decode($output);
        $messages = array_reverse($messages);

        return view('frontend.chat.private', ['token' => $token, 'username' => $username, 'getUniqueID' => $getUniqueID, 'messages' => $messages]);
    }

    private function getUniqueID($a, $b) {
        if ($a < $b)
            return $a . "-" . $b;
        else
            return $b . "-" . $a;
    }

}
