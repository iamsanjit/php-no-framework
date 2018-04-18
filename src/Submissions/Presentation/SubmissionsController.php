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
use App\Submissions\Application\SubmitLinkHandler;
use App\Submissions\Application\SubmitLink;

final class SubmissionsController
{
    protected $templateRenderer;
    protected $submitFormFactory;
    protected $session;
    protected $submitLinkHandler;

    public function __construct(
        TemplateRenderer $templateRenderer,
        SubmitFormFactory $submitFormFactory,
        SessionInterface $session,
        SubmitLinkHandler $submitLinkHandler
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->submitFormFactory = $submitFormFactory;
        $this->session = $session;
        $this->submitLinkHandler = $submitLinkHandler;
    }

    public function create(Request $request) : Response
    {
        $content = $this->templateRenderer->render('Submission.html.twig');
        return new Response($content);
    }

    public function store(Request $request) : Response
    {
        $response = new RedirectResponse('/submit');
        $form = $this->submitFormFactory->createFromRequest($request);
        
        if ($form->hasValidationErrors()) {
            foreach ($form->getValidationErrors() as $errorMessage) {
                $this->session->getFlashBag()->add('errors', $errorMessage);
            }
            return $response;
        }

        $this->submitLinkHandler->handle($form->toCommand());

        $this->session->getFlashBag()->add('success', 'Your url was submitted successfully');
        return $response;
    }
}
