<?php

namespace App\Http\Controllers;

use App\Services\Persistence\ValidatorCache;

class MainController extends Controller
{
    public function index()
    {
        $lists = ValidatorCache::instance()->getAllValidators();

        return $this->success($lists);
    }
}
