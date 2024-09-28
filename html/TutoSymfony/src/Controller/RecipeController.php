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
        dd($recipes);

        return $this->render('recipe/index.html.twig', [
            'controller_name' => 'Recipe Controller',
        ]);
    }

    #[Route('/recipe/{slug}/{id}', name: 'recipe.show')]
    public function show(Request $request, string $slug, int $id): Response
    {
        return $this->render('recipe/show.html.twig', [
            'slug' => $slug,
            'id' => $id,
            'person' => [
                'firstname' => 'John',
                'lastname' => 'DOE'
            ]
        ]);
    }
}
