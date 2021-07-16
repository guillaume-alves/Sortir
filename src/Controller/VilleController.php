<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VilleController extends AbstractController
{

    /**
     * @Route("/admin/ville", name="app_admin_ville")
     * @Security("is_granted('ROLE_ADMIN')")
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
            return $this->redirectToRoute('app_admin_ville');
        }

        return $this->render('ville/index.html.twig', [
            'form' => $form->createView(),
            'villes' => $villes
        ]);
    }

    /**
     * @Route("/admin/ville/{id<[0-9]+>}/delete", name="app_admin_ville_delete", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function delete(EntityManagerInterface $em, Ville $ville): Response
    {
        $em->remove($ville);
        $em->flush();

        $this->addFlash('info', 'Ville supprimée avec succès !');

        return $this->redirectToRoute('app_admin_ville');
    }
}
