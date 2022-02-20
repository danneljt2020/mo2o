<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    /**
     * @Route("/api/error", name="error")
     * @return Response
     */
    public function errorHandler(): Response
    {
        return new JsonResponse(['status' => Response::HTTP_NOT_FOUND, 'error' => "Not found",
            'message' => "The requested route does not exist"]);
    }
}
