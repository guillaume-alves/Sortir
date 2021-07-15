<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Participant;
use App\Entity\ParticipantAvatar;
use App\Form\RegistrationFormType;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, CampusRepository $campusRepository, ParticipantRepository $participantAvatarRepository): Response
    {
        $user = new Participant();
        $avatar = new ParticipantAvatar();
        $user->setImage($avatar);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Retrieve campusId
            $dataCampusNom = $form->getData()->getCampus()->getNom();
            $campus = $campusRepository->findOneBy(['nom' => $dataCampusNom]);
            $user->setCampus($campus);
            $user->setActif(true);

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('success', 'Inscription réalisée avec succès, vous pouvez maintenant vous connecter !');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
