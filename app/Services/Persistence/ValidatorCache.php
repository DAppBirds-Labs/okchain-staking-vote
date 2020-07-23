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
        $data && $data = json_decode($data, true);
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

            $results && \Cache::forever($cache_key, json_encode(['expire_time' => $now, 'results' => $results]));
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

    public function getDelegator($delegator_address)
    {
        $cache_key = sprintf('cache:delegator_info-%s', $delegator_address);
        $data = \Cache::get($cache_key);
        $data && $data = json_decode($data, true);
        $now = time();
        $info = null;
        if(!$data || $data['expire_time'] < $now){
            $info = OkChainExplorer::instance()->getDelegator($delegator_address);

            $expire_time = $now + 30;

            $info && \Cache::forever($cache_key, json_encode(['expire_time' => $expire_time , 'info' => $info]));
        }else{
            $info = Arr::get($data, 'info');
        }

        return $info;
    }

    public function getAccountAsset($delegator_address, $bond_denom)
    {
        $cache_key = sprintf('cache:account_asset-%s-%s', $delegator_address, $bond_denom);
        $data = \Cache::get($cache_key);
        $data && $data = json_decode($data, true);
        $now = time();
        $info = null;
        if(!$data || $data['expire_time'] < $now){
            $info = OkChainExplorer::instance()->getAccountAsset($delegator_address, $bond_denom);

            $expire_time = $now + 30;

            $info && \Cache::forever($cache_key, json_encode(['expire_time' => $expire_time , 'info' => $info]));
        }else{
            $info = Arr::get($data, 'info');
        }

        return $info;
    }

    public function storeDelegator($delegator_address, $info)
    {
        $cache_key = sprintf('cache:delegator_info-%s', $delegator_address);

        $expire_time = time() + 200;

        $ret =\Cache::forever($cache_key, json_encode(['expire_time' => $expire_time , 'info' => $info]));

        return $ret;
    }

    public function getParam()
    {
        $cache_key = 'cache:chain-param';
        $data = \Cache::get($cache_key);
        $data && $data = json_decode($data, true);
        $now = time();
        $params = null;
        if(!$data || $data['expire_time'] < $now){
            $params = OkChainExplorer::instance()->stakingParameters();

            $now += 300;
            $params && \Cache::forever($cache_key, json_encode(['expire_time' => $now, 'params' => $params]));
        }else{
            $params = Arr::get($data, 'params');
        }

        $bond_denom = Arr::get($params, 'bond_denom');
        !$bond_denom && $params['bond_denom'] = config('app.bond_denom');
        $params['asset_logo'] = config('app.bond_denom_logo');

        return $params;
    }
}