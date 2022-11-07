<?php

namespace App\Http\Controllers;

use App\Models\NewsModel;
use App\Models\Category;
use App\Models\Feature;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class NewsController extends Controller
{
    public function addFeature(Request $request)
    {
        if ($request->hasFile('video') && $request->hasFile('podcast')
            && $request->hasFile('editorial') && $request->hasFile('magazine')){
            $mag = $request->file('magazine');
            $vid = $request->file('video');
            $pod = $request->file('podcast');
            $edi = $request->file('editorial');
            $path = public_path('/features');
            $magname = $mag->getClientOriginalName();
            $vidname = $vid->getClientOriginalName();
            $podname = $pod->getClientOriginalName();
            $ediname = $edi->getClientOriginalName();
            $mag->move($path,$magname);
            $vid->move($path,$vidname);
            $pod->move($path,$podname);
            $edi->move($path,$ediname);
            $feature = new Feature();
            $feature->video = $vidname;
            $feature->magazine = $magname;
            $feature->editorial = $ediname;
            $feature->podcast = $podname;
            $feature->save();
            return response()->json(['message'=>'Success'],201);
        }
    }
    public function addNews(Request $request)
    {
        $file = $request->file('image');
        $destnation_path = public_path().'/editorials';
        $filename = $file->getClientOriginalName();
        $file->move($destnation_path,$filename);
        $news = new NewsModel();
        $news->title = $request['title'];
        $news->link = $request['link'];
        $news->imageurl = $filename;
        $news->details_id = $request->id;
        $news->date = Carbon::now()->format('d-m-Y');
        $news->save();
        $this->sendNotification("New Editorial","A new editorial has been published. Check it out");
        return response()->json([
            'message' => 'Editorial added successfully',
        ],201);
    }
    public function getCategories(){
        $categories = Category::latest()->get();
        return $categories;
    }
    public function getNewsDetails(Request $request){
        $id = $request['id'];
        $details = DB::Table('wplm_posts')->where('id',$id)->get();
        return $details;
    }
    public function getAuthor(Request $request){
        $id = $request['id'];
        $author = DB::Table('wplm_users')->where('id',$id)->get();
        return $author;
    }
    public function getSearchedNews(Request $request){
        $category = $request['category'];
        $cc = Category::where('category','LIKE','%'.$category.'%')->first();
        if (!empty($cc)){
            $id = $cc->id;
            $news = NewsModel::where('category',$id)->orderby('created_at','desc')->get();
            return $news;
        }else{
            return response()->json([
            ],200);
        }
    }
    public function addCategory(Request $request){
        $catt = $request['category'];
        $cate = new Category();
        $cate->category = $catt;
        $cate->save();
        // where('id','!=',$id)->
    }

    public function getNews(Request $request){
        $category = $request['category'];
        $cc = Category::where('category',$category)->first();
        $idd = $cc->id;
        $latest = NewsModel::orderby('created_at','desc')->first();
        $id = $latest->id;
        $news = NewsModel::where('category',$idd)->orderby('created_at','desc')->get();
        return $news;
    }
}
