<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{

    #[Route('/contact', name: 'contact')]
    public function index(Request $resquest, MailerInterface $mailer): Response
    {
        $data = new ContactDTO();
        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($resquest);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $email = (new TemplatedEmail())->from($data->email)
                    ->to($data->service)
                    ->subject('Demande de contact')
                    ->htmlTemplate('contact/email.html.twig')
                    ->context(['data' => $data]);

                $mailer->send($email);

                $this->addFlash('success', 'L\'email a été envoyé');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Impossible d\'envoyer votre email');
            } finally {
                return $this->redirectToRoute('contact');
            }
        }

        // TODO : A supprimer
        $data->name = 'John Doe';
        $data->email = 'john@Doe.fr';
        $data->message = 'Super site !!!';
        $form = $this->createForm(ContactType::class, $data);

        return $this->render('contact/index.html.twig', ['form' => $form]);
    }
}
