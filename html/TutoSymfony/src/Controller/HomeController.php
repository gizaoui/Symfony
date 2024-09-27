<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController {
    public function index(Request $request): Response {
        // http://localhost:8000/?name=World
        return new Response('Hello '.$request->query->get('name', 'Inconnu'));
    }
}