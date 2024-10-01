<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/category/', name: 'admin.category.')]
class CategoryController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findAll();
        return $this->render('admin/category/index.html.twig', [
            'categories' => $category,
        ]);
    }

    #[Route('edit/{id}', name: 'edit')]
    public function edit(Category $category, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'La recette a bien été modifiée');
            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('admin/category/edit.html.twig', [
            'categoryData' => $category,
            'categoryForm' => $form
        ]);
    }

    #[Route('create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
         $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'La catégorie a bien été créée');
            return $this->redirectToRoute('admin.category.index');
        }


        return $this->render('admin/category/create.html.twig', [
            'categoryForm' => $form
        ]);
    }

    #[Route('delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Category $category, EntityManagerInterface $em): Response
    {
        $em->remove($category);
        $em->flush();
        $this->addFlash('success', 'La catégorie a été supprimée');
        return $this->redirectToRoute('admin.category.index');
    }
}
