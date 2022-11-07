<?php

namespace App\Http\Controllers;

use App\Models\Magazine;
use App\Models\NewsModel;
use Illuminate\Http\Request;

class MagazineController extends Controller
{
    public function doupload(Request $request){
        $image = $request->file('image');
        $file = $request->file('magazine');
        $destnation_path = public_path().'/magazine';
        $filename = $file->getClientOriginalName();
        $imagename = $image->getClientOriginalName();
        $file->move($destnation_path,$filename);
        $image->move($destnation_path,$imagename);
        $pod = new Magazine();
        $pod->title = $filename;
        $pod->imageurl = $imagename;
        $pod->url = 'https://www.igrandsub.igrandbp.com/public/magazine/'.$filename;
        $pod->save();
        $this->sendNotification("Magazine Upload","A new magazine has been uploaded. Check it out");
        return response()->json(['message'=>'success']);
    }
    public function fetchAll(Request $request)
    {
        $cli = Magazine::orderby('created_at', 'desc')->get();
        return $cli;
    }
}
