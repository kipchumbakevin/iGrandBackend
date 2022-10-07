<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\NewsModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function addNews(Request $request)
    {
        $news = new NewsModel();
        $news->title = $request['title'];
        $news->link = $request['link'];
        $news->date = Carbon::now()->format('d-m-Y');
        $news->save();
        return response()->json([
            'message' => 'News added successfully',
        ],201);
    }
    public function getNews(){
        $news = NewsModel::orderby('created_at','desc')->get();
        return $news;
    }

    public function addfeatures(Request $request)
    {
        if ($request->hasFile('video') && $request->hasFile('podcast')
        && $request->hasFile('editorial') && $request->hasFile('magazine')){
            $mag = $request->file('magazine');
            $vid = $request->file('video');
            $pod = $request->file('podcast');
            $edi = $request->file('editorial');
            $path = public_path().'/features';
            $magname = $mag->getClientOriginalName();
            $vidname = $vid->getClientOriginalName();
            $podname = $pod->getClientOriginalName();
            $ediname = $edi->getClientOriginalName();
            $mag->move($path);
            $vid->move($path);
            $pod->move($path);
            $edi->move($path);
            $feature = new Feature();
            $feature->video = $vidname;
            $feature->magazine = $magname;
            $feature->editorial = $ediname;
            $feature->podcast = $podname;
            $feature->save();
            return response()->json(['message'=>'Success'],201);
        }
    }
}
