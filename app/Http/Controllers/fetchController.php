<?php

namespace App\Http\Controllers;

use App\Models\ClientDocs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class fetchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function fetchAll(Request $request)
    {
        $user = User::where('id',Auth::guard('api')->user()->id)->first();
        $m = $user->email;
        $cli = ClientDocs::where('client_email',$m)->orderby('created_at', 'desc')->get();
        return $cli;
    }
}
