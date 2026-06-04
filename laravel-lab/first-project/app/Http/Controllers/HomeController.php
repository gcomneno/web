<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('pages.home', [
            'greeting' => 'Hello',
            'showGreeting' => true,
        ]);
    }
}
