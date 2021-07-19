<?php

namespace App\Controller;

use App\Entity\CritereRechercheSorties;
use App\Form\RechechesortieFormType;
use App\Repository\SortieRepository;
use App\Repository\CampusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    /**
     * @Route("/sorties", name="app_home")
     */
    public function index(SortieRepository $sortieRepository, CampusRepository $campusRepository, Request $request): Response
    {
        $search= new CritereRechercheSorties();
        $form= $this->createForm(RechechesortieFormType::class, $search);
        $form->handleRequest($request);

        $sorties = $sortieRepository->findAll();
        $campus = $campusRepository->findAll();
        return $this->render('sorties/index.html.twig', [
            'sorties' => $sorties,
            'campus' => $campus,
            'form' => $form->createView()
        ]);

    }

//    /**
//     * @Route("/sorties", name="test")
//     */
//    public function affichersorties(SortieRepository $sortieRepository, CampusRepository $campusRepository): Response
//    {
//        $sorties = $sortieRepository->findAll();
//        $campus = $campusRepository->findAll();
//        return $this->render('sorties/recherchesortie.html.twig', [
//            'sorties' => $sorties,
//            'campus' => $campus
//        ]);
//
//    }

}
