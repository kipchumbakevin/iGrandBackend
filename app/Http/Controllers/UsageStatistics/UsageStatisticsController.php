<?php

namespace App\Http\Controllers\UsageStatistics;

use App\Http\Controllers\Controller;
use App\Models\UsageStatistics;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UsageStatisticsController extends Controller
{
    public function insert(Request $request)
    {
        $deviceId = $request['deviceId'];
        $contentId = $request['contentId'];
        $contentType = $request['contentType'];

        $usageStat = UsageStatistics::where('deviceId',$deviceId)
            ->where('contentId',$contentId)
            ->where('contentType',$contentType)
            ->first();
        if (empty($usageStat)){
            UsageStatistics::create([
                'deviceId'=>$deviceId,
                'contentId'=>$contentId,
                'contentType'=>$contentType,
                'date'=>Carbon::now()->format('Y-m-d')
            ]);
        }
    }
}
