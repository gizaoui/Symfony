<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Recipe;
use App\Form\RecipeType;
use Doctrine\ORM\EntityManagerInterface;

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

    #[Route('/recipe/show/{id}', name: 'recipe.show')]
    // Récupération par la 'Primary key' dans l'instance '$recipe'
    public function show(Recipe $recipe): Response
    {
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe
        ]);
    }

    #[Route('/recipe/edit/{id}', name: 'recipe.edit')]
    // Récupération par la 'Primary key' dans l'instance '$recipe'
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em): Response
    {
        // Création de l'instance du formulaire initialisée 
        // avec l'injection des données dans l'instance '$recipe'.
        $form = $this->createForm(RecipeType::class, $recipe);

        // Récupère les données mise à jour de la page web.
        // pour mettre à jour celle de l'instance '$recipe'
        // L'absence de données dans la $request au premier appel
        // n'écrase pas celles présentes dans l'instance '$recipe'.
        $form->handleRequest($request);

        // Information obtenues par le 'handleRequest'
        // précédement appelé.
        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'La recette a bien été modifiée');
            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('recipe/edit.html.twig', [
            'recipeData' => $recipe,
            'recipeForm' => $form
        ]);
    }
}
