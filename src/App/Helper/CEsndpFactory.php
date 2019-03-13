<?php

namespace App\Helper;

use App\Helper\CCommon;
use GuzzleHttp\Client;

final class CEsndpFactory
{
    private static $token;

    /**
     * Call this method to get singleton
     *
     * @return \App\Helper\CEsndpFactory
     */
    public static function Instance()
    {
        static $inst = null;
        
        if ($inst === null) {
            $inst = new CEsndpFactory();
        }

        return $inst;
    }

    /**
     * Private constructor for singleton
     *
     */
    private function __construct(){ }

    /**
     * Get a token
     */ 
    public static function getToken($refresh=false)
    {
        if($refresh || is_null(self::$token)){
            $token = self::initEsndpApi();

            if(!is_null($token)){
                self::$token = $token;
            }

            return $token;
        } else {
            return self::$token;
        }        
    }

    /**
     * Get a prepared eSNDP API Client
     */ 
    public static function prepareEsndpApi(){
        $defaults = [
            'headers' => [
                'x-api-key' => CCommon::ESNDP_API_KEY,
                'Accept' => 'application/json'
            ]
        ];
        return new Client([
            'base_uri' => CCommon::ESNDP_API,
            'defaults' => $defaults
        ]);
    }

    private static function initEsndpApi(){
        $apiClient = self::prepareEsndpApi();
        $response = $apiClient->post('auth/login', [
            'json' => [
                'username' => CCommon::ESNDP_ADMIN_USERNAME,
                'password' => CCommon::ESNDP_ADMIN_PASSWORD
            ]
        ]);

        if ($response->getStatusCode() == '200'){
            $body = json_decode($response->getBody(), true);
            if(array_key_exists('token', $body['data'])){
                return $body['data']['token'];
            }
        }

        return null;
    }
}