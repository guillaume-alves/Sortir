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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/participant", name="app_participant")
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
            return $this->redirectToRoute('app_participant');
        }

        return $this->render('participant/index.html.twig', [
            'registrationForm' => $form->createView(),
            'participants' => $participants
        ]);
    }



    /**
     * @Route("/ville/{id<[0-9]+>}/delete", name="app_participant_delete", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function delete(EntityManagerInterface $em, Participant $participant): Response
    {
        $em->remove($participant);
        $em->flush();

        $this->addFlash('info', 'Participant supprimé avec succès !');

        return $this->redirectToRoute('app_participant');
    }
}
