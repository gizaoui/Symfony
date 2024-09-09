<?php

namespace App\Controller\API;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class RecipesController extends AbstractController
{

   #[Route('/api/recipes')]
   public function index(RecipeRepository $recipeRepository, Request $request)
   {
      $recipes = $recipeRepository->paginateRecipes($request->query->getInt('page', 1), 2);
      return $this->json($recipes, 200, [], [
         # Liste des champs que l'on souhaite convertir
         'groups' => ['recipes.index']
      ]);
   }

   #[Route('/api/recipes/{id}', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
   public function show(Recipe $recipe)
   {
      return $this->json($recipe, 200, [], [
         'groups' => ['recipes.index', 'recipes.show']
      ]);
   }
}
