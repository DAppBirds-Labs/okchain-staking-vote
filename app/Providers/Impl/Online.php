<?php

namespace App\Providers\Impl;

use Illuminate\Http\Request;


class Online
{

    public function testDemo()
    {
        return 'demo';
    }

    public function detectRequestSecure(Request $request)
    {
        // 当前请求是否为ssl
        $is_secure = $request->secure();
        if($is_secure){
            return true;
        }

        $request_headers = $request->headers;
        $x_secure = $request_headers->get('x-secure'); // 1- 开启了ssl
        if($x_secure == 1){
            return true;
        }

        return false;
    }

}
