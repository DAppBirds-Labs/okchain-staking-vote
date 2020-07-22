<?php

namespace App\Services\Provider;

use App\Services\Service;

/**
 * Class OkChainExplorer
 * @package App\Services\Provider
 */
class OkChainExplorer extends Service
{
    protected $api_provider = 'https://www.okex.com';
    protected $network_impl;

    public function __construct()
    {
        $this->network_impl = Service\NetworkService::instance();
    }

    public function validators()
    {
        return $this->_getUrl('/okchain/v1/staking/validators', 'GET', [
            'status' => 'all'
        ]);
    }

    public function getDelegator($delegator_address)
    {
        $response = $this->_getUrl("/okchain/v1/staking/delegators/{$delegator_address}", 'GET');
        if(!$response){
            return false;
        }

        $response2 = $this->_getUrl("/okchain/v1/staking/delegators/{$delegator_address}/unbonding_delegations", 'GET');
        if(!$response2){
            return false;
        }

        $response['unbonding_token'] = \Arr::get($response2, 'quantity');
        $response['completion_time'] = \Arr::get($response2, 'completion_time');

        return $response;

    }

    protected function _getUrl($path, $method, $data = [])
    {
        if($method == 'GET'){
            $url = $this->api_provider . $path . ($data ? '?'. http_build_query($data) : '');
        }else{
            $url = $this->api_provider . $path;
        }

        return $this->network_impl->get($url, $method, $data);
    }
}