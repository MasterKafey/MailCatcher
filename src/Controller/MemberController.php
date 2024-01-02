<?php

namespace App\Controller;

use App\Entity\Member;
use App\Entity\MemberRole;
use App\Entity\MemberStatus;
use App\Entity\Project;
use App\Form\Type\ConfirmType;
use App\Form\Type\Member\CreateMemberType;
use App\Form\Type\Member\UpdateMemberType;
use App\Security\Voter\MemberVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/member')]
class MemberController extends AbstractController
{
    #[Route(path: '/{id}/create', name: 'app_member_create')]
    public function create(
        Project                $project,
        Request                $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $member = new Member();
        $form = $this
            ->createForm(CreateMemberType::class, $member)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($member);
            $entityManager->flush();

            return $this->redirectToRoute('app_member_list_project_members', ['id' => $project->getId()]);
        }

        return $this->render('Page/Member/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}/update', name: 'app_member_update')]
    public function update(
        Member                 $member,
        EntityManagerInterface $entityManager,
        Request                $request,
    ): Response
    {
        $this->denyAccessUnlessGranted(MemberVoter::UPDATE, $member);
        $currentMember = $member->getProject()->getMemberByUser($this->getUser());

        $form = $this
            ->createForm(UpdateMemberType::class, $member, ['current_role' => $currentMember->getRole()])
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_project_show', ['id' => $member->getProject()->getId()]);
        }

        return $this->render('Page/Member/update.html.twig', [
            'form' => $form->createView(),
            'member' => $member,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_member_delete')]
    public function delete(
        Member                 $member,
        EntityManagerInterface $entityManager,
        Request                $request
    ): Response
    {
        $this->denyAccessUnlessGranted(MemberVoter::DELETE, $member);
        $form = $this->createForm(ConfirmType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($member);
            $entityManager->flush();
            return $this->redirectToRoute('app_project_show', ['id' => $member->getId()]);
        }

        return $this->render('Page/Member/delete.html.twig');
    }
}