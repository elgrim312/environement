<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 20/03/19
 * Time: 11:43
 */

namespace App\Service;


class Co2SignalManager extends RequestManager
{
    private $apiKey;

    private $baseUri;

    public function __construct($c02Signal)
    {
        $this->apiKey = $c02Signal;
        $this->baseUri = "https://api.co2signal.com/v1/latest?";
    }

    public function getCarbonProgram($country = "FR")
    {
        return $this->sendRequest("countryCode=$country", ["auth-token: $this->apiKey"], $this->baseUri);
    }
}