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

    private $sentimenter;

    public function __construct($ozaeKey, VaderSentimentManager $vaderSentimentManager)
    {
        $this->apiKey = $ozaeKey;
        $this->baseUrl = "https://api.ozae.com/gnw/";
        $this->sentimenter = $vaderSentimentManager;
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

    public function getComputedForBiodiversity(?string $keyword = "pollution", ?int $limit = 20)
    {
        $articles = $this->sendRequest("articles?date=20180601__20180630&key=$this->apiKey&edition=en-us-ny&query=$keyword&hard_limit=$limit");
        $compound = 0;

        foreach ($articles->articles as $article) {
            $compound = $compound + $this->sentimenter->getSentiment($article->name)["compound"];
        }

         $div = count($articles->articles) ? count($articles->articles) : 1;
         $compound = $compound / $div;

        return $compound;
    }
}