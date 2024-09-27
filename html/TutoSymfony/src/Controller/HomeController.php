<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    /**
     * Déclaré dans le fichier 'TutoSymfony/config/routes.yaml'
     *
     * home:
     *    path: /
     *    controller: App\Controller\HomeController::index
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Home Controller',
        ]);
    }
}
