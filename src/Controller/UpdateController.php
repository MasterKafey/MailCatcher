<?php

namespace App\Controller;

use App\Business\DatabaseBusiness;
use App\Business\UpdateBusiness;
use App\Form\Model\Update\UpdateCodeModel;
use App\Form\Type\Update\UpdateCodeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/update')]
class UpdateController extends AbstractController
{
    #[Route('/database')]
    public function database(
        DatabaseBusiness $databaseBusiness,
        Request $request,
        UpdateBusiness $updateBusiness
    ): Response
    {
        if ($databaseBusiness->upToDate()) {
            return $this->redirectToRoute('app_authentication_login');
        }

        $mustCreateDatabase = !$databaseBusiness->doesDatabaseExist();
        if (!$mustCreateDatabase) {
            $mustMigrate = !empty($databaseBusiness->getMissingMigrations());
        } else {
            $mustMigrate = true;
        }

        $model = new UpdateCodeModel();
        $form = $this->createForm(UpdateCodeType::class, $model)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($model->getCode() !== $updateBusiness->getUpdateCode()) {
                $form->get('code')->addError(new FormError('Le code ne correspond pas à la variable d\'environement "CODE_UPDATE"'));
            } else {
                $databaseBusiness->update();
                return $this->redirectToRoute('app_authentication_login');
            }
        }

        return $this->render('Page/Update/database.html.twig', [
            'must_create_database' => $mustCreateDatabase,
            'must_migrate' => $mustMigrate,
            'database_name' => $databaseBusiness->getDatabaseName(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/missing-update-code', name: 'app_update_missing_update_code')]
    public function missingUpdateCode(DatabaseBusiness $databaseBusiness): Response
    {
        if ($databaseBusiness->upToDate()) {
            return $this->redirectToRoute('app_authentication_login');
        }

        return $this->render('Page/Update/missing-update-code.html.twig');
    }
}