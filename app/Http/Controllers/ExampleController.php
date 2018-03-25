<?php

namespace App\Http\Controllers;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function test() {
        return "Test is executed without error(s).";
    }

    public function testLogin(Request $request) {

    }

    public function gen() {
        return app('hash')->make('password');
    }

}
