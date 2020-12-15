<?php

namespace App\Http\Controllers;

use AfricasTalking\SDK\AfricasTalking;
use App\Models\Code;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class CodesController extends Controller
{
    public function sendCode(Request $request)
    {
        $output = $request->phone;
            $signature = $request->appSignature;
            $signupcode = rand(100000, 999999);
            $codes = new Code();
            $codes->code = $signupcode;
            $codes->phone = $output;
            $username = "mduka.com";
            $apiKey = "d028c62e28389d081e33fc4e0c2fbee3aea66570250cf60511c2dc410530149c";
            $AT = new AfricasTalking($username, $apiKey);
            $sms = $AT->sms();
            $recipients = $output;
            $message = "<#> Verification code:" . $signupcode . ": " . $signature;
            try {
                // Thats it, hit send and we'll take care of the rest
                $result = $sms->send([
                    'to' => $recipients,
                    'message' => $message,
                ]);
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
            $codes->save();
            return response()->json([
                'message' => 'Code sent',
            ], 201);
    }

    public function checkDetails(Request $request)
    {
        $output = $request->phone;
        $mail = $request->email;
        $usp = User::where('phone',$output)->get();
		$usm = User::where('email',$mail)->get();
        $p = [];
        $m = [];
        foreach ($usp as $ph){
            array_push($p,$ph->phone);
        }
        foreach ($usm as $pm){
            array_push($m,$pm->email);
        }
        if (in_array($mail,$m)){
            return response()->json([
                'message' => 'Email has already been taken',
            ],200);
        }else if (in_array($output,$p)){
            return response()->json([
                'message' => 'Phone has already been taken',
            ],200);
        }
        else if (in_array($output,$p) && in_array($mail,$m)){
            return response()->json([
                'message' => 'Phone and email have already been taken',
            ],200);
        }else {
            return response()->json([
                'message' => 'Code sent',
            ],201);
        }
    }
}
