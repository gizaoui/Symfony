<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/recipe', name: 'admin.recipe.')]
class RecipeController extends AbstractController {
    
    // http://localhost:8000/recette
    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function index(Request $resquest, RecipeRepository $recipeRepository, EntityManagerInterface $em): Response
    {
        $recipes = $recipeRepository->findWithDurationLowerThan(20);
        return $this->render('admin/recipe/index.html.twig', ['recipes' => $recipes]);
    }
    
    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $resquest, EntityManagerInterface $em): Response
    {
        $recipe = new Recipe();
        /**
         * Màj des champs déportées dans 'src/Form/RecipeType.php'
         *   $recipe->setCreatedAt(new \DateTimeImmutable())
         *   $recipe->setUpdateAt(new \DateTimeImmutable());
         * */
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($resquest);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($recipe);
            $em->flush($recipe);
            $this->addFlash('success', 'La recette a été créée');
            return $this->redirectToRoute('admin.recipe.index');
        }
        return $this->render('admin/recipe/create.html.twig', ['recipe' => $recipe, 'form' => $form]);
    }
    
    #[Route('/show/{id}/{slug}', name: 'show', methods: ['GET', 'POST'])]
    public function show(Request $resquest, int $id, string $slug, RecipeRepository $recipeRepository): Response
    {
        // dd($id, $slug);
        $recipe = $recipeRepository->find($id);
        return $this->render('admin/recipe/show.html.twig', ['recipe' => $recipe]);
    }
    
    
    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Recipe $recipe, Request $resquest, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($resquest);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            $this->addFlash('success', 'La recette a été mise à jour');
            return $this->redirectToRoute('admin.recipe.index');
        }
        return $this->render('admin/recipe/edit.html.twig', ['recipe' => $recipe, 'form' => $form]);
    }
    
    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function remove(Recipe $recipe, EntityManagerInterface $em): Response
    {
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success', 'La recette a été supprimée');
        return $this->redirectToRoute('admin.recipe.index');
    }
}
