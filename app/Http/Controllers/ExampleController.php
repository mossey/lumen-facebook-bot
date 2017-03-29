<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
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
    public function facebook(Request $request){

// parameters
        $hubVerifyToken = 'TOKEN123456abcd';
        $accessToken = "EAAC9ttF8doIBAAM0d8ZA8ag3c0oysDOrfTzUJsZBagRhz0GuZB8XkDtxVhmSkrs10tMZBEheoGvBLNpMnHmGsP7a9ZCFuNC5KTyVm8iWeAmwuSJKdY4AU42zZCsiXARETMneQMcBQ1eE8ZB9ZAvmdjWpzmXTiMuWKMlOhvqa9MbF3wZDZD";

// check token at setup
        if ($request->input('hub_verify_token') === $hubVerifyToken) {
            echo $request->input('hub_challenge');
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
