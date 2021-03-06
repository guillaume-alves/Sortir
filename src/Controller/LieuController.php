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
    public function index(Request $request, EntityManagerInterface $em, LieuRepository $lieuRepository): Response
    {
        $lieux = $lieuRepository->findAll();

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

        return $this->render('lieu/index.html.twig', [
            'lieux' => $lieux,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lieu/{id<[0-9]+>}/delete", name="app_lieu_delete", methods={"GET"})
     */
    public function delete(EntityManagerInterface $em, Lieu $lieu): Response
    {
        $em->remove($lieu);
        $em->flush();

        $this->addFlash('info', 'Lieu supprimé avec succès !');

        return $this->redirectToRoute('app_lieu');
    }


}
