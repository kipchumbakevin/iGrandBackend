<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use App\Models\Feature;
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
        $image = $request->file('image');
        $file = $request->file('audio');
        $destnation_path = public_path().'/podcast';
        $filename = $file->getClientOriginalName();
        $imagename = $image->getClientOriginalName();
        $file->move($destnation_path,$filename);
        $image->move($destnation_path,$imagename);
        $pod = new Podcast();
        $pod->title = $request->title;
        $pod->imageurl = $imagename;
        $pod->url ='https://igrandsub.igrandbp.com/public/podcast/'.$filename;
        $pod->save();
        $this->sendNotification("New Podcast","A new podcast has been published. Check it out");
        return response()->json(['message'=>'success']);
    }

    public function fetchAll(Request $request)
    {
        $latest = Podcast::orderby('created_at','desc')->first();
        $id = $latest->id;
        $cli = Podcast::orderby('created_at', 'desc')->get();
        return $cli;
    }
}
