<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampusController extends AbstractController
{
    /**
     * @Route("/admin/campus", name="app_admin_campus")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(Request $request, EntityManagerInterface $em, CampusRepository $campusRepository): Response
    {
        $allCampus = $campusRepository->findAll();

        $campus = new Campus();
        $form = $this->createForm(CampusType::class, $campus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($campus);
            $em->flush();
            // do anything else you need here, like send an email
            $this->addFlash('success', 'Nouveau campus ajouté avec succès !');
            return $this->redirectToRoute('app_admin_campus');
        }

        return $this->render('campus/index.html.twig', [
            'form' => $form->createView(),
            'allCampus' => $allCampus
        ]);
    }

    /**
     * @Route("/admin/campus/{id<[0-9]+>}/delete", name="app_admin_campus_delete", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function delete(EntityManagerInterface $em, Campus $campus): Response
    {
        $em->remove($campus);
        $em->flush();

        $this->addFlash('info', 'Campus supprimé avec succès !');

        return $this->redirectToRoute('app_admin_campus');
    }
}