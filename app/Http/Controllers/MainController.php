<?php

namespace App\Http\Controllers;

use App\Services\Persistence\ValidatorCache;
use App\Services\Provider\OkChainExplorer;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class MainController extends Controller
{
    public function index()
    {
        $validator_cache_impl = ValidatorCache::instance();
        $lists = $validator_cache_impl->getAllValidators();

        $pool_assets = OkChainExplorer::instance()->poolAssets();
        $bonded_tokens = Arr::get($pool_assets, 'bonded_tokens');

        $params = ValidatorCache::instance()->getParam();
        $params['asset_logo'] = \Image::formatCustomUrl($params['asset_logo'], $this->is_force_secure);

        return $this->success($lists, [
            'pool_bonded_tokens' => $bonded_tokens,
            'staking_param' => $params,
        ]);
    }

    public function voteStore(Request $request)
    {
        $user_address = $request->get('user_address');

        if(!$user_address){
            return $this->error(1, 'error');
        }

        $delegator_info = OkChainExplorer::instance()->getDelegator($user_address);
        $user_vote_addresses = Arr::get($delegator_info, 'validator_address');
        if(!$user_vote_addresses){
            return $this->error(1, 'error');
        }

        $validator_cache_impl = ValidatorCache::instance();
        foreach ($user_vote_addresses as $validator_address){
            $validator_cache_impl->getVoteNumByValidator($validator_address, true);
            $validator_cache_impl->getDepositToken($validator_address, true);
        }

        $validator_cache_impl->getAllValidators(true);

        return $this->success();
    }

}
