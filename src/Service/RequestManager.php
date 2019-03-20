<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 20/03/19
 * Time: 13:17
 */

namespace App\Service;


class RequestManager
{
    protected function sendRequest($request, array $headers, $baseUrl)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl.$request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);

        return json_decode($result);
    }
}