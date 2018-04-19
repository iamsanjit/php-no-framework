<?php declare(strict_types=1);

namespace App\User\Presentation;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use App\Framework\Rendering\TemplateRenderer;
use App\User\Presentation\RegisterUserForm;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Submissions\Application\SubmitLinkHandler;
use App\User\Application\RegisterUserHandler;


final class RegistrationController
{

    private $templateRenderer;
    private $session;
    private $registerUserHandler;
    private $registerFormFactory;

    public function __construct(
        TemplateRenderer $templateRenderer,
        SessionInterface $session,
        RegisterUserHandler $registerUserHandler,
        RegisterFormFactory $registerFormFactory
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->session = $session;
        $this->registerUserHandler = $registerUserHandler;
        $this->registerFormFactory = $registerFormFactory;
    }

    public function create(Request $request) : Response
    {
        $content = $this->templateRenderer->render('RegisterUser.html.twig');
        return new Response($content);
    }

    public function store(Request $request) : Response
    {
        $response = new RedirectResponse('/register');
        
        $form = $this->registerFormFactory->createFromRequest($request);

        if ($form->hasValidationErrors()) {
            foreach ($form->getValidationErrors() as $errorMessage) {
                $this->session->getFlashBag()->add('errors', $errorMessage);
            }
            return $response;
        }

        $this->registerUserHandler->handle($form->toCommand());

        $this->session->getFlashBag()->add('success', 'User created.');

        return $response;
    }

}