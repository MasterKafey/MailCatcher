<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Template;
use App\Form\Type\Template\CreateTemplateType;
use App\Form\Type\Template\UpdateTemplateType;
use App\Security\Voter\ProjectVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/template')]
class TemplateController extends AbstractController
{
    #[Route(path: '/create/{id}', name: 'app_template_create')]
    public function create(Request $request, EntityManagerInterface $entityManager, Project $project): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::UPDATE, $project);

        $template = new Template();
        $template->setProject($project);

        $form = $this->createForm(CreateTemplateType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($template);
            $entityManager->flush();

            return $this->redirectToRoute('app_template_show', ['id' => $template->getId()]);
        }

        return $this->render('Page/Template/create.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }

    #[Route('/list', name: 'app_template_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $templates = $entityManager->getRepository(Template::class)->findBy(['user' => $user]);

        return $this->render('Page/Template/list.html.twig', [
            'templates' => $templates,
        ]);
    }

    #[Route(path: '/{id}', name: 'app_template_show')]
    public function show(Template $template): Response
    {
        return $this->render('Page/Template/show.html.twig', [
            'template' => $template,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_template_delete')]
    public function delete(Template $template, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($template);
        $entityManager->flush();

        return $this->redirectToRoute('app_template_list');
    }

    #[Route('/send/{id}', name: 'app_template_send', methods: [Request::METHOD_POST])]
    public function send(Request $request, Template $template, MailerInterface $mailer): Response
    {
        $parameters = $request->toArray();

        $to = $parameters['to'] ?? null;
        $from = $parameters['from'] ?? null;

        if ($to === null || $from === null) {
            return $this->json([
                'result' => false,
                'errors' => [
                    'You need to pass "to" and "from" email address',
                ],
            ]);
        }

        $body = $template->getBody();
        $subject = $template->getSubject();

        foreach ($parameters as $key => $value) {
            $body = str_replace('{{' . $key . '}}', $value, $body);
            $subject = str_replace('{{' . $key . '}}', $value, $subject);
        }

        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->html($body);

        $mailer->send($email);

        return $this->json([
            'result' => true,
        ]);
    }


    #[Route('/{id}/update', name: 'app_template_update')]
    public function update(Request $request, Template $template, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UpdateTemplateType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_template_show', ['id' => $template->getId()]);
        }

        return $this->render('Page/Template/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}