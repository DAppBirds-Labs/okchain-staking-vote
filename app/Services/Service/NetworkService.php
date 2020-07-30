<?php

namespace App\Services\Service;

use App\Services\Service;

/**
 * Class NetworkService
 * @package App\Services\Service
 */
class NetworkService extends Service
{
    static protected $ch = null;

    public function get($url, $method = 'GET', $data = [], $headers = [])
    {
        if (!isset(self::$ch)) {
            self::$ch = curl_init();
        }

        $ch = self::$ch;
        $start_time = microtime(true);
        $response = '';

        $headers = array_merge([
            'Connection: Keep-Alive',
            'Keep-Alive: 300',
            'user-agent: Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1',
        ], $headers);

        for ($i = 0, $l = 3; $i < $l; ++ $i) {
//            curl_setopt($ch, CURLOPT_FORBID_REUSE, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            if($method == 'POST'){
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }

            $response = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 == $status) {
                break;
            }
        }

        $end_time = microtime(true);
        $run_time = $end_time - $start_time;

        $result = json_decode($response, true) ?: false;

        return $result;
    }
}