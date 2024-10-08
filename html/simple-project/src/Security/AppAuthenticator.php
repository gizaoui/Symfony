<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AppAuthenticator extends AbstractLoginFormAuthenticator {
    use TargetPathTrait;
    
    public const LOGIN_ROUTE = 'app_login';
    
    public function __construct(private UrlGeneratorInterface $urlGenerator, private UserRepository $userRepository)
    {
    }
    
    public function authenticate(Request $request): Passport
    {
        $username = $request->getPayload()
            ->getString('username');
        
        $request->getSession()
            ->set(SecurityRequestAttributes::LAST_USERNAME, $username);
        
        // dd($this->userRepository->findByEmailOrUsername($username));
        
        return new Passport(new UserBadge($username, function (string $identifier)
        {
            // Exploitation d'un 'callable' retournant le résultat d'une requête SQL (User)
            return $this->userRepository->findByEmailOrUsername($identifier);
        }), new PasswordCredentials($request->getPayload()
            ->getString('password')), [new CsrfTokenBadge('authenticate', $request->getPayload()
            ->getString('_csrf_token')), new RememberMeBadge()]);
    }
    
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName))
        {
            return new RedirectResponse($targetPath);
        }
        
        // Redirige de la page home après un 'registration'
        return new RedirectResponse($this->urlGenerator->generate('home'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
        
        # Methode 1 :  Redirige les personnes connectées vers la page d'acceuil
        // return new RedirectResponse('/');
        
        # Methode 2 : Redirige les personnes connectées vers la page d'administration
        # return new RedirectResponse($this->urlGenerator->generate('admin.recipe.index'));
    }
    
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
