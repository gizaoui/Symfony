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
        $recipes = $recipeRepository->findWithDurationLowerThan(30);
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/recipe/{slug}', name: 'recipe.show')]
    public function show(RecipeRepository $recipeRepository, string $slug): Response
    {
        return $this->render('recipe/show.html.twig', [
            // Récupération par le 'slug'
            'recipe' => $recipeRepository->findOneBy(['slug' => $slug])
        ]);
    }
}
