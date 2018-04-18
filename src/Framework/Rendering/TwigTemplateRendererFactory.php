<?php declare(strict_types=1);

namespace App\Framework\Rendering;

use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_Function;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Framework\Rendering\TemplateDirectory;
use App\Framework\Csrf\StoredTokenReader;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;


final class TwigTemplateRendererFactory
{

    private $templateDirectory;
    private $storedTokenReader;
    private $session;

    public function __construct(
        TemplateDirectory $templateDirectory,
        StoredTokenReader $storedTokenReader,
        SessionInterface $session
    ) {
        $this->templateDirectory = $templateDirectory;
        $this->storedTokenReader = $storedTokenReader;
        $this->session = $session;
    }

    public function create() : TwigTemplateRenderer
    {
        $templateDirectory = $this->templateDirectory->toString();
        $loader = new Twig_Loader_Filesystem([$templateDirectory]);
        $twigEnvironment = new Twig_Environment($loader);

        // Move these function to somewhere else if we endup with lot of functions
        $twigEnvironment->addFunction(new Twig_Function('get_token', function (string $key) : string {
            $token = $this->storedTokenReader->read($key);
            return $token->toString();
        }));

        $twigEnvironment->addFunction(new Twig_Function('get_flash_bag', function () : FlashBagInterface {
            return $this->session->getFlashBag();
        }));

        return new TwigTemplateRenderer($twigEnvironment);
    }
}