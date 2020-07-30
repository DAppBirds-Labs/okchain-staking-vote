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
        $data_raw = file_get_contents('php://input');

        if ($method == 'GET') {
            $response = OkChainExplorer::instance()->proxyCall($path, $_GET);
        } else {
            $response = OkChainExplorer::instance()->proxyCall($path, json_decode($data_raw, true), 'POST');
        }

        return response($response, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
