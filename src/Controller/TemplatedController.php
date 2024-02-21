<?php

namespace App\Controller;

use App\Entity\TemplatedEmail;
use App\Form\Type\Email\TemplatedEmailType;
use App\Form\Type\Email\UpdateTemplatedEmailType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class TemplatedController extends AbstractController
{
    #[Route(path: '/template/create', name: 'app_template_create')]
    public function createTemplate(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $templatedEmail = new TemplatedEmail();
        $user = $security->getUser();
        $templatedEmail->setUser($user);
        $form = $this->createForm(TemplatedEmailType::class, $templatedEmail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($templatedEmail);
            $entityManager->flush();

            return $this->redirectToRoute('app_template_show', ['id' => $templatedEmail->getId()]);
        }

        return $this->render('Page/Template/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/template/list', name: 'app_template_list')]
    public function listTemplates(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $templates = $entityManager->getRepository(TemplatedEmail::class)->findBy(['user' => $user]);

        return $this->render('Page/Template/list.html.twig', [
            'templates' => $templates,
        ]);
    }
    #[Route(path: '/template/{id}', name: 'app_template_show')]
    public function showTemplate(TemplatedEmail $template): Response
    {
        return $this->render('Page/Template/show.html.twig', [
            'template' => $template,
        ]);
    }

    #[Route('/template/{id}/delete', name: 'app_template_delete')]
    public function deleteTemplate(TemplatedEmail $template, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($template);
        $entityManager->flush();

        return $this->redirectToRoute('app_template_list');
    }

    #[Route('/template/{id}/send', name: 'app_template_send')]
    public function sendTemplate(Request $request, TemplatedEmail $template, MailerInterface $mailer, Security $security): Response
    {
        $user = $security->getUser();

        $email = $user->getEmail();
        $userId = $user->getId();

        $templateBody = str_replace('{{email}}', $email, $template->getBody());
        $templateBody = str_replace('{{user_id}}', $userId, $templateBody);

        $email = (new Email())
            ->from("kyllian.claveau@gmail.com")
            ->to($template->getRecipient())
            ->subject($template->getSubject())
            ->html($templateBody);

        $mailer->send($email);

        return new Response('Email envoyé avec succès');
    }


    #[Route('/template/{id}/edit', name: 'app_template_edit')]
    public function editTemplate(Request $request, TemplatedEmail $template, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UpdateTemplatedEmailType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_template_show', ['id' => $template->getId()]);
        }

        return $this->render('Page/Template/modify.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}