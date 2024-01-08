<?php

namespace App\Controller;

use App\Entity\Mail;
use App\Form\Type\ConfirmType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

#[Route('/mail')]
class MailController extends AbstractController
{
    #[Route(path: '/{id}', name: 'app_mail_show')]
    public function show(
        Request                $request,
        Mail                   $mail,
        EntityManagerInterface $entityManager
    ): Response
    {
        if (!$request->isXmlHttpRequest()) {
            throw new NotFoundHttpException();
        }

        if (!$mail->isSeen()) {
            $mail->setIsSeen(true);
            $entityManager->persist($mail);
            $entityManager->flush();
        }

        return $this->json([
            'view' => $this->renderView('Page/Mail/show.html.twig', [
                'mail' => $mail,
            ]),
        ]);
    }

    #[Route(path: '/send/{id}', name: 'app_mail_send', methods: ['GET'])]
    public function sendMail(Mail $mail, MailerInterface $mailer): RedirectResponse
    {
        try {
            $email = (new Email())
                ->from(new Address($mail->getFrom()))
                ->to(new Address($mail->getTo()))
                ->subject($mail->getSubject())
                ->text($mail->getText())
                ->html($mail->getHtml());

            $mailer->send($email);

            return $this->redirectToRoute('app_inbox_list');
        } catch (\Exception $e) {
            return $this->redirectToRoute('app_inbox_show');
        }
    }

    #[Route(path: '/{id}/content', name: 'app_mail_show_content')]
    public function content(Mail $mail, ?Profiler $profiler = null): Response
    {
        if (null !== $profiler) {
            $profiler->disable();
        }
        return new Response($mail->getHtml());
    }

    #[Route(path: '/{id}/delete', name: 'app_mail_delete')]
    public function delete(EntityManagerInterface $entityManager, Mail $mail, Request $request): Response
    {
        $inbox = $mail->getInbox();

        $form = $this
            ->createForm(ConfirmType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($mail);
            $entityManager->flush();

            return $this->redirectToRoute('app_inbox_show', [
                'id' => $inbox->getId(),
            ]);
        }

        return $this->render('Page/Mail/delete.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}