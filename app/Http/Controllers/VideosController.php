<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideosController extends Controller
{
    public function doupload(Request $request){
        $this->validate($request, [

            'slide' => 'required|mimes:mp4|max:5000'
            //mp4,ppx, ppt, pptx,pdf,ogv,jpg,webm
            //'slide' => 'mimetypes:video/avi,video/mpeg,video/quicktime'

        ]);
        $file = $request->file('slide');
        $destnation_path = public_path().'/videos';
        $extension =$file->getClientOriginalExtension();
        $files = $file->getClientOriginalName();
        $filename = $files.'_'.time().'.'.$extension;
        $file->move($destnation_path,$filename);
        $pod = new Video();
        $pod->title = $request->title;
        $pod->url =$filename;
        $pod->save();
    }

    public function fetchAll(Request $request)
    {
        $cli = Video::orderby('created_at', 'desc')->get();
        return $cli;
    }
}
