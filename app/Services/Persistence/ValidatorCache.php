<?php

namespace App\Services\Persistence;

use App\Services\Provider\OkChainExplorer;
use App\Services\Service;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class ValidatorCache
 * @package App\Services\Persistence
 */
class ValidatorCache extends Service
{
    public function getAllValidators()
    {
        $cache_key = 'cache:all-validator';
        $data = \Cache::get($cache_key);
        $data && $data = json_decode($data);
        $now = time();
        $results = null;
        if(!$data || $data['expire_time'] < $now){
            $lists = OkChainExplorer::instance()->validators();
            if(!$lists){
                return null;
            }

            $results = [];
            foreach ($lists as $item){
                $tmp = [];
                $tmp['operator_address'] = $item['operator_address'];
                $tmp['consensus_pubkey'] = $item['consensus_pubkey'];
                $tmp['delegator_shares'] = $item['delegator_shares'];
                $identity = Arr::get($item, 'description.identity');

                if(Str::startsWith($identity, ['http://', 'https://'])){
                    $logo = $identity;
                }else if(Str::contains($identity, 'logo|||')){
                    $logo = str_replace('logo|||', '', $identity);
                }else{
                    $logo = config('app.validator_logo');
                }

                $tmp['description'] =  [
                    'moniker' => Arr::get($item, 'description.moniker'),
                    'website' => Arr::get($item, 'description.website'),
                    'details' => Arr::get($item, 'description.details'),
                    'logo' => $logo,
                ];

                $results[] = $tmp;
            }

            $now += 10;

            \Cache::forever($cache_key, json_encode(['expire_time' => $now, 'results' => $results]));
        }else{
            $results = Arr::get($data, 'results');
        }

        return $results;
    }

    public function getPoolInfo()
    {
        $response = OkChainExplorer::instance()->poolAssets();

        return $response;
    }
}