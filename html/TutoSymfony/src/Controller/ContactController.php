<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request): Response
    {
        $contact = new ContactDTO();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'L\'email a bien été envoyé');
            // Envoi de l'email
            dd($contact);
        }

        // TODO : A supprimer
        // Permet de pré-remplir le formulaire
        $contact->name = 'John Doe';
        $contact->email = 'john@Doe.fr';
        $contact->message = 'Super site !!!';
        $form = $this->createForm(ContactType::class, $contact);

        return $this->render('contact/index.html.twig', [
            'formContact' => $form,
        ]);
    }
}
