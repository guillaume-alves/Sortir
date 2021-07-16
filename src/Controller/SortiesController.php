<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use App\Repository\CampusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    /**
     * @Route("/sorties", name="app_home")
     */
    public function affichersorties(SortieRepository $sortieRepository, CampusRepository $campusRepository): Response
    {
        $sorties = $sortieRepository->findAll();
        $campus = $campusRepository->findAll();
        return $this->render('sorties/index.html.twig', [
            'sorties' => $sorties,
            'campus' => $campus
        ]);
    }

}
