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

    private $mapLang;

    public function __construct($ozaeKey, VaderSentimentManager $vaderSentimentManager)
    {
        $this->apiKey = $ozaeKey;
        $this->baseUrl = "https://api.ozae.com/gnw/";
        $this->sentimenter = $vaderSentimentManager;
        $this->mapLang = [
            "fr" => "fr-fr",
            "dl" => "de-de",
            "us" => "en-us-ny",
        ];
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

    public function getComputedForSentence(\DateTime $startDate, \DateTime $endDate, ?string $keyword = "pollution", ?int $limit = 20, ?string $country = "us")
    {
        //y-m-d
        $startDate = ($startDate == $endDate) ? date('Ymd', strtotime('-6 month')) : $endDate->format('Ymd');
        $endDate = $endDate->format('Ymd');
        $country = $this->mapLang[$country];

        $articles = $this->sendRequest("articles?date=".$startDate."__".$endDate."&key=$this->apiKey&edition=$country&query=$keyword&hard_limit=$limit");

        $compound = 0;

        foreach ($articles->articles as $article) {
            $compound = $compound + $this->sentimenter->getSentiment($article->name)["compound"];
        }

         $div = count($articles->articles) ? count($articles->articles) : 1;
         $compound = $compound / $div;

        return $compound;
    }
}