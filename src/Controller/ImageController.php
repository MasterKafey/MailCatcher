<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    #[Route(path: '/upload/image', name:"upload_image", methods:"POST")]
    public function uploadImage(Request $request): Response
    {
        $uploadedFile = $request->files->get('image');

        $destination = $this->getParameter('upload_directory');
        $newFilename = uniqid().'.'.$uploadedFile->guessExtension();
        $uploadedFile->move($destination, $newFilename);

        $imagePath = $request->getBaseUrl().'/images/upload/'.$newFilename;
        return $this->json(['success' => true, 'imagePath' => $imagePath]);
    }

    #[Route(path: '/upload/video', name:"upload_video", methods:"POST")]
    public function uploadVideo(Request $request): Response
    {
        $uploadedFile = $request->files->get('video');

        $destination = $this->getParameter('upload_directory_video');
        $newFilename = uniqid().'.'.$uploadedFile->guessExtension();
        $uploadedFile->move($destination, $newFilename);

        $videoPath = 'http://127.0.0.1:8000'.'/videos/upload/'.$newFilename;
        return $this->json(['success' => true, 'videoPath' => $videoPath]);
    }
}