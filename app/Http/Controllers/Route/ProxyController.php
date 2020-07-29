<?php

namespace App\Http\Controllers\Route;

use App\Http\Controllers\Controller;
use App\Services\Persistence\ValidatorCache;
use App\Services\Provider\OkChainExplorer;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProxyController extends Controller
{
    public function index(Request $request)
    {
        $req = $request;
        $path = $req->path();
        $method = $req->method();
        $path = str_replace('route/proxy', '', $path);

        if ($method == 'GET') {
            $response = OkChainExplorer::instance()->proxyCall($path, $_GET);
        } else {
            $response = OkChainExplorer::instance()->proxyCall($path, $_POST, 'POST');
        }

        return response($response, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
