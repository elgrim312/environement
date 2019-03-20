<?php

namespace App\Controller;

use App\Service\AirQualityManager;
use App\Service\Co2SignalManager;
use App\Service\DateManager;
use App\Service\OzaeManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/airquality", name="app_index_airquality", methods={"POST"})
     */
    public function getAirQuality(AirQualityManager $airQualityManager, DateManager $dateManager, Request $request)
    {
        $country = $request->get('country');
        $date = $request->get('date');

        $date = ($date == 0) ? $dateManager->getLastDay() : $dateManager->getLastNMonth($date);

        return new JsonResponse([
            'data' => $airQualityManager->getAirQuality($date, $country),
        ]);
    }

    /**
     * @Route("/biodiversityScore", name="app_index_biodiversity", methods={"POST"})
     */
    public function getBiodiversity(OzaeManager $ozaeManager, Request $request, DateManager $dateManager)
    {
        $startDate = $dateManager->getLastNMonth($request->request->get('date'));
        $country = $request->request->get('country');
        $currentDate = new \DateTime();

        return new JsonResponse([
            'data' => $ozaeManager->getComputedForSentence($startDate, $currentDate, $country)
        ]);
    }

    /**
     * @Route("/carbonEvolution", name="app_index_carbon_evolution", methods={"POST"})
     */
    public function getCarbonEvolution(Co2SignalManager $co2SignalManager, Request $request)
    {
        $country = strtoupper($request->request->get('country'));

        return new JsonResponse([
            'data' => $co2SignalManager->getCarbonProgram($country)
        ]);
    }
}
