<?php declare (strict_types = 1);

namespace App\Submissions\Presentation;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Framework\Rendering\TemplateRenderer;
use App\FrontPage\Application\SubmissionsQuery;
use App\Framework\Csrf\StoredTokenValidator;
use App\Framework\Csrf\Token;
use App\Framework\Rbac\User;
use App\Framework\Rbac\Permission;
use App\Submissions\Application\SubmitLinkHandler;
use App\Submissions\Application\SubmitLink;
use App\Framework\Rbac\AuthenticatedUser;

final class SubmissionsController
{
    protected $templateRenderer;
    protected $submitFormFactory;
    protected $session;
    protected $submitLinkHandler;
    protected $user;

    public function __construct(
        TemplateRenderer $templateRenderer,
        SubmitFormFactory $submitFormFactory,
        SessionInterface $session,
        SubmitLinkHandler $submitLinkHandler,
        User $user
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->submitFormFactory = $submitFormFactory;
        $this->session = $session;
        $this->submitLinkHandler = $submitLinkHandler;
        $this->user = $user;
    }

    public function create(Request $request) : Response
    {
        if (!$this->user->hasPermission(new Permission\SubmitLink())) {
            $this->session->getFlashBag()->add('errors', 'You have to login before you submit a link.');
            return new RedirectResponse('/login');
        }

        $content = $this->templateRenderer->render('Submission.html.twig');
        return new Response($content);
    }

    public function store(Request $request) : Response
    {
        if (!$this->user->hasPermission(new Permission\SubmitLink())) {
            $this->session->getFlashBag()->add('errors', 'You have to login before you submit a link.');
            return new RedirectResponse('/login');
        }

        $response = new RedirectResponse('/submit');
        $form = $this->submitFormFactory->createFromRequest($request);
        
        if ($form->hasValidationErrors()) {
            foreach ($form->getValidationErrors() as $errorMessage) {
                $this->session->getFlashBag()->add('errors', $errorMessage);
            }
            return $response;
        }

        if (!$this->user instanceof AuthenticatedUser) {
            throw new \LogicException('Only authenticated users can submit links');
        }
        
        $this->submitLinkHandler->handle($form->toCommand($this->user));

        $this->session->getFlashBag()->add('success', 'Your url was submitted successfully');
        return $response;
    }
}
