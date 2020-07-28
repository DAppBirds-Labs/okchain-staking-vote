<?php

namespace App\Services\Persistence;

use App\Jobs\DelegatorCacheJob;
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
        if (!$data || $data['expire_time'] < $now) {
            $lists = OkChainExplorer::instance()->validators();
            if (!$lists) {
                return null;
            }

            $uri_alias_arr = [];
            $results = [];
            foreach ($lists as $item) {
                $validator_address = $item['operator_address'];
                $tmp = [];
                $tmp['operator_address'] = $item['operator_address'];
                $tmp['consensus_pubkey'] = $item['consensus_pubkey'];
                $tmp['delegator_shares'] = $item['delegator_shares'];
                $identity = Arr::get($item, 'description.identity');

                if (Str::startsWith($identity, ['http://', 'https://'])) {
                    $logo = $identity;
                } else if (Str::contains($identity, 'logo|||')) {
                    $logo = str_replace('logo|||', '', $identity);
                } else {
                    $logo = config('app.validator_logo');
                }

                $node_name = Arr::get($item, 'description.moniker');

                $tmp['description'] = [
                    'moniker' => $node_name,
                    'website' => Arr::get($item, 'description.website'),
                    'details' => Arr::get($item, 'description.details'),
                    'logo' => $logo,
                ];

                $uri_alias = \Utility::name2uri($node_name);
                $tmp['validator_uri_name'] = $uri_alias;
                $vote_num = $this->getVoteNumByValidator($validator_address);
                if($vote_num === false){
                    $tmp['vote_num'] = null;
                }else{
                    $tmp['vote_num'] = (int) $vote_num;
                }

                $deposit_token = $this->getDepositToken($validator_address);
                if($deposit_token === false){
                    $tmp['vote_token'] = null;
                }else{
                    $tmp['vote_token'] = floatval($deposit_token). '';
                }

                $uri_alias_arr[$uri_alias] = $validator_address;

                $this->storeValidatorAlias($uri_alias_arr);

                $results[] = $tmp;
            }

            $now += 10;

            $results && \Cache::forever($cache_key, json_encode(['expire_time' => $now, 'results' => $results]));
        } else {
            $results = Arr::get($data, 'results');
        }

        return $results;
    }

    public function storeValidatorAlias($uri_alias_arr)
    {
        $cache_key = 'cache:mapping-uri-alias-validator';
        $ret = \Cache::forever($cache_key, json_encode($uri_alias_arr));

        return $ret;
    }

    public function getValidatorByUriAlias($uri_alias)
    {
        $cache_key = 'cache:mapping-uri-alias-validator';
        $data = \Cache::get($cache_key);
        $data && $data = json_decode($data, true);
        if($data){
            return \Arr::get($data, $uri_alias);
        }

        return false;
    }

    public function getVoteNumByValidator($validator_address)
    {
        $cache_key = sprintf('cache:vote-num-validator-%s', $validator_address);
        $data = \Cache::get($cache_key);
        $data && $data = json_decode($data, true);
        $now = time();
        $info = null;
        if (!$data || $data['expire_time'] < $now) {
            $vote_addresses = OkChainExplorer::instance()->getVoteAddressByValidator($validator_address);
            if($vote_addresses === false){
                return false;
            }

            $vote_num = count($vote_addresses);
            $now += 600;
            \Cache::forever($cache_key, json_encode(['expire_time' => $now, 'vote_num' => $vote_num]));

        } else {
            $vote_num = $data['vote_num'];
        }

        return $vote_num;
    }

    public function getDepositToken($validator_address)
    {
        $cache_key = sprintf('cache:deposit-token-validator-%s', $validator_address);
        $data = \Cache::get($cache_key);
        $data && $data = json_decode($data, true);
        $now = time();
        $info = null;
        if (!$data || $data['expire_time'] < $now) {
            $vote_addresses = OkChainExplorer::instance()->getVoteAddressByValidator($validator_address);
            if($vote_addresses === false){
                return false;
            }


            $deposit_token = 0;
            foreach ($vote_addresses as $vote_address){
                $delegator_info = $this->getOnlyDelegator($vote_address);

                $_tokens = (float)\Arr::get($delegator_info, 'tokens');
                $deposit_token += $_tokens;
                dispatch(new DelegatorCacheJob($vote_address));
            }

            $now += mt_rand(10, 60);
            \Cache::forever($cache_key, json_encode(['expire_time' => $now, 'deposit_token' => $deposit_token]));

        } else {
            $deposit_token = $data['deposit_token'];
        }

        return $deposit_token;
    }

    public function storeVoteNumByValidator($validator_address, $vote_num)
    {
        $cache_key = sprintf('cache:vote-num-validator-%s', $validator_address);

        $now = time();
        $now += 600;

        $ret = \Cache::forever($cache_key, json_encode(['expire_time' => $now, 'vote_num' => $vote_num]));

        return $ret;
    }



    public function getPoolInfo()
    {
        $response = OkChainExplorer::instance()->poolAssets();

        return $response;
    }

    public function getDelegator($delegator_address, $is_force = false)
    {
        $cache_key = sprintf('cache:delegator_info-%s', $delegator_address);
        $data = \Cache::get($cache_key);
        $data && $data = json_decode($data, true);
        $now = time();
        $info = null;
        if(!$data || $data['expire_time'] < $now || $is_force){
            $info = OkChainExplorer::instance()->getDelegator($delegator_address);

            if(!empty($info['code'])){
                $info = [];
                $info['delegator_address'] = $delegator_address;
            }

            $expire_time = $now + mt_rand(3600, 86400);

            $info && \Cache::forever($cache_key, json_encode(['expire_time' => $expire_time , 'info' => $info]));
        }else{
            $info = Arr::get($data, 'info');
        }

        return $info;
    }

    public function getOnlyDelegator($delegator_address)
    {
        $cache_key = sprintf('cache:delegator_info-%s', $delegator_address);
        $data = \Cache::get($cache_key);
        $data && $data = json_decode($data, true);

        $info = Arr::get($data, 'info');

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