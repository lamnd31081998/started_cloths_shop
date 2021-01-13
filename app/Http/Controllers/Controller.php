<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->fast_links = DB::table('generals')->where('type', '=', 'fast-link')->orderBy('id')->get();
        $this->contact = DB::table('generals')->where('type', '=', 'contact')->first();
        $this->social_links = DB::table('generals')->where('type', '=', 'social-link')->orderBy('id')->get();
    }

    public function mail()
    {
        echo '<img src="'.asset(DB::table('generals')->where('type', '=', 'contact')->first()->images).'">';
    }
}
