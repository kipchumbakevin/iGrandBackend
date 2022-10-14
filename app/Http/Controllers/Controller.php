<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function sendNotification($title,$body)
    {
        // FCM API Url
        $url = 'https://fcm.googleapis.com/fcm/send';

        // Put your Server Key here
        $apiKey = "AAAA6S8pnLc:APA91bEnFIBli4cjRMyHrLGxcYuU7jFgUur4eGwBvM4F0ysk_jGG7hXq4ZHQ9s6uo8wTCttSww0lKnu6O56aBAiN2ks6OY6n2Min9y4KSf5gGlAcNYwFTzkUY_IBGOriDHEKWZDBWPLd";

        // Compile headers in one variable
        $headers = array (
            'Authorization:key=' . $apiKey,
            'Content-Type:application/json'
        );

        // Add notification content to a variable for easy reference
        $notifData = [
            'title' => $title,
            'body' => $body,
            //  "image": "url-to-image",//Optional
            //'click_action' => "activities.NotifHandlerActivity" //Action/Activity - Optional
        ];
//optional
        $dataPayload = [
            'to'=> 'My Name',
            'points'=>80,
            'other_data' => 'This is extra payload'
        ];

        // Create the api body
        $apiBody = [
            'notification' => $notifData,
            //'data' => $dataPayload, //Optional
            //'time_to_live' => 600, // optional - In Seconds
            //'to' => '/topics/mytargettopic'
            //'registration_ids' = ID ARRAY
            'to' => 'fZJkjwFwJ1Y:APA91bF2DbQm49YIw1yQ18QXZE5bvCEWC7Odu7_GAA588oKfeT00EuXJ5xtZneSLe8izVkDdDrf6hUJpLepEdO6t5VBY6Etn6lPC8ILc1at5nyV_idm-x_aWP2eTTHLHF_1x9guU_CgW'
        ];

        // Initialize curl with the prepared headers and body
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_POST, true);
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));

        // Execute call and save result
        $result = curl_exec($ch);
        print($result);
        // Close curl after call
        curl_close($ch);

        return $result;

    }
}
