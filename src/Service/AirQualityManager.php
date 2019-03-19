<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 18/03/19
 * Time: 19:46
 */

namespace App\Service;


class AirQualityManager
{

    private $apiKey;

    private $mapCountry;

    private $baseUri;

    public function __construct($aircheckerKey)
    {
        $this->apiKey = $aircheckerKey;
        $this->baseUri = 'https://api.aircheckr.com';
        $this->mapCountry = [
            "fr" => ['FR', 'FRANCE'],
            'dl' => ['DE', 'GERMANY'],
            'us' => ['TR', 'TURKEY'],
        ];
    }

    private function sendRequest(string $request)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUri.$request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = "x-access-token: $this->apiKey";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);

        return json_decode($result);
    }

    public function getAirQuality(\DateTime $date, ?string $country = "fr")
    {
        $currentDate = $date->getTimestamp();
        $territory = $this->mapCountry[$country];

        $request = "/territory/$territory[0]/NUTS0/name/$territory[1]?timestamp=$currentDate&timeFormat=UTC";

        return $this->sendRequest($request);
    }



}