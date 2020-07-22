<?php

namespace App\Http\Controllers\Photos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //
    public function method(Request $request)
    {
        return 'method is ok?';
    }
}
