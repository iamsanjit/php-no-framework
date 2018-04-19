<?php declare (strict_types = 1);

namespace App\User\Presentation;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use App\Framework\Rendering\TemplateRenderer;
use App\Framework\Csrf\StoredTokenValidator;
use App\User\Application\LogInHandler;
use App\User\Application\LogIn;
use App\Framework\Csrf\Token;


final class LoginController
{
    private $templateRenderer;
    private $storedTokenValidator;
    private $logInHandler;
    private $session;

    public function __construct(
        TemplateRenderer $templateRenderer,
        StoredTokenValidator $storedTokenValidator,
        LogInHandler $logInHandler,
        SessionInterface $session
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->storedTokenValidator = $storedTokenValidator;
        $this->logInHandler = $logInHandler;
        $this->session = $session;
    }

    public function create(Request $request) : Response
    {
        $content = $this->templateRenderer->render('login.html.twig');
        return new Response($content);
    }

    public function store(Request $request) : Response
    {   
        $this->session->remove('userId');
    
        if (!$this->storedTokenValidator->validate('login', new Token($request->get('csrf_token')))) {
            $this->session->getFlashBag()->add('errors', 'Invalid token.');
            return new RedirectResponse('/login');
        }

       $this->logInHandler->handle(new LogIn(
           $request->get('username'),
           $request->get('password')
       ));

       if ($this->session->get(userId) === null) {
           $this->session->getFlashBag()->add('errors', 'Invalid username or password');
           return new RedirectResponse('/login');
       }

       $this->session->getFlashBag()->add('success', 'You were logged in');
       return new RedirectResponse('/');
    }

}