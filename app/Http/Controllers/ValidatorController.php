<?php

namespace App\Http\Controllers;

use App\Services\Persistence\ValidatorCache;
use App\Services\Provider\OkChainExplorer;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ValidatorController extends Controller
{
    public function info(Request $request)
    {
        // 可领取收益
        $validator_address = $request->get('validator_address');
        $validator_name = $request->get('validator_uri_name');
        $validator_cache_impl = ValidatorCache::instance();
        $validator_name && $validator_address = $validator_cache_impl->getValidatorByUriAlias($validator_name);

        $okchain_explorer_impl = OkChainExplorer::instance();
        $_info = $okchain_explorer_impl->getValidator($validator_address);

        $validator_info = [];
        $validator_info['operator_address'] = $_info['operator_address'];
        $validator_info['consensus_pubkey'] = $_info['consensus_pubkey'];
        $validator_info['delegator_shares'] = $_info['delegator_shares'];
        $identity = Arr::get($_info, 'description.identity');

        if(Str::startsWith($identity, ['http://', 'https://'])){
            $logo = $identity;
        }else if(Str::contains($identity, 'logo|||')){
            $logo = str_replace('logo|||', '', $identity);
        }else{
            $logo = config('app.validator_logo');
        }

        $validator_info['description'] =  [
            'moniker' => Arr::get($_info, 'description.moniker'),
            'website' => Arr::get($_info, 'description.website'),
            'details' => Arr::get($_info, 'description.details'),
            'logo' => $logo,
        ];

        $vote_num = $validator_cache_impl->getVoteNumByValidator($validator_address);
        if($vote_num === false){
            $validator_info['vote_num'] = null;
        }else{
            $validator_info['vote_num'] = (int) $vote_num;
        }

        $deposit_token = $validator_cache_impl->getDepositToken($validator_address);
        if($deposit_token === false){
            $validator_info['vote_token'] = null;
        }else{
            $validator_info['vote_token'] = floatval($deposit_token). '';
        }

        $params = $validator_cache_impl->getParam();
        $params['asset_logo'] = \Image::formatCustomUrl($params['asset_logo'], $this->is_force_secure);

        $bond_denom = Arr::get($params, 'bond_denom');
        $reward_info = $okchain_explorer_impl->getValidatorRewards($validator_address);

        $reward_balance = 0;
        foreach ($reward_info as $item){
            if($item['denom'] == $bond_denom){
                $reward_balance = $item['amount'];
                break;
            }
        }

        $validator_info['reward_balance'] = $reward_balance;

        $pool_assets = $okchain_explorer_impl->poolAssets();
        $bonded_tokens = Arr::get($pool_assets, 'bonded_tokens');

        return $this->success($validator_info, [
            'staking_param' => $params,
            'pool_bonded_tokens' => $bonded_tokens,
        ]);
    }

    public function voteAddresses(Request $request)
    {
        $validator_address = $request->get('validator_address');
        $validator_name = $request->get('validator_uri_name');
        $validator_name && $validator_address = ValidatorCache::instance()->getValidatorByUriAlias($validator_name);

        $okchain_explorer_impl = OkChainExplorer::instance();
        $validator_cache_impl = ValidatorCache::instance();

        $vote_addresses = $okchain_explorer_impl->getVoteAddressByValidator($validator_address);

        $results = null;
        if($vote_addresses){
            ValidatorCache::instance()->storeVoteNumByValidator($validator_address, count($vote_addresses));
            foreach ($vote_addresses as $item){

                $voter_address = $item['voter_address'];
                $_info = $validator_cache_impl->getDelegator($voter_address);
                if(!$_info){
                    continue;
                }

                $tmp = [];
                $tmp['voter_address'] = $voter_address;
                $tmp['votes'] = $item['votes'];
                $tmp['tokens'] = Arr::get($_info, 'tokens');

                $results[] = $tmp;
            }
        }

        $params = ValidatorCache::instance()->getParam();
        $params['asset_logo'] = \Image::formatCustomUrl($params['asset_logo'], $this->is_force_secure);

        return $this->success($results, [
            'staking_param' => $params,
        ]);
    }
}
