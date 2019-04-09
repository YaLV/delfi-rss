<?php

namespace App\Http\Controllers;

use App\Support\RssFacade;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    use RssFacade;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $xml = $this->readXML();
        if (!$xml) {
            return view('noReader');
        }

        return view('home', ["items" => $xml->channel->item]);
    }
}
