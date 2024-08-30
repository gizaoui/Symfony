<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/admin/category', name: 'admin.category.')]
class CategoryController extends AbstractController {
    
    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function index(Request $resquest, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        return $this->render('admin/category/index.html.twig', ['categories' => $categories]);
    }
    
    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $resquest, EntityManagerInterface $em)
    {
        $category = new Category();
        /**
         * Màj des champs déportées dans 'src/Form/RecipeType.php'
         *   $recipe->setCreatedAt(new \DateTimeImmutable())
         *   $recipe->setUpdateAt(new \DateTimeImmutable());
         * */
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($resquest);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($category);
            $em->flush($category);
            $this->addFlash('success', 'La catégorie a été créée');
            return $this->redirectToRoute('admin.category.index');
        }
        return $this->render('admin/category/create.html.twig', ['form' => $form]);
    }
    
    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
    public function edit(Category $category, Request $resquest, EntityManagerInterface $em)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($resquest);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            $this->addFlash('success', 'La catégorie a été mise à jour');
            return $this->redirectToRoute('admin.category.index');
        }
        return $this->render('admin/category/edit.html.twig', ['category' => $category, 'form' => $form]);
    }
    
    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function remove(Category $category, EntityManagerInterface $em)
    {
        $em->remove($category);
        $em->flush();
        $this->addFlash('success', 'La catégorie a été supprimée');
        return $this->redirectToRoute('admin.category.index');
    }
}


