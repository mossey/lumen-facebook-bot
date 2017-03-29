<?php

namespace App\Http\Controllers;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function facebook(){

// parameters
        $hubVerifyToken = 'TOKEN123456abcd';
        $accessToken = "EAAbjcuvajRYBAKc0O857d7sBYqjFc18uZBlCaBP2LK3YGZCuueQAG6DHC66AMQi534qC3ZADgZBWLTZCGOMISZAYRBu2cQ6g6wcB7S6WYTESbWSGoRkY4Wts0N0f4z19nx28jWwFaYWBMZBeXKgQUrr249BgVtIao4RsP4AE1a5EQZDZD";

// check token at setup
        if ($_REQUEST['hub_verify_token'] === $hubVerifyToken) {
            echo $_REQUEST['hub_challenge'];
            exit;
        }

// handle bot's anwser
        $input = json_decode(file_get_contents('php://input'), true);

        $senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
        $messageText = $input['entry'][0]['messaging'][0]['message']['text'];


        $answer = "I don't understand. Ask me 'hi'.";
        if(!is_null($messageText)){
            if($messageText == "hi") {
                $answer = "Hello ".$senderId. " please choose one" ;

            }
            $response = [


                'recipient' => [ 'id' => $senderId ],
                'message' => [ "attachment"=> [
                    "type"=>"template",
                    "payload"=>[
                        "template_type"=>"button",
                        "text"=>"choose one",
                        "buttons"=>[[
                            "type"=>"web_url",
                            "url"=>"http://twitter.com",
                            "title"=>"twitter"],
                            [
                                "type"=>"web_url",
                                "url"=>"http://fb.com",
                                "title"=>"facebook"]

                        ]
                    ]

                ]
                ]];
            $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$accessToken);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_exec($ch);
            curl_close($ch);
        }



    }

    //
}
