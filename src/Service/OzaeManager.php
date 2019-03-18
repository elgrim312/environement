<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 18/03/19
 * Time: 19:55
 */

namespace App\Service;


class OzaeManager
{
    private $apiKey;

    public function __construct($ozaeKey)
    {
        $this->apiKey = $ozaeKey;
        $this->baseUrl = "https://api.ozae.com/gnw/";
    }

    private function sendRequest(string $request)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl.$request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);

        return json_decode($result);
    }

    public function getArticleByKeyword(?string $keyword = "pollution", ?int $limit = 10)
    {
        $result = $this->sendRequest("articles?date=20180601__20180630&key=$this->apiKey&edition=en-us-ny&query=$keyword&hard_limit=$limit");

        dump($result);die;
    }
}