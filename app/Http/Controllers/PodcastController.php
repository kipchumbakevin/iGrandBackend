<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    public function doupload(Request $request){
//        $this->validate($request, [
//
//            'slide' => 'required|mimes:mp3|max:5000'
//            //mp4,ppx, ppt, pptx,pdf,ogv,jpg,webm
//            //'slide' => 'mimetypes:video/avi,video/mpeg,video/quicktime'
//
//        ]);
//        if($request->hasFile('slide')){
//            $filenameWithExt= $request->file('slide')->getClientOriginalName();
//            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
//            $extension = $request->file('slide')->getClientOriginalExtension();
//            $fileNameToStore = $filename. '_'.time().'.'.$extension;
//            $path = $request->file('slide')->storeAs('public/magazine/',$fileNameToStore);
//        }else{
//            $fileNameToStore = 'novideo.jpg';
//        }
//        $filenameWithExt= $request->file('slide')->getClientOriginalName();
//        $extension = $request->file('slide')->getClientOriginalExtension();
//        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
//        $fileNameToStore = $filename. '_'.time().'.'.$extension;
//        $filenameWithExt->move(public_path().'/podcast/', $fileNameToStore);
        $file = $request->file('slide');
        $destnation_path = public_path().'/podcast';
        $extension =$file->getClientOriginalExtension();
        $files = $file->getClientOriginalName();
        $filename = $files.'_'.time().'.'.$extension;
        $file->move($destnation_path,$filename);
        $pod = new Podcast();
        $pod->title = $request->title;
        $pod->url =$filename;
        $pod->save();
        return response()->json(['message'=>'Sucess'],201);
    }
    public function fetchAll(Request $request)
    {
        $cli = Podcast::orderby('created_at', 'desc')->get();
        return $cli;
    }
}
