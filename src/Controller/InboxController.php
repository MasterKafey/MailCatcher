<?php

namespace App\Controller;

use App\Entity\Inbox;
use App\Entity\Mail;
use App\Entity\Project;
use App\Entity\User;
use App\Form\Type\ConfirmType;
use App\Form\Type\Inbox\CreateInboxType;
use App\Form\Type\Inbox\UpdateInboxType;
use App\Security\Voter\InboxVoter;
use App\Security\Voter\ProjectVoter;
use App\Utils\MailParser;
use Doctrine\ORM\EntityManagerInterface;
use PhpMimeMailParser\Parser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/inbox')]
class InboxController extends AbstractController
{
    #[Route(path: '/create/{id}', name: 'app_inbox_create', defaults: ['id' => null])]
    public function create(
        Request                $request,
        EntityManagerInterface $entityManager,
        Project $project = null
    ): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new AccessDeniedException();
        }

        $parameters = [];
        if ($project === null) {
            $projects = $entityManager->getRepository(Project::class)->findByUser($user);

            if (empty($projects)) {
                return $this->render('Page/Project/no_project.html.twig');
            }
            $parameters['projects'] = $project;
        } else {
            $this->denyAccessUnlessGranted(ProjectVoter::UPDATE, $project);
        }

        $inbox = new Inbox();
        $form = $this
            ->createForm(CreateInboxType::class, $inbox, $parameters)
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
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new AccessDeniedException();
        }

        $inboxes = $entityManager->getRepository(Inbox::class)->findByUser($user);
        return $this->render('Page/Inbox/list.html.twig', [
            'inboxes' => $inboxes,
        ]);
    }

    #[Route(path: '/{id}', name: 'app_inbox_show')]
    #[IsGranted(InboxVoter::VIEW, 'inbox')]
    public function show(Inbox $inbox): Response
    {

        $parsers = array_map(function(Mail $mail) {
            return new MailParser($mail);
        }, $inbox->getMails()->toArray());

        usort($parsers, function (MailParser $a, MailParser $b) {
            return $a->getDate() > $b->getDate();
        });

        return $this->render('Page/Inbox/show.html.twig', [
            'inbox' => $inbox,
            'parsers' => $parsers,
        ]);
    }

    #[Route(path: '/{id}/update', name: 'app_inbox_update')]
    #[IsGranted(InboxVoter::EDIT, 'inbox')]
    public function update(Inbox $inbox, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new AccessDeniedException();
        }

        $projects = $entityManager->getRepository(Project::class)->findByUser($user);

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