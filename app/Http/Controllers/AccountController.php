<?php

namespace App\Http\Controllers;

use App\Services\Persistence\ValidatorCache;
use App\Services\Provider\OkChainExplorer;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AccountController extends Controller
{
    public function info(Request $request)
    {
        $user_address = $request->get('user_address');

        // 查询余额
        $validator_cache_impl = ValidatorCache::instance();
        $params = $validator_cache_impl->getParam();

        $bond_denom = Arr::get($params, 'bond_denom');
        //
        $asset_info = $validator_cache_impl->getAccountAsset($user_address, $bond_denom);

        $delegator_info = $validator_cache_impl->getDelegator($user_address);

        $available = Arr::get($asset_info, 'available');

        $delegator_info['asset_balance'] =  $available;
        $params['asset_logo'] = \Image::formatCustomUrl($params['asset_logo'], $this->is_force_secure);

        return $this->success($delegator_info, [
            'staking_param' => $params,
        ]);
    }

    // 用户的投票
    public function votes(Request $request)
    {
        $user_address = $request->get('user_address');

        $delegator_info = OkChainExplorer::instance()->getDelegator($user_address);

        $user_vote_addresses = Arr::get($delegator_info, 'validator_address');

        $validator_cache_impl = ValidatorCache::instance();
        $lists = $validator_cache_impl->getAllValidators();

        $validator_maps = \Arr::build($lists, function($key, $val){
            return [$val['operator_address'], $val];
        });

        $user_vote_validators = [];
        foreach ($user_vote_addresses as $validator_address){
            $validator_info = \Arr::get($validator_maps, $validator_address);
            $user_vote_validators[] = $validator_info;
        }

        return $this->success($user_vote_validators);
    }
}
