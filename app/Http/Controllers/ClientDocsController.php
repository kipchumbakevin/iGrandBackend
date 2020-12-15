<?php

namespace App\Http\Controllers;

use App\Models\ClientDocs;
use Illuminate\Http\Request;

class ClientDocsController extends Controller
{
    public function doupload(Request $request){
        $this->validate($request, [

            'slide' => 'required|mimes:pdf|max:5000'
            //mp4,ppx, ppt, pptx,pdf,ogv,jpg,webm
            //'slide' => 'mimetypes:video/avi,video/mpeg,video/quicktime'

        ]);
        $file = $request->file('slide');
        $destnation_path = public_path().'/clientDocs';
        $extension =$file->getClientOriginalExtension();
        $files = $file->getClientOriginalName();
        $filename = $files.'_'.time().'.'.$extension;
        $file->move($destnation_path,$filename);
        $pod = new ClientDocs();
        $pod->title = $request->title;
        $pod->client_email = $request->email;
        $pod->url =$filename;
        $pod->save();
    }


}
