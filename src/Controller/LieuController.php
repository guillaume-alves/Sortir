<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    /**
     * @Route("/lieu", name="app_lieu")
     */
    public function index(LieuRepository $lieuRepository): Response
    {
        $lieux = $lieuRepository->findAll();
        return $this->render('lieu/index.html.twig', [
            'lieux' => $lieux,
        ]);
    }

    /**
     * @Route("/lieu/create", name="app_lieu_create")
     */
    public function create(Request $request, EntityManagerInterface $em, LieuRepository $lieuRepository): Response
    {
        $lieu = new Lieu();
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($lieu);
            $em->flush();
            // do anything else you need here, like send an email
            $this->addFlash('success', 'Nouveau Lieu ajouté avec succès !');
            return $this->redirectToRoute('app_lieu');
        }

        return $this->render('lieu/create.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
