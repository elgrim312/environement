<?php

namespace App\Controller;

use App\Service\AirQualityManager;
use App\Service\Co2SignalManager;
use App\Service\DateManager;
use App\Service\OzaeManager;
use App\Service\WaterManager;
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

        $date = $dateManager->getLastDay();

        return new JsonResponse([
            'data' => $airQualityManager->getAirQuality($date, $country),
        ], 200);
    }

    /**
     * @Route("/biodiversityScore", name="app_index_biodiversity", methods={"POST"})
     */
    public function getBiodiversity(OzaeManager $ozaeManager, Request $request, DateManager $dateManager)
    {
        $country = $request->request->get('country');
        $date = $request->request->get('date');
        $startDate = ($date == 0) ? $dateManager->getLastDay() : $dateManager->getLastNMonth($request->request->get('date'));

        $currentDate = new \DateTime();

        return new JsonResponse([
            'data' => $ozaeManager->getComputedForSentence($startDate, $currentDate, $country)
        ], 200);
    }

    /**
     * @Route("/carbonEvolution", name="app_index_carbon_evolution", methods={"POST"})
     */
    public function getCarbonEvolution(Co2SignalManager $co2SignalManager, Request $request)
    {
        $country = strtoupper($request->request->get('country'));

        return new JsonResponse([
            'data' => $co2SignalManager->getCarbonProgram($country)
        ], 200);
    }

    /**
     * @Route("/related-article", name="app_index_related_articles", methods={"POST"})
     */
    public function getRelatedArticle(OzaeManager $ozaeManager, Request $request, DateManager $dateManager)
    {
        $country = $request->request->get('country');
        $date = $request->request->get('date');

        switch ($date) {
            case "0" :
                $endDate = new \DateTime();
                $startDate = $dateManager->getLastNMonth("-6");
            break;

            case "-6" :
                $endDate = $dateManager->getLastNMonth("-6");
                $startDate = $dateManager->getLastNMonth("-12");
            break;

            case "-12" :
                $endDate = $dateManager->getLastNMonth("-12");
                $startDate = $dateManager->getLastNMonth("-24");
            break;

            case "-24" :
                $endDate = $dateManager->getLastNMonth("-24");
                $startDate = $dateManager->getLastNMonth("-32");
            break;
        }

        return new JsonResponse([
            'data' => $ozaeManager->getRelatedArticles($startDate, $endDate, $country)
        ], 200);
    }

    /**
     * @Route("/water-quality", name="app_index_water_quality", methods={"POST"})
     */
    public function getWaterValue(WaterManager $waterManager, Request $request, DateManager $dateManager)
    {
        $date = $request->request->get('date');
        $country = $request->request->get('request');

        $currentDate =$date == 0 ? new \DateTime() : $dateManager->getLastNMonth($date);

        return new JsonResponse([
            'data' => $waterManager->getWaterValue($country, $currentDate->format("Y")),
        ], 200);
    }
}
