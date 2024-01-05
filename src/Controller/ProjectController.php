<?php

namespace App\Controller;

use App\Entity\Member;
use App\Entity\MemberRole;
use App\Entity\MemberStatus;
use App\Entity\Project;
use App\Form\Type\ConfirmType;
use App\Form\Type\Member\CreateMemberType;
use App\Form\Type\Project\CreateProjectType;
use App\Form\Type\Project\UpdateProjectType;
use App\Form\Type\Project\LeaveProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/project')]
class ProjectController extends AbstractController
{
    #[Route(path: '/create', name: 'app_project_create')]
    public function createProject(
        Request                $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $project = new Project();
        $form = $this
            ->createForm(CreateProjectType::class, $project)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_show', [
                'id' => $project->getId(),
            ]);
        }

        return $this->render('Page/Project/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/leave/{id}', name: 'app_project_leave')]
    public function leaveProject(Project $project, EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();

        $member = $entityManager
            ->getRepository(Member::class)
            ->findOneBy(['user' => $user, 'project' => $project]);

        if (!$member) {
            return $this->redirectToRoute('app_project_list');
        }

        $form = $this
            ->createForm(ConfirmType::class, $project)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($member);
            $entityManager->flush();
            return $this->redirectToRoute('app_project_list');
        }

        return $this->render('Page/Project/confirm_form_leave.html.twig', [
            'form' => $form
        ]);
    }


    #[Route(path: '/list', name: 'app_project_list')]
    public function list(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        $projects = $entityManager->getRepository(Project::class)->findByUser($user);
        return $this->render('Page/Project/list.html.twig', [
            'projects' => $projects,
        ]);
    }


    #[Route(path: '/{id}', name: 'app_project_show')]
    public function show(Project $project): Response
    {
        return $this->render('Page/Project/show.html.twig', [
            'project' => $project,
            'members' => $project->getAcceptedMembers(),
            'invitations' => $project->getPendingMembers(),
        ]);
    }

    #[Route(path: '/{id}/update', name: 'app_project_update')]
    public function update(
        Request                $request,
        Project                $project,
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this
            ->createForm(UpdateProjectType::class, $project)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_project_show', [
                'id' => $project->getId(),
            ]);
        }
        return $this->render('Page/Project/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete')]
    public function delete(
        Request                $request,
        Project                $project,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $form = $this
            ->createForm(ConfirmType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_list');
        }

        return $this->render('Page/Project/delete.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/invite')]
    public function invite(
        Project $project,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        $member = new Member();
        $member->setProject($project);

        $form = $this
            ->createForm(CreateMemberType::class, $member)
            ->handleRequest($request)
        ;

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($member);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_show', ['id' => $project->getId()]);
        }

        return $this->render('Page/Project/invite.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}