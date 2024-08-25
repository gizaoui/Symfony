<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController {
    
    // http://localhost:8000/?name=John
    public function index(Request $resquest): Response {
        // dd($resquest);
        // return new Response ( 'Bonjour ' . $resquest->query->get ( 'name', 'Inconnu' ) );
        
        return $this->render ( 'home/index.html.twig', [ 'controller_name' => 'HomeController' ] );
    }
}

