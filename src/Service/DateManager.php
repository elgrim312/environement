<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 19/03/19
 * Time: 23:05
 */

namespace App\Service;


class DateManager
{
    public function getLastDay() : \DateTime
    {
        $start = new \DateTime();

        return $start->modify('-1 days');
    }
}