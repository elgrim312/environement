<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 18/03/19
 * Time: 19:55
 */

namespace App\Service;


class OzaeManager extends RequestManager
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

    public function getComputedForSentence(\DateTime $startDate, \DateTime $endDate,?string $country = "us", ?string $keyword = "pollution", ?int $limit = 50)
    {
        $startDate = $startDate->format('Ymd');
        $endDate = $endDate->format('Ymd');
        $country = $this->mapLang[$country];

        $articles = $this->sendRequest("articles?date=".$startDate."__".$endDate."&key=$this->apiKey&edition=$country&query=$keyword&hard_limit=$limit", ['Content-Type: application/json'], $this->baseUrl);

        $compound = 0;

        foreach ($articles->articles as $article) {
            $compound = $compound + $this->sentimenter->getSentiment($article->name)["compound"];
        }

         $div = count($articles->articles) ? count($articles->articles) : 1;
         $compound = $compound / $div;

        return $compound;
    }

    public function getRelatedArticle($startDate, $endDate, $country)
    {
        $startDate = $startDate->format('Ymd');
        $endDate = $endDate->format('Ymd');
        $article = $this->sendRequest("articles?date=".$startDate."__".$endDate."&key=$this->apiKey&edition=$country&query=pollution&hard_limit=1", ['Content-Type: application/json'], $this->baseUrl );

        dump($article);die;
    }
}