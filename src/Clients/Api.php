<?php

class Api implements TelegramInterface
{
    private string $keepTokenBot;
    public static array $configCurl = [CURLOPT_RETURNTRANSFER => true];


    public function __construct(string $botToken)
    {
        $this->keepTokenBot = "https://api.telegram.org/bot$botToken/";
    }

    
    public function Get(string $method, array|string $params = "") : CurlHandle 
    {
        $params = is_array($params) ? http_build_query($params) : $params;
        $curlObject = curl_init( $this->keepTokenBot . "$method?$params" );
        curl_setopt_array( $curlObject, self::$configCurl );
        return $curlObject;
    }



    public function Post(string $method, array|string $postField = "") : CurlHandle
    {
        $curlObject = curl_init( $this->keepTokenBot . "$method" );
        curl_setopt_array( $curlObject, self::$configCurl );
        curl_setopt( $curlObject, CURLOPT_POSTFIELDS, $postField );
        return $curlObject;
    }



    public function CheckerResult(object $curlObject, string $method) : object 
    {
        $objectJson = json_decode( curl_exec($curlObject) );
        if (!$objectJson->ok) {
            throw new exception( "HAS ERROR OCURRED (API TELEGRAM): RETURN BOOL 'FALSE' ($method)" );
        }
        return $objectJson;
    }



    public function executeRetrys(object $curlObject) : object 
    {
        for ($countRetrys = 0; 6 >= $countRetrys; $countRetrys++) {
            $objectJson = json_decode( curl_exec($curlObject) );
            if ($objectJson->ok) {
                break;
            }
        }
        
        if (!$objectJson->ok) { 
            throw new exception( "HAS ERROR OCURRED (API TELEGRAM): After 7 attempts it still returns 'false'" );
        }
        return $objectJson;
    }
}
