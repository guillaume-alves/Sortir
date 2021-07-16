<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(SortieRepository $sortieRepository): Response
    {
        $sorties = $sortieRepository->findAll();
        return $this->render('sorties/index.html.twig', [
            'sorties' => $sorties
        ]);
    }

    /**
     * @Route("/creer", name="app_creer")
     */
    public function creer(
        Request $request,
        EntityManagerInterface $entityManager,
        ParticipantRepository $participantRepository,
        EtatRepository $etatRepository,
        CampusRepository $campusRepository
    ) :  Response
    {
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortie->setOrganisateur($participantRepository->findOneBy(['id' => 1]));
        $sortie->setEtat($etatRepository->findOneBy(['id' => 1]));

        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid())
        {
            $dataCampusNom = $sortieForm->getData()->getCampus()->getNom();
            $campus = $campusRepository->findOneBy(['nom' => $dataCampusNom]);
            $sortie->setCampus($campus);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('sucess', 'Une nouvelle sortie a été crée');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('sorties/creer.html.twig', [
            'sortieForm' => $sortieForm->createView(),
        ]);
    }

}
