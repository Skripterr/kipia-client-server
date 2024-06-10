<?php

namespace Application\Core\Utils;

/**
 * 
 * Class Http, making request using curl.
 *
*/
class Http
{
    /**
	 * 
     * Doing GET request.
	 * 
     */
    public static function get(string $url, array $httpQuery = [], array $httpHeaders = [])
    {
        if (!empty($httpQuery)) 
        {
            $url = $url . '?' . http_build_query($httpQuery);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if ($httpHeaders)
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
        }

        $response = curl_exec($ch);

        curl_close($ch);
        return $response;
    }

    /**
	 * 
     * Doing POST request, receive post fields. 
	 * 
     */
    public static function post(string $url, array $postFields = [], array $httpHeaders = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if ($httpHeaders)
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
        }

        if ($postFields != [])
        {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
        }

        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);

        $response = curl_exec($ch);

        curl_close($ch);
        return $response;
    }
}

?>