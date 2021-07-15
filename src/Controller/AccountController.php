<?php

namespace App\Controller;

use App\Entity\ParticipantAvatar;
use App\Form\ChangePasswordFormType;
use App\Form\ParticipantAvatarFormType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="app_account")
     */
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    /**
     * @Route("/account/edit", name="app_account_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(RegistrationFormType::class, $user, ['firstRegistration'=>false]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Compte mis à jour avec succès');
            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/edit.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/account/avatar", name="app_account_edit_avatar", methods={"GET", "POST"})
     */
    public function avatar(Request $request, EntityManagerInterface $em): Response
    {
        $avatar = $this->getUser()->getImage();
        $form = $this->createForm(ParticipantAvatarFormType::class, $avatar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Avatar mis à jour avec succès');
            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/avatar.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/account/change-password", name="app_account_change_password", methods={"GET", "POST"})
     */
    public function changePassword(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user=$this->getUser();
        $form = $this->createForm(ChangePasswordFormType::class, null, ['currentPasswordIsRequired'=>true]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword($user, $form['plainPassword']->getData())
            );
            $em->flush();
            $this->addFlash('success', 'Mot de passe mis à jour avec succès !');
            return $this->redirectToRoute('app_account');
        }
        return $this->render('account/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }




}
