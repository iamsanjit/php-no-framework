<?php declare(strict_types = 1);

namespace App\FrontPage\Presentation;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Framework\Rendering\TemplateRenderer;

final class FrontPageController
{
    protected $templateRenderer;
    
    public function __construct(TemplateRenderer $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }

    public function show(Request $request) : Response
    {
        $submissions = [
            ['url' => 'https://www.google.com', 'title' => 'Google'],
            ['url' => 'https://www.github.com', 'title' => 'Github']
        ];

        $content = $this->templateRenderer->render('FrontPage.html.twig', [
            'submissions' => $submissions
        ]);
        
        return new Response($content);
    }
}
