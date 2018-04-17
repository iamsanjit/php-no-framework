<?php declare (strict_types = 1);

use Auryn\Injector;

use App\Framework\Rendering\TemplateRenderer;
use App\Framework\Rendering\TwigTemplateRendererFactory;


$injector = new Injector();

$injector->delegate(
    TemplateRenderer::class,
    function() use ($injector) : TemplateRenderer {
        $factory = $injector->make(TwigTemplateRendererFactory::class);
        return $factory->create();
    }
);

return $injector;