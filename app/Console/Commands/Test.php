<?php

namespace App\Console\Commands;

use App\Jobs\ExampleJob;
use App\Services\Provider\OkChainExplorer;
use App\Services\Service\NetworkService;
use Illuminate\Console\Command;

class Test extends Command
{

    protected $signature = 'script:test';

    public function handle()
    {


        dispatch(new ExampleJob('test111'));
        var_dump('gogogogo');
        dispatch(new ExampleJob('test112'));
        var_dump('gogogogdddo');
        dispatch(new ExampleJob('test113'));
        var_dump('gogogogddxxxo');
        dispatch(new ExampleJob('test114'));

//        $name = 'x.d/d$?/$sda#';
//        $name = \Utility::name2uri($name);
//        var_dump($name);
//        $demo = \Online::testDemo();
//        var_dump($demo);


//        var_dump('22222');

//        $url = 'https://www.okex.com/okchain/v1/staking/delegators/okchain16jr4ru6qsej8ejfdgayzx5lu2l5vrqhahvkd0f';
//        $response = NetworkService::instance()->get($url);
//
//        var_dump($response);

//        $ret = \Cache::put('test', 1, 10);
//        var_dump($ret);
//
//        $val = \Cache::get('test');
//
//        var_dump($val);

//        $response = OkChainExplorer::instance()->validators();
//
//        var_dump($response);

//        $response = OkChainExplorer::instance()->getDelegator('okchain16jr4ru6qsej8ejfdgayzx5lu2l5vrqhahvkd0f');
//
//        var_dump($response);


//        $response = OkChainExplorer::instance()->getAllProducer(0, 30);
//
//        var_dump($response);

//        \Cache::forever('test22', 11111);

//        $t1 = \Cache::get('test22');
//
//        var_dump($t1);
    }


}