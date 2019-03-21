<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 18/03/19
 * Time: 19:46
 */

namespace App\Service;


class AirQualityManager extends RequestManager
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
            'br' => ['TR', 'TURKEY'],
        ];
    }


    public function getAirQuality(\DateTime $date, ?string $country = "fr")
    {
        $currentDate = $date->getTimestamp();
        $territory = $this->mapCountry[$country];

        $request = "/territory/$territory[0]/NUTS0/name/$territory[1]?timestamp=$currentDate&timeFormat=UTC";
        $headers = ["Content-Type: application/json", "x-access-token: $this->apiKey"];

        return $this->sendRequest($request,$headers, $this->baseUri);
    }



}