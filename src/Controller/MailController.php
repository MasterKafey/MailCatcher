<?php

namespace App\Controller;

use App\Entity\Mail;
use App\Utils\MailParser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
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
        $mail = new MailParser($mail);

        $email = (new Email())
            ->from(new Address($mail->getFrom()))
            ->to(new Address($mail->getTo()))
            ->subject($mail->getSubject())
            ->text($mail->getText())
            ->html($mail->getHtml())
        ;

        try {

            $mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            return $this->json([
                'result' => false,
                'errors' => [
                    $exception->getMessage()
                ],
            ]);
        }
        return $this->json(['result' => true]);
    }

    #[Route(path: '/{id}/content', name: 'app_mail_show_content')]
    public function content(Mail $mail, ?Profiler $profiler = null): Response
    {
        $profiler?->disable();

        $parser = new MailParser($mail);
        return new Response($parser->getHtml());
    }

    #[Route('/{id}/delete', name: 'app_mail_delete')]
    public function delete(EntityManagerInterface $entityManager, Mail $mail): JsonResponse
    {
        $entityManager->remove($mail);
        $entityManager->flush();

        return $this->json(['result' => true]);
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