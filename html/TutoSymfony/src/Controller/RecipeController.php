<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{
    #[Route('/recipe', name: 'recipe.index')]
    public function index(RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findAll();
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/recipe/{id}', name: 'recipe.show')]
    public function show(RecipeRepository $recipeRepository, int $id): Response
    {
        return $this->render('recipe/show.html.twig', [
            // Récupération par la 'Primary key'
            'recipe' => $recipeRepository->find($id)
        ]);
    }
}
