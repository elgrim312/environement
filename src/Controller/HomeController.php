<?php

namespace App\Controller;

use App\Service\AirQualityManager;
use App\Service\DateManager;
use App\Service\OzaeManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(OzaeManager $ozaeManager, AirQualityManager $airQualityManager, DateManager $dateManager)
    {
        $currentDate = new \DateTime();
        $airQuality = $airQualityManager->getAirQuality($dateManager->getLastDay());
        dump($airQuality);die;

        $biodiversityScore = $ozaeManager->getComputedForSentence($currentDate, $currentDate);
        $waterQuality = $ozaeManager->getComputedForSentence($currentDate, $currentDate, "pollution eau");

        return $this->render('index.html.twig', [
            'biodiversityScore' => $biodiversityScore
        ]);
    }
}
