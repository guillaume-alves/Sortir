<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VilleController extends AbstractController
{
    /**
     * @Route("/villez", name="app_villee")
     */
    public function indexx(VilleRepository $villeRepository): Response
    {
        $villes = $villeRepository->findAll();
        return $this->render('ville/index.html.twig', [
            'villes' => $villes,
        ]);
    }

    /**
     * @Route("/ville", name="app_ville")
     */
    public function index(Request $request, EntityManagerInterface $em, VilleRepository $villeRepository): Response
    {
        $villes = $villeRepository->findAll();

        $ville = new Ville();
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ville);
            $em->flush();
            // do anything else you need here, like send an email
            $this->addFlash('success', 'Nouvelle ville ajoutée avec succès !');
            return $this->redirectToRoute('app_ville');
        }

        return $this->render('ville/index.html.twig', [
            'form' => $form->createView(),
            'villes' => $villes
        ]);
    }
}
