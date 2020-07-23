<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    protected $is_force_secure;

    public function __construct()
    {
        $request = app('request');
        $this->is_force_secure = \Online::detectRequestSecure($request);
    }

    //
    protected function error($code, $msg, $data = null, $extra = null)
    {
        $code = (int)$code;
        $msg = (string)$msg;

        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
            'extra' => $extra,
        ]);
    }

    protected function success($data = null, $extra = null)
    {
        $extra['timestamp'] = time();

        return response()->json([
            'code' => 0,
            'data' => $data,
            'extra' => $extra,
        ]);
    }
}
