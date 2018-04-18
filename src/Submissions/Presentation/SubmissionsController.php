<?php declare (strict_types = 1);

namespace App\Submissions\Presentation;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Framework\Rendering\TemplateRenderer;
use App\FrontPage\Application\SubmissionsQuery;
use App\Framework\Csrf\StoredTokenValidator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Framework\Csrf\Token;

final class SubmissionsController
{
    protected $templateRenderer;
    protected $storedTokenValidator;
    protected $session;

    public function __construct(
        TemplateRenderer $templateRenderer,
        StoredTokenValidator $tokenValidator,
        SessionInterface $session
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->storedTokenValidator = $tokenValidator;
        $this->session = $session;
    }

    public function create(Request $request) : Response
    {
        $content = $this->templateRenderer->render('Submission.html.twig');
        return new Response($content);
    }

    public function store(Request $request) : Response
    {
        $response = new RedirectResponse('/submit');

        $token = new Token((string)$request->get('csrf_token'));

        //TODO Add dedicated flash messenger class to framework
        if (!$this->storedTokenValidator->validate('submission', $token)) {
            $this->session->getFlashBag()->add('errors', 'Invalid Token');
            return $response;
        }

        $this->session->getFlashBag()->add('success', 'Your url was submitted successfully');
        return $response;
    }
}
