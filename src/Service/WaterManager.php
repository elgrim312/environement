<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 21/03/19
 * Time: 15:05
 */

namespace App\Service;


class WaterManager
{
    public function getWaterValue($country, $date)
    {
        $waterRaises = json_decode(file_get_contents('../public/water.json'));

        return $waterRaises->{$country}->{$date};
    }
}