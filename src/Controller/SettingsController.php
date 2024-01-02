<?php

namespace App\Controller;

use App\Business\ParameterBusiness;
use App\Form\Model\Parameter\UpdateParametersModel;
use App\Form\Type\Parameter\UpdateParametersType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SettingsController extends AbstractController
{
    #[Route(path: '/settings', name: 'app_settings_list')]
    public function list(): Response
    {
        return $this->render('Page/Settings/list.html.twig');
    }

    #[Route(path: '/settings/update', name: 'app_settings_update')]
    public function update(
        Request $request,
        ParameterBusiness $parameterBusiness
    ): Response
    {
        $model = new UpdateParametersModel();
        $form = $this
            ->createForm(UpdateParametersType::class, $model)
            ->handleRequest($request);

        if ($form->handleRequest($request) && $form->isSubmitted()) {
            foreach ($model->getParameters() as $parameter) {
                $parameterBusiness->setParameterValue($parameter->getName(), $parameter->getValue(), false);
            }
            $parameterBusiness->saveConfiguration();
        }

        return $this->redirectToRoute('app_settings_list');
    }
}