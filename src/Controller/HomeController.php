<?php

namespace App\Controller;

use App\Service\OzaeManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(OzaeManager $ozaeManager)
    {
        $biodiversityScore = $ozaeManager->getComputedForBiodiversity();

        return $this->render('index.html.twig', [
            'biodiversityScore' => $biodiversityScore
        ]);
    }
}
