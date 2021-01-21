<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Request;
use LRedis;
use App\Models\SocialPost;
use App\Models\Settings;

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

//    public function instgramRefreshToken() {
    public function getInstgramPosts() {
//        https://api.instagram.com/oauth/authorize
//  ?client_id=684477648739411
//  &redirect_uri=https://socialsizzle.herokuapp.com/auth/
//  &scope=user_profile,user_media
//  &response_type=code
//        $curl = curl_init();
//        curl_setopt_array($curl, array(
//            CURLOPT_URL => "https://api.instagram.com/oauth/access_token",
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING => "",
//            CURLOPT_MAXREDIRS => 10,
//            CURLOPT_TIMEOUT => 0,
//            CURLOPT_FOLLOWLOCATION => true,
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => "POST",
//            CURLOPT_POSTFIELDS => array('client_id' => '739257940326449', 'client_secret' => '3d3edfd09ff7b9b1bce9073da198f503', 'grant_type' => 'authorization_code', 'redirect_uri' => 'https://wa.customer.leagueofclicks.com/', 'code' => 'AQD_k902b5ckt9-goA775_-9JdbXpucaK1FnEyFt_pTzBZsEIkd7_CeucrVXRspbNWoOJ8BFTxUHy2JcA8UC6OFzQUk0u0pW9eNoN9udgxeAI1Fe2fhkhtcUIqfy4vkSBy8_im1iNa75ZeoETICzojOmU_plDmdyeDc4H6Olj8YY6EYBIWrPfV063Df1gtnWIwFGgRGd1_B0xCE0bKL7PSCCus-Qg6kSuJzxoUGvk94Nqw'),
//            CURLOPT_HTTPHEADER => array(
//                "Content-Type: multipart/form-data; boundary=--------------------------780367731654051340650991"
//            ),
//        ));
//        $response = curl_exec($curl);
//        curl_close($curl);
//        print_r($response);
//        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.instagram.com/17841445037860695?fields=media&access_token=AQB9l-WzMGyslzzIZs7Soo4dD-prU6P6XvHvWqAL7Bqt0_LJFFMKve8W4uuQnFqAyJ1v5pT8nA-CFOm_wtINy2YHfoOXpYbO25v7vAymecQxjzyudy4Vo2jRbNF1iTOMxLa2PEtCi9YejZc9xfTXe-SAWVC3k7LPJmlS3Xwxwuns7KfY1HjnfoKDDWBAGEtCi2Wn43rXrmPYVyyIWR5gVGjX476nzP4j3ZvfOg58-eaTbg",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
//            CURLOPT_POSTFIELDS => array('access_token' => 'IGQVJVaFhtOVhDUDlKWWRQWXc5RVkwaHdUTGpZAUERsc1VFOWhrNk1aWjhILVo3cDk3NDlDOWRQYWI3eVdwNjdsZAUUxR1pIcDdlQjZAvWnJzc2dBTUpPNksxVzhudnQyamRwTmlqQ3lxckNjcXlqZA3NmeVpwcTBfSVNlamJv'),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: multipart/form-data; boundary=--------------------------780367731654051340650991"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        print_r($response);
//        
//        $curl = curl_init();
//        curl_setopt_array($curl, array(
//            CURLOPT_URL => "https://graph.instagram.com/access_token?grant_type=ig_exchange_token&client_secret=3d3edfd09ff7b9b1bce9073da198f503&access_token=IGQVJVaFhtOVhDUDlKWWRQWXc5RVkwaHdUTGpZAUERsc1VFOWhrNk1aWjhILVo3cDk3NDlDOWRQYWI3eVdwNjdsZAUUxR1pIcDdlQjZAvWnJzc2dBTUpPNksxVzhudnQyamRwTmlqQ3lxckNjcXlqZA3NmeVpwcTBfSVNlamJv",
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING => "",
//            CURLOPT_MAXREDIRS => 10,
//            CURLOPT_TIMEOUT => 0,
//            CURLOPT_FOLLOWLOCATION => true,
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => "GET",
//            CURLOPT_HTTPHEADER => array(
//                "Content-Type: multipart/form-data; boundary=--------------------------780367731654051340650991"
//            ),
//        ));
//        $response = curl_exec($curl);
//        curl_close($curl);
//        print_r($response);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&client_secret=3d3edfd09ff7b9b1bce9073da198f503&access_token=IGQVJVaFhtOVhDUDlKWWRQWXc5RVkwaHdUTGpZAUERsc1VFOWhrNk1aWjhILVo3cDk3NDlDOWRQYWI3eVdwNjdsZAUUxR1pIcDdlQjZAvWnJzc2dBTUpPNksxVzhudnQyamRwTmlqQ3lxckNjcXlqZA3NmeVpwcTBfSVNlamJv",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: multipart/form-data; boundary=--------------------------780367731654051340650991"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        print_r($response);
//        Settings::first()->update(['instagram_access_token' => $accessTokenObj->getRefreshToken()]);
//        $this->getInstgramPosts();
    }

    public function getInstgramPosts2() {
        $tokens = Settings::first(['instagram_access_token']);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.instagram.com/17841445037860695?fields=media&access_token=".$tokens['instagram_access_token'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: multipart/form-data; boundary=--------------------------780367731654051340650991"
            ),
        ));
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if (isset($error_msg)) {
            $this->instgramRefreshToken();
        }
//        print_r($response);

        $messages = json_decode($response);
//        dd($messages->media->data[0]->id);
        foreach ($messages->media->data as $media) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://graph.instagram.com/" . $media->id . "?fields=id,media_type,media_url,username,timestamp&access_token=IGQVJXRjBVeDZAZAUkg3aW5odER0YVhVYndKZAHA0SG8xZAVVVa0VsOXhwREFLX29GanQtZAHY5RW9ySko3bFNhNkJaT1VzdzA4LWoyckJzZAWZA0VnBrWExIazRna2xjb2U1U3pqRGFxSTdB",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: multipart/form-data; boundary=--------------------------780367731654051340650991"
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
//            print_r($response);

            $social_post = json_decode($response);
            $social_posts = new SocialPost([
                'media_type' => $social_post->media_type,
                'media_url' => $social_post->media_url,
                'username' => $social_post->username,
                'timestamp' => $social_post->timestamp,
            ]);
            $social_posts->save();
            die('DONE');
        }
    }

}
