<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController
{
    #[Route('/recipe/{slug}/{id}', name: 'recipe.show')]
    public function index(Request $request): Response
    {
        dd($request);
    }
}
