<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController {
    
    // http://localhost:8000/recette
    // /!\ La path ne doit pas contenir 'recipe'
    #[Route('/recette', name: 'recipe.index', methods: ['GET', 'POST'])]
    public function index(Request $resquest, RecipeRepository $recipeRepository, EntityManagerInterface $em): Response {
        $recipes = $recipeRepository->findWithDurationLowerThan(20);
        return $this->render('recipe/index.html.twig', ['recipes' => $recipes]);
    }
    
    // http://localhost:8000/recette/pate-bolognaise-32
    // /!\ La path ne doit pas contenir 'recipe'
    #[Route('/recette/show/{id}/{slug}', name: 'recipe.show', methods: ['GET', 'POST'])]
    public function show(Request $resquest, int $id, string $slug, RecipeRepository $recipeRepository): Response {
        // dd($id, $slug);
        $recipe = $recipeRepository->find($id);
        return $this->render('recipe/show.html.twig', ['recipe' => $recipe]);
    }
    
    #[Route('/recette/edit/{id}', name: 'recipe.edit', methods: ['GET', 'POST'])]
    public function edit(Recipe $recipe, Request $resquest, EntityManagerInterface $em): Response {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($resquest);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'La recette a été mise à jour');
            return $this->redirectToRoute('recipe.index');
        }
        return $this->render('recipe/edit.html.twig', ['recipe' => $recipe, 'form' => $form]);
    }
    
    #[Route('/recette/create', name: 'recipe.create', methods: ['GET', 'POST'])]
    public function create(Request $resquest, EntityManagerInterface $em): Response {
        $recipe = new Recipe();
//         $recipe->setCreatedAt(new \DateTimeImmutable())
//             ->setUpdateAt(new \DateTimeImmutable());
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($resquest);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($recipe);
            $em->flush($recipe);
            $this->addFlash('success', 'La recette a été créée');
            return $this->redirectToRoute('recipe.index');
        }
        return $this->render('recipe/create.html.twig', ['recipe' => $recipe, 'form' => $form]);
    }
    
    #[Route('/recette/delete/{id}', name: 'recipe.delete', methods: ['DELETE'])]
    public function remove(Recipe $recipe, EntityManagerInterface $em): Response {
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success', 'La recette a été supprimée');
        return $this->redirectToRoute('recipe.index');
    }
}
