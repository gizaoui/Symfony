<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class HomeController extends AbstractController
{
    // http://localhost:8000/?name=John
    public function index(Request $resquest): Response
    {
        // return new Response ( 'Bonjour ' . $resquest->query->get ( 'name', 'Inconnu' ) );
        return $this->render('home/index.html.twig', ['controller_name' => 'HomeController']);
    }
    
    
    # Création d'un User par défaut
    public function __construct(private EntityManagerInterface $em, private UserRepository $userRepository, private UserPasswordHasherInterface $hasher, private UserAuthenticatorInterface $UserAuthenticatorInterface)
    {
        // Suppression des utilisateurs
        $users = $userRepository->findBy(['username' => 'JohnDoe']);
        foreach ( $users as $user )
        {
            $em->remove($user);
        }
        $em->flush();
        
        $user = new User();
        // Recherche de l'interface : php bin/console debug:autowiring Password
        $user->setEmail('John@Doe.fr')
        ->setUsername('JohnDoe')
        ->setPassword($hasher->hashPassword($user, '0000'))
        ->setRoles(['ROLE_ADMIN']);
        $em->persist($user);
        $em->flush();
    }
}
