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
            "de" => "de-de",
            "us" => "en-us-ny",
            "br" => "en-us-sl"
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

    public function getRelatedArticles($startDate, $endDate, $country, $type)
    {
        $startDate = $startDate->format('Ymd');
        $endDate = $endDate->format('Ymd');

        $data = [];
        $keywords = [
            'biodiversity' => [
                'fr' => ['biodiversité', 'fr-fr'],
                'us' => ['biodiversity', 'en-us-ny'],
                'de' => ['biodiversität', 'de-de'],
                'br' => ['biodiversity', 'en-us-df']
            ],
            'airQualiy' => [
                'fr' => ["pollution de l'air", 'fr-fr'],
                'us' => ['air pollution', 'en-us-ny'],
                'de' => ['luftverschmutzung', 'de-de'],
                'br' => ['air pollution', 'en-us-df'],
            ],
            'waterPollution' => [
                'fr' => ["pollution de l'eau", "fr-fr"],
                'us' => ["water", "en-us-my"],
                'de' => ["wasser", "de-de"],
                'br' => ["water", "en-us-df"],
            ],
            'climat' => [
                'fr' => ["réchauffement climatique", "fr-fr"],
                'us' => ["global warming", "en-us-my"],
                'de' => ["Globale Erwärmung", "de-de"],
                'br' => ["global warming", "en-us-df"],
            ]
        ];

        $lang = $keywords[$type][$country][1];
        $word = $keywords[$type][$country][0];

        return $this->sendRequest("articles?date=".$startDate."__".$endDate."&key=$this->apiKey&edition=$lang&query=$word&hard_limit=1", ['Content-Type: application/json'], $this->baseUrl );
    }
}