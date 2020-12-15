<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $request->validate([
            'email' => 'required|string|unique:users',
            'phone'=>'required|string|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        $output = $request->phone;

            $user = new User([
                'email' => $request->email,
                'phone' => $output,
                'password' => Hash::make($request->password)

            ]);
            $codes = Code::where('phone',$output)->orderby('created_at', 'desc')->first();
            $cod = $codes->code;
            $cc = $request->code;
            if ($cc == $cod){
                $user->save();
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
                    'message'=>"Wrong code",
                ],200);
            }


    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['phone', 'password']);
        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Credentials do no match'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        //dd($tokenResult);
        $token = $tokenResult->token;
        //dd($token);
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'user' => $user,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ],200);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
    public function getUser(Request $request){
        $data = User::all('first_name','last_name','phone');
        return $data;
    }
}
