<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ErrorController extends AbstractController
{
    #[Route(path: '/error/{code}')]
    public function testError(int $code = 500): Response
    {
        $exception = FlattenException::create(new \Exception('Test Error'), $code);
        $request = Request::createFromGlobals();
        return $this->forward('twig.controller.exception:show', [
            'request' => $request,
            'exception' => $exception,
        ]);
    }
}