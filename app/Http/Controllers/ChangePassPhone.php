<?php

namespace App\Http\Controllers;

use AfricasTalking\SDK\AfricasTalking;
use App\Models\Code;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePassPhone extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function changePassword(Request $request)
    {
        $user = User::where('id',Auth::guard('api')->user()->id)->first();       
        if (Hash::check($request['oldpass'], $user->password)) {
            $user->update([
                'password' => Hash::make($request['newpass'])
            ]);
            return response()->json([
                'message' => 'Success',
            ],201);
        } else {
            return response()->json([
                'message' => 'Invalid old password',
            ],200);
        }
    }
    public function checkIfExist(Request $request)
    {
        $output = $request->phone;
        $user = User::find(Auth::user()->id);
		$a = User::all();
		$al = [];
		foreach ($a as $av){
            array_push($al,$av->phone);
        }
		if (in_array($request->newPhone,$al)){
			return response()->json([
                'message' => 'The new number has already been taken',
            ],200);
		}
        else if ($user->phone != $output){
			return response()->json([
                'message' => 'The old number does not belong to you',
            ],200);
            
        }else{
            return response()->json([
                'message' => 'Code sent',
            ],201);
        }
    }

    public function changePhone(Request $request)
    {
        $user = User::where('id',Auth::guard('api')->user()->id)->first();
        $cc = Code::where('phone',$request->newPhone)->orderby('created_at', 'desc')->first();
        $cod = $request->code;
        if ($cod == $cc->code){
            $user->update([
                'phone'=>$request->newPhone
            ]);
            $cc->delete();
            return response()->json([
                'message' => 'Success',
            ],201);
        }else{
            return response()->json([
                'message' => 'Wrong code',
            ],200);
        }
    }
}
