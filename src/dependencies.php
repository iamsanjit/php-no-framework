<?php declare(strict_types = 1);

use Auryn\Injector;

use App\Framework\Rendering\TemplateRenderer;
use App\Framework\Rendering\TwigTemplateRendererFactory;
use App\Framework\Rendering\TemplateDirectory;

use App\FrontPage\Application\SubmissionsQuery;
use App\FrontPage\Infrastructure\MockSubmissionsQuery;
use App\Framework\Dbal\DatabaseUrl;
use Doctrine\DBAL\Connection;
use App\Framework\Dbal\ConnectionFactory;

$injector = new Injector();

$injector->define(TemplateDirectory::class, [':rootDirectory' => ROOT_DIR]);

$injector->delegate(
    TemplateRenderer::class,
    function () use ($injector) : TemplateRenderer {
        $factory = $injector->make(TwigTemplateRendererFactory::class);
        return $factory->create();
    }
);

// Mark the Mock Submission Query as the default implementation of submission query
$injector->alias(SubmissionsQuery::class, MockSubmissionsQuery::class);
// Use same object for all classes that use SubmissionQuery dependency
$injector->share(SubmissionsQuery::class);


$injector->define(DatabaseUrl::class, [':url' => 'sqlite:///' . ROOT_DIR . '/storage/db.sqlite3']);
$injector->delegate(Connection::class, function () use ($injector) : Connection {
    $factory = $injector->make(ConnectionFactory::class);
    return $factory->create();
});
$injector->share(Connection::class);

return $injector;
