<?php

namespace App\Http\Controllers;

use App\Services\Persistence\ValidatorCache;
use App\Services\Provider\OkChainExplorer;
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
}
