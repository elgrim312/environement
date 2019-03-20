<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 19/03/19
 * Time: 10:42
 */

namespace App\Service;

require_once __DIR__."/Extension/php-vadersentiment/vadersentiment.php";

class VaderSentimentManager
{
    private $sentimenter;

    public function __construct()
    {
        $this->sentimenter = new \SentimentIntensityAnalyzer();
    }

    public function getSentiment(string $value) : array
    {
        $value = strtr($value, [",", ";", "\\", ".", "%", "*"]);

       return $this->sentimenter->getSentiment($value);
    }
}