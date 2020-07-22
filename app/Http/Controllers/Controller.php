<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
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
