<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CampusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
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

    /**
     * @Route("/creer", name="app_creer")
     */
    public function creer(Request $request, EntityManagerInterface $em, ParticipantRepository $participantRepository, EtatRepository $etatRepository, CampusRepository $campusRepository) :  Response
    {
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $user = $this->getUser();
        $userId = $user->getId();
        $userCampus = $user->getCampus();

        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid())
        {
            $sortie->setOrganisateur($participantRepository->findOneBy(['id' => $userId]));
            $sortie->setEtat($etatRepository->findOneBy(['id' => 1]));
            $sortie->setCampus($userCampus);

            $em->persist($sortie);
            $em->flush();

            $this->addFlash('sucess', 'Une nouvelle sortie a été crée');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('sorties/creer.html.twig', [
            'sortieForm' => $sortieForm->createView(),
        ]);
    }
}
