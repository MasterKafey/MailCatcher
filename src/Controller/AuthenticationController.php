<?php

namespace App\Controller;

use App\Business\UserBusiness;
use App\Entity\User;
use App\Form\Type\Authentication\LoginType;
use App\Form\Type\Authentication\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class AuthenticationController extends AbstractController
{
    #[Route(path: '/login', name: 'app_authentication_login')]
    public function login(
        UserBusiness $userBusiness
    ): Response
    {
        $form = $this->createForm(LoginType::class);

        return $this->render('Page/Authentication/login.html.twig', [
            'form' => $form->createView(),
            'display_register_link' => $userBusiness->isRegistrationEnabled(),
        ]);
    }

    #[NoReturn]
    #[Route(path: '/logout', name: 'app_authentication_logout')]
    public function logout(): void
    {
        throw new \RuntimeException('Should never be called');
    }

    #[Route(path: '/register')]
    public function register(
        Request                $request,
        EntityManagerInterface $entityManager,
        UserBusiness $userBusiness,
        TranslatorInterface $translator
    ): Response
    {
        if (!$userBusiness->isRegistrationEnabled()) {
            throw new NotFoundHttpException($translator->trans('registration_disabled', [], 'parameter'));
        }

        $user = new User();
        $form = $this
            ->createForm(RegisterType::class, $user)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_authentication_login');
        }

        return $this->render('Page/Authentication/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}