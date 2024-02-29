<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class RoleController extends AbstractController
{
    #[Route('/admin/promote', name: 'app_admin_role')]
    public function changerRole(Request $request, EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('Page/Roles/adminRole.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/promote/send', name: 'app_admin_promote_role')]
    public function promoteUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userId = $request->request->get('userId');

        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            $this->addFlash('error', 'Aucun utilisateur trouvé.');
            return $this->redirectToRoute('app_admin_role');
        }

        $user->setRoles(['ROLE_ADMIN']);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur promu en Administrateur réussi');

        return $this->redirectToRoute('app_home_index');
    }
}
