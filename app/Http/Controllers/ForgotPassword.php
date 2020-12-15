<?php

namespace App\Http\Controllers;

use AfricasTalking\SDK\AfricasTalking;
use App\Models\Code;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPassword extends Controller
{
    public function checkIfExist(Request $request)
    {
        $user = User::where('phone',$request->phone)->first();
        if ($user!=null){
            return response()->json([
                'message' => 'Code sent',
            ],201);
        }else{
            return response()->json([
                'message' => 'The number does not exist',
            ],200);
        }
    }
    public function forgotPassword(Request $request)
    {
		$output = $request->phone;
        $user = User::where('phone',$output)->first();
        $codes = Code::where('phone',$output)->orderby('created_at', 'desc')->first();
        $cod = $request->code;
        if ($user!=null && $cod == $codes->code){
            $user->update([
                'password'=>Hash::make($request->new_password)
            ]);
		
			$codes->delete();
            $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;
                if ($request->remember_me)
                    $token->expires_at = Carbon::now()->addWeeks(1);
                $token->save();

                return response()->json([
                    'access_token' => $tokenResult->accessToken,
                    'message'=>"Successfully registered",
                    'user' => $user,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString()
                ], 201);
      
           

        }else{
            return response()->json([
                'message' => 'Wrong code',
            ],200);
        }
    }
}
