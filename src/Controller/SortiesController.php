<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     * @Security("is_granted('ROLE_USER')")
     */
    public function index(Request $request, SortieRepository $sortieRepository): Response
    {
        $sorties = $sortieRepository->findAllWithinOneMonth();

        $searchForm = $this->createForm('App\Form\SearchType');
        $searchForm->handleRequest($request);
        if($searchForm->isSubmitted() && $searchForm->isValid()) {
            if (($searchForm->getData())['campus'] != null) {
                $campus = $searchForm->getData()['campus'];
                $sorties = $sortieRepository->findAllWithinOneMonthAndCampus($campus);

            }

            elseif (($searchForm->getData())['search'] != null) {
            $mot = $searchForm->getData()['search'];
            $sorties = $sortieRepository->findkeywords($mot);
            }

            elseif (($searchForm->getData())['datedebut']!= null && ($searchForm->getData())['datefin']!= null) {
                $datedebut = $searchForm->getData()['datedebut'];
                $datefin = $searchForm->getData()['datefin'];
                $sorties = $sortieRepository->findperiod($datedebut, $datefin);
            }
            /*
             * elseif (($searchForm->getData())['datedebut']== null|| ($searchForm->getData())['datefin']== null){
                $this->addFlash('success', "Veuillez saisir les deux dates !");
             */
       }

        return $this->render('sorties/index.html.twig', [
            'sorties' => $sorties,
            'searchForm' => $searchForm->createView(),
        ]);
    }

    /**
     * @Route("/sortie/{id<[0-9]+>}/show", name="app_sortie_show")
     */
    public function show(Sortie $sortie): Response
    {
        $participants = $sortie->getParticipant();

        return $this->render('sorties/show.html.twig', [
            'sortie' => $sortie,
            'participants' => $participants
        ]);
    }

    /**
     * @Route("/sortie/create", name="app_sortie_create")
     */
    public function create(Request $request, EntityManagerInterface $em, ParticipantRepository $participantRepository, EtatRepository $etatRepository, CampusRepository $campusRepository) :  Response
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
            $sortie->setCampus($userCampus);
            $participant = $participantRepository->findOneBy(['id' => $userId] );
            $sortie->addParticipant($participant);

            $em->persist($sortie);
            $em->flush();

            $this->addFlash('success', 'Une nouvelle sortie a été créée');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('sorties/create.html.twig', [
            'sortieForm' => $sortieForm->createView(),
        ]);
    }

    /**
     * @Route("/sortie/{id<[0-9]+>}/edit", name="app_sortie_edit")
     */
    public function edit(Request $request, Sortie $sortie, EntityManagerInterface $em, ParticipantRepository $participantRepository) : Response
    {
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'Sortie mis à jour avec succès');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('sorties/create.html.twig', [
            'sortieForm' => $sortieForm->createView(),
        ]);

    }

    /**
     * @Route("/sortie/{id<[0-9]+>}/subscribe", name="app_sortie_subscribe")
     */
    public function subscribe(Sortie $sortie, ParticipantRepository $participantRepository, EntityManagerInterface $em): Response
    {
        //check registration closing date
        $dateNow = New \DateTime();
        if($sortie->getDateLimiteInscription()<$dateNow){
            $this->addFlash('success', "Il est trop tard pour s'inscrire à cette sortie !");
        }
        elseif ( $sortie->getEtat()->getLibelle() != 'Ouverte') {
            $this->addFlash('success', "Cette sortie n'est pas ouverte aux inscription !");
        }
        else {
            // retrieve userId
            $userId = $this->getUser()->getId();
            $participant = $participantRepository->findOneBy(['id' => $userId] );
            $sortie->addParticipant($participant);

            // persist data with ORM
            $em->persist($participant);
            $em->flush();

            // do anything else you need here, like send an email
            $this->addFlash('success', 'Inscription réalisée avec succès !');
        }
        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/sortie/{id<[0-9]+>}/unsubscribe", name="app_sortie_unsubscribe")
     */
    public function unsubscribe(Sortie $sortie, ParticipantRepository $participantRepository, EntityManagerInterface $em): Response
    {
        $userId = $this->getUser()->getId();
        $participant = $participantRepository->findOneBy(['id' => $userId] );
        $sortie->removeParticipant($participant);

        // persist data with ORM
        $em->persist($participant);
        $em->flush();

        // do anything else you need here, like send an email
        $this->addFlash('success', 'Désinscription réalisée avec succès !');
        return $this->redirectToRoute('app_home');
    }
}
