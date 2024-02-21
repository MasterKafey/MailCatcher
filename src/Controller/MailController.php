<?php

namespace App\Controller;

use App\Entity\Mail;
use App\Form\Type\ConfirmType;
use App\Utils\MailParser;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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
                'parser' => new MailParser($mail),
            ]),
        ]);
    }

    #[Route(path: '/send/{id}', name: 'app_mail_send')]
    public function sendMail(Mail $mail, MailerInterface $mailer): JsonResponse
    {
        try {
            $email = (new Email())
                ->from(new Address($mail->getFrom()))
                ->to(new Address($mail->getTo()))
                ->subject($mail->getSubject())
                 ->text($mail->getText())
                ->html($mail->getHtml());

            $mailer->send($email);

            return new JsonResponse(['message' => 'Mail envoyé avec succès!'], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Erreur lors de l\'envoi du mail.'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route(path: '/{id}/content', name: 'app_mail_show_content')]
    public function content(Mail $mail, ?Profiler $profiler = null): Response
    {
        if (null !== $profiler) {
            $profiler->disable();
        }

        $parser = new MailParser($mail);
        return new Response($parser->getHtml());
    }

    #[Route('/{id}/delete', name: 'app_mail_delete')]
    public function delete(EntityManagerInterface $entityManager, Mail $mail, Request $request): JsonResponse
    {
        try {
            $entityManager->remove($mail);
            $entityManager->flush();

            return new JsonResponse(['message' => 'Email supprimé avec succès!'], JsonResponse::HTTP_OK);
        } catch (\Exception) {
            return new JsonResponse(['message' => 'Erreur lors de la suppression de l\'email.'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}/attachment/{number}', name: 'app_mail_attachment_download')]
    public function downloadAttachment(Mail $mail, int $number): BinaryFileResponse
    {
        $parser = new MailParser($mail);
        $attachment = $parser->getAttachments()[$number] ?? null;

        if (null === $attachment) {
            throw new NotFoundHttpException();
        }

        $tempFilePath = tempnam(sys_get_temp_dir(), 'attachment');
        file_put_contents($tempFilePath, $attachment->getContent());


        $response = $this->file($tempFilePath, $attachment->getFilename());
        $response->deleteFileAfterSend();

        return $response;
    }
}