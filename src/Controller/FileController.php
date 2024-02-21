<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FileController extends AbstractController
{
    #[Route(path: '/upload', name: "app_file_upload", methods: Request::METHOD_POST)]
    public function upload(Request $request): Response
    {
        $uploadedFile = $request->files->get('file');

        $destination = $this->getParameter('upload_directory');
        $name = uniqid() . '.' . $uploadedFile->guessExtension();
        $uploadedFile->move($destination, $name);

        return $this->json([
            'success' => true,
            'path' => $this->generateUrl('app_file_download', ['name' => $name], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    }

    #[Route(path: '/download/{name}', name: 'app_file_download')]
    public function downloadFile(string $name): Response
    {
        return $this->file($this->getParameter('upload_directory') . '/' .$name, $name);
    }
}