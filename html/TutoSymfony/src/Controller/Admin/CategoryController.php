<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/category/', name: 'admin.category.')]
class CategoryController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index()
    {
    }

    #[Route('edit/{id}', name: 'edit')]
    public function edit()
    {
    }

    #[Route('create', name: 'create')]
    public function create()
    {
    }

    #[Route('delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete()
    {
    }
}
