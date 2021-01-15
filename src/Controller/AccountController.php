<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account.login")
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * @Route("/logout", name="account.logout")
     */
    public function logout()
    {
    }

    /**
     * @Route("/register", name="account.register")
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());

            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();
        }

        return  $this->render('account/signup.html.twig', [
            'form' => $form->createView()

        ]);
    }

    /**
     * @Route("/account", name="account.index")
     */
    public function myAcccount()
    {
        return $this->render('user/ .html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
