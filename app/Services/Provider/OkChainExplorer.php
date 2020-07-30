<?php

namespace App\Services\Provider;

use App\Services\Service;
use Illuminate\Support\Arr;

/**
 * Class OkChainExplorer
 * @package App\Services\Provider
 */
class OkChainExplorer extends Service
{
    protected $api_provider = 'https://www.okex.com';
    protected $api_provider_region = 'https://www.okex.me';
    protected $oklink_api_provider = 'https://www.oklink.com';
    private $api_key = 'LWIzMWUtNDU0Ny05Mjk5LWI2ZDA3Yjc2MzFhYmEyYzkwM2NjfDI3MDY1MTYzODY2NjY5Nzg=';

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
        /*
         * ["delegator_address"]=>
          string(46) "okchain16jr4ru6qsej8ejfdgayzx5lu2l5vrqhahvkd0f"
          ["validator_address"]=>
          array(2) {
            [0]=>
            string(53) "okchainvaloper1g7znsf24w4jc3xfca88pq9kmlyjdare6mph5rx"
            [1]=>
            string(53) "okchainvaloper1rk9umqd62zmeethwx4cg483ewnft6p8pmp5hke"
          }
          ["shares"]=>
          string(18) "260234071.12780866"
          ["tokens"]=>
          string(12) "162.00000000"
          ["is_proxy"]=>
          bool(false)
          ["total_delegated_tokens"]=>
          string(10) "0.00000000"
          ["proxy_address"]=>
          string(0) ""
          ["unbonding_token"]=>
          string(10) "8.00000000"
          ["completion_time"]=>
          string(30) "2020-08-04T12:03:40.924422993Z"
         */

        $response = $this->_getUrl("/okchain/v1/staking/delegators/{$delegator_address}", 'GET');
        if(!$response){
            return false;
        }

        $response2 = $this->_getUrl("/okchain/v1/staking/delegators/{$delegator_address}/unbonding_delegations", 'GET');
        if(!$response2){
            return false;
        }

        $response['unbonding_token'] = Arr::get($response2, 'quantity');
        $response['completion_time'] = Arr::get($response2, 'completion_time');

        return $response;

    }

    public function getVoteAddressByValidator($validator_address)
    {
        $response = $this->_getUrl("/okchain/v1/staking/validators/{$validator_address}/votes", 'GET');

        return $response;
    }

    public function poolAssets()
    {
        /*
         *  {
            not_bonded_tokens: "8.00000000",
            bonded_tokens: "4257859.54840000",
            }
         */
        $response = $this->_getUrl('/okchain/v1/staking/pool', 'GET');

        return $response;
    }

    public function getValidator($validator_address)
    {
        $response = $this->_getUrl("/okchain/v1/staking/validators/{$validator_address}", 'GET');

        return $response;
    }

    public function getValidatorRewards($validator_address)
    {
        $response = $this->_getUrl("/okchain/v1/distribution/validators/{$validator_address}/validator_commission", 'GET');

        return $response;
    }

    public function stakingParameters()
    {
        /*
         * {
              "unbonding_time": "300000000000",
              "max_bonded_validators": 21,
              "epoch": 252,
              "max_validators_to_vote": 30,
              "bond_denom": "tokt",
              "min_delegation": "0.00010000"
            }
         */
        $response = $this->_getUrl("/okchain/v1/staking/parameters", 'GET');

        return $response;
    }

    public function getAllProducer($offset = 0, $limit = 30)
    {
        $t = time() * 1000;
        $response = $this->_getUrlOnLink('/api/explorer/v1/okchain_test/validators', 'GET', [
            't' => $t,
            'offset' => $offset,
            'limit' => $limit,
            'q' => '',
        ]);

        return $response;
    }

    public function getProducer($validator_address)
    {
        $t = time() * 1000;
        $this->_getUrlOnLink("/api/explorer/v1/okchain_test/addresses/{$validator_address}", 'GET', [
            't' => $t,
        ]);
    }

    public function getAccountAsset($address, $asset)
    {
        $response = $this->_getUrl("/okchain/v1/accounts/{$address}", 'GET', [
            'symbol' => $asset,
        ]);

        if(Arr::get($response, 'code') != 0){
            return false;
        }

        $data = Arr::get($response, 'data');

        $asset_info = Arr::get($data, 'currencies.0');

        return $asset_info;
    }

    protected function _getUrl($path, $method, $data = [])
    {
        if(env('APP_ENV') == 'local'){
            if($method == 'GET'){
                $url = $this->api_provider_region . $path . ($data ? '?'. http_build_query($data) : '');
            }else{
                $url = $this->api_provider_region . $path;
            }
        }else{
            if($method == 'GET'){
                $url = $this->api_provider . $path . ($data ? '?'. http_build_query($data) : '');
            }else{
                $url = $this->api_provider . $path;
            }
        }

        return $this->network_impl->get($url, $method, $data);
    }

    protected function _getUrlOnLink($path, $method, $data = [])
    {
        if($method == 'GET'){
            $url = $this->oklink_api_provider . $path . ($data ? '?'. http_build_query($data) : '');
        }else{
            $url = $this->oklink_api_provider . $path;
        }

        $t = Arr::get($data, 't');
        return $this->network_impl->get($url, $method, $data, [
            'x-apiKey: '. $this->api_key,
            'App-Type: web',
            'devId: 3923dcf2-0a95-47de-a055-39f396e44bd1',
            'Host: www.oklink.com',
            'Referer: https://www.oklink.com/okchain-test/bp-list',
            'timeout: 10000',
            'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36',
            'x-cdn: https://static.bafang.com',
            'Cookie: locale=zh_CN; _okcoin_legal_currency=CNY; Hm_lpvt_5244adb4ce18f1d626ffc94627dd9fd7='. ($t/ 1000),
        ]);
    }

    public function proxyCall($path, $data, $method = 'GET')
    {
        return $this->_getUrl($path, $method, $data);
    }

    public static function instance()
    {
        return new static();
    }
}