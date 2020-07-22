<?php

namespace App\Console\Commands;

use App\Services\Provider\OkChainExplorer;
use App\Services\Service\NetworkService;
use Illuminate\Console\Command;
use QL\QueryList;
use QL\Ex\PhantomJs;

class Test extends Command
{
    protected $signature = 'script:test';

    public function handle()
    {
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
    }

    public function fetch()
    {
        $ql = QueryList::getInstance();
        $ql->use(PhantomJs::class,'/usr/local/bin/phantomjs');
//or Custom function name
        $ql->use(PhantomJs::class,'/usr/local/bin/phantomjs','browser');
    }
}