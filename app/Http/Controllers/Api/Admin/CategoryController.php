<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bonus;
use App\Models\Faq;
use App\Models\HelpVideo;
use App\Models\Level;
use App\Models\SnapShotPoint;
use Illuminate\Http\Request;
use PHPUnit\TextUI\Help;
use stdClass;

class CategoryController extends Controller
{
    public function category(Request $request)
    {
        $obj = new stdClass();
        $levels = Level::all();
        $video = HelpVideo::all();
        $faq = Faq::all();
        $obj->levels = $levels;
        $obj->videos = $video;
        $obj->faqs = $faq;
        $bonuses = Bonus::where('user_id', $request->user_id)->where('is_read', 0)->get();
        $totalbonus = 0;
        foreach($bonuses as $bonus){
            $totalbonus += $bonus->point;
        }

        $obj->bonus = $totalbonus;
        $point = SnapShotPoint::latest()->first();
        $obj->point_value = $point->value;


        return response()->json([
            'status' => true,
            'action' => 'Categories List',
            'data'   => $obj
        ]);
    }
}
