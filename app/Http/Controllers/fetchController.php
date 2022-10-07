<?php

namespace App\Http\Controllers;

use App\Models\ClientDocs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class fetchController extends Controller
{
    
    public function fetchAll(Request $request)
    {
        
        $cli = ClientDocs::where('client_email',$request->email)->orderby('created_at', 'desc')->get();
        return $cli;
    }
}
