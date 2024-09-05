<?php

namespace App\Controller\Admin;


use Symfony\Component\Routing\Requirement\Requirement;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
# use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/admin/recipe', name: 'admin.recipe.')]
#[IsGranted('ROLE_ADMIN')]
class
// Pour toutes les méthodes
RecipeController extends AbstractController
{

    // http://localhost:8000/recette
    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function index(RecipeRepository $recipeRepository, Request $resquest)
    {
        # $this->denyAccessUnlessGranted('ROLE_USER'); // Remplacé par #[IsGranted('ROLE_USER')]
        // $recipes = $recipeRepository->findWithDurationLowerThan(20);
        $page = $resquest->query->getInt('page', 1);
        $limit = 2;
        $recipes = $recipeRepository->paginateRecipes($page, $limit);
        $maxPage = ceil($recipes->count() / $limit);

        // dd($recipes->count());
        return $this->render('admin/recipe/index.html.twig', [
            'recipes' => $recipes,
            'maxPage' => $maxPage,
            'page' => $page
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $resquest, EntityManagerInterface $em)
    {
        $recipe = new Recipe();
        /**
         * Màj des champs déportées dans 'src/Form/RecipeType.php'
         *   $recipe->setCreatedAt(new \DateTimeImmutable())
         *   $recipe->setUpdateAt(new \DateTimeImmutable());
         * */
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($resquest);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($recipe);
            $em->flush($recipe);
            $this->addFlash('success', 'La recette a été créée');
            return $this->redirectToRoute('admin.recipe.index');
        }
        return $this->render('admin/recipe/create.html.twig', ['recipe' => $recipe, 'form' => $form]);
    }

    #[Route('/show/{id}/{slug}', name: 'show', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
    public function show(Request $resquest, int $id, string $slug, RecipeRepository $recipeRepository)
    {
        // dd($id, $slug);
        $recipe = $recipeRepository->find($id);
        return $this->render('admin/recipe/show.html.twig', ['recipe' => $recipe]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
    public function edit(Recipe $recipe, Request $resquest, EntityManagerInterface $em)
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($resquest);

        if ($form->isSubmitted() && $form->isValid()) {
            // /** @var UploadedFile $file */
            // $file = $form->get('thumbnailFile')
            //     ->getData();

            // if ($file != null)
            // {
            //     $filename = $recipe->getId().'_'.$file->getClientOriginalName();

            //     if (file_exists($filename))
            //     {
            //         if (! unlink($filename))
            //         {
            //             $this->addFlash('danger', 'Error deleting the file');
            //         }
            //     }
            //     else
            //     {
            //         // dd($filename);
            //         # php bin/console debug:container --parameters | grep dir
            //         $file->move($this->getParameter('kernel.project_dir').'/public/images/recettes', $filename);
            //         $recipe->setThumbnail($filename);
            //     }
            // }

            $em->flush();
            $this->addFlash('success', 'La recette a été mise à jour');
            return $this->redirectToRoute('admin.recipe.index');
        }
        return $this->render('admin/recipe/edit.html.twig', ['recipe' => $recipe, 'form' => $form]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function remove(Recipe $recipe, EntityManagerInterface $em)
    {
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success', 'La recette a été supprimée');
        return $this->redirectToRoute('admin.recipe.index');
    }

    public function __construct(private RecipeRepository $recipeRepository, private CategoryRepository $categoryRepository, private EntityManagerInterface $em)
    {
        #####  Test de la propriété cascade: ['persist']  #####

        # Suppression de catégories et des recette
        //         $recipes = $recipeRepository->findBy(['slug' => 'pate-bolognaise']);
        //         foreach ( $recipes as $recipe ) {$em->remove($recipe);}
        //         $categories = $categoryRepository->findBy(['slug' => 'plat-principal']);
        //         foreach ( $categories as $category ) {$em->remove($category);}
        //         $em->flush();

        # Ajout d'une recette
        //         # Recette 'Pâte bolognaise'
        //         $recipe = new Recipe();
        //         $recipe->setTitle('Pâte bolognaise')
        //             ->setSlug('pate-bolognaise')
        //             ->setContent('Sauce tomate')
        //             ->setDuration(12)
        //             ->setCreatedAt(new \DateTimeImmutable())
        //             ->setUpdateAt(new \DateTimeImmutable());
        //         $em->persist($recipe);
        //         $em->flush($recipe);

        # Ajout d'une catégorie (sans persistance)
        //         # Catégorie 'Plat principal'
        //         $category = new Category();
        //         $category->setName('Plat principal')
        //             ->setSlug('plat-principal')
        //             ->setCreatedAt(new \DateTimeImmutable())
        //             ->setUpdateAt(new \DateTimeImmutable());
        //         # $em->persist($category); // /!\ Pris en charge par la propriété cascade: ['persist']
        //         $em->flush($category);

        # Association de la catégorie 'Plat principal' à la recette 'Pâte bolognaise'
        //         $recipe->setCategory($category);
        //         $em->flush($recipe);
    }
}
