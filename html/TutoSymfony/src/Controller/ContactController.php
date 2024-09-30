<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $contact = new ContactDTO();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {

                $email = (new TemplatedEmail())
                ->from($contact->email)
                ->to('contact@demo.fr')
                ->subject('Demande de contact')
                ->htmlTemplate('contact/template.html.twig')
                ->context(['data' => $contact]);

                $mailer->send($email);

                $this->addFlash('success', 'L\'email a été envoyé');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Impossible d\'envoyer votre email');
            } finally {
                return $this->redirectToRoute('contact');
            }
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
