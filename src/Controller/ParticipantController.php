<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\ParticipantAvatar;
use App\Form\RegistrationFormType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/participant/{id<[0-9]+>}/show", name="app_participant_show")
     */
    public function show(Participant $participant): Response
    {
        return $this->render('participant/show.html.twig', [
            'participant' => $participant
        ]);
    }

    /**
     * @Route("/admin/participant", name="app_admin_participant")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em, ParticipantRepository $participantRepository): Response
    {
        $participants = $participantRepository->findAll();

        $participant = new Participant();
        $avatar = new ParticipantAvatar();
        $form = $this->createForm(RegistrationFormType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatar->setImageName("avatarNameByDefault");
            $participant->setImage($avatar);
            $participant->setActif(true);

            // encode the plain password
            $participant->setPassword(
                $passwordHasher->hashPassword(
                    $participant,
                    $form->get('plainPassword')->getData()
                )
            );

            // persist data with ORM
            $em->persist($participant);
            $em->flush();

            // do anything else you need here, like send an email
            $this->addFlash('success', 'Nouveau participant créé avec succès !');
            return $this->redirectToRoute('app_admin_participant');
        }

        return $this->render('participant/index.html.twig', [
            'registrationForm' => $form->createView(),
            'participants' => $participants
        ]);
    }

    /**
     * @Route("/admin/participant/{id<[0-9]+>}/desactivate", name="app_admin_participant_desactivate", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function disable(EntityManagerInterface $em, Participant $participant): Response
    {
        //Activate or disable user
        if ($participant->getActif()) {
            $participant->setActif(false);
        }
        else $participant->setActif(true);
        $em->persist($participant);
        $em->flush();

        $this->addFlash('info', 'Status du participant mis à jour avec succès !');

        return $this->redirectToRoute('app_admin_participant');
    }

    /**
     * @Route("/admin/participant/{id<[0-9]+>}/delete", name="app_admin_participant_delete", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function delete(EntityManagerInterface $em, Participant $participant): Response
    {
        $em->remove($participant);
        $em->flush();

        $this->addFlash('info', 'Participant supprimé avec succès !');

        return $this->redirectToRoute('app_admin_participant');
    }
}
