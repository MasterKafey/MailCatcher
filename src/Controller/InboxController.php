<?php

namespace App\Controller;

use App\Entity\Inbox;
use App\Entity\Mail;
use App\Entity\Project;
use App\Form\Type\ConfirmType;
use App\Form\Type\Inbox\CreateInboxType;
use App\Form\Type\Inbox\UpdateInboxType;
use App\Security\Voter\InboxVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/inbox')]
class InboxController extends AbstractController
{
    #[Route(path: '/create', name: 'app_inbox_create')]
    public function create(
        Request                $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $projects = $entityManager->getRepository(Project::class)->findByUser($this->getUser());

        if (empty($projects)) {
            return $this->render('Page/Project/no_project.html.twig');
        }

        $inbox = new Inbox();
        $form = $this
            ->createForm(CreateInboxType::class, $inbox, ['projects' => $projects])
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($inbox);
            $entityManager->flush();

            return $this->redirectToRoute('app_inbox_show', [
                'id' => $inbox->getId(),
            ]);
        }

        return $this->render('Page/Inbox/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/', name: 'app_inbox_list')]
    public function list(
        EntityManagerInterface $entityManager
    ): Response
    {
        $inboxes = $entityManager->getRepository(Inbox::class)->findByUser($this->getUser());
        return $this->render('Page/Inbox/list.html.twig', [
            'inboxes' => $inboxes,
        ]);
    }

    #[Route(path: '/{id}', name: 'app_inbox_show')]
    #[IsGranted(InboxVoter::VIEW, 'inbox')]
    public function show(Inbox $inbox): Response
    {
        $mails = $inbox->getMails()->toArray();
        usort($mails, function (Mail $a, Mail $b) {
            return $b->getSentAt() <=> $a->getSentAt();
        });

        return $this->render('Page/Inbox/show.html.twig', [
            'inbox' => $inbox,
            'mails' => $mails,
        ]);
    }

    #[Route(path: '/{id}/update', name: 'app_inbox_update')]
    #[IsGranted(InboxVoter::EDIT, 'inbox')]
    public function update(Inbox $inbox, Request $request, EntityManagerInterface $entityManager): Response
    {
        $projects = $entityManager->getRepository(Project::class)->findByUser($this->getUser());

        $form = $this
            ->createForm(UpdateInboxType::class, $inbox, ['projects' => $projects])
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($inbox);
            $entityManager->flush();

            return $this->redirectToRoute('app_inbox_list');
        }

        return $this->render('Page/Inbox/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}/delete', name: 'app_inbox_delete')]
    #[IsGranted(InboxVoter::EDIT, 'inbox')]
    public function delete(
        Inbox                  $inbox,
        EntityManagerInterface $entityManager,
        Request                $request
    ): Response
    {
        $form = $this
            ->createForm(ConfirmType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($inbox);
            $entityManager->flush();

            return $this->redirectToRoute('app_inbox_list');
        }

        return $this->render('Page/Inbox/delete.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}