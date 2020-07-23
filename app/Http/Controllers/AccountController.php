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
        $params = ValidatorCache::instance()->getParam();
        $bond_denom = Arr::get($params, 'bond_denom');
        !$bond_denom && $bond_denom = config('app.bond_denom');
        //
        $asset_info = OkChainExplorer::instance()->getAccountAsset($user_address, $bond_denom);
        $asset_logo = config('app.bond_denom_logo');

        $delegator_info = OkChainExplorer::instance()->getDelegator($user_address);

        $available = Arr::get($asset_info, 'available');

        $delegator_info['asset_balance'] =  $available;
        $asset_logo = \Image::formatCustomUrl($asset_logo, $this->is_force_secure);

        return $this->success($delegator_info, [
            'bond_denom' => $bond_denom,
            'asset_logo' => $asset_logo,
        ]);
    }
}
