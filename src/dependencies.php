<?php declare(strict_types = 1);

use Auryn\Injector;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;

use App\Framework\Rendering\TemplateRenderer;
use App\Framework\Rendering\TwigTemplateRendererFactory;
use App\Framework\Rendering\TemplateDirectory;

use App\Framework\Dbal\DatabaseUrl;
use App\Framework\Dbal\ConnectionFactory;

use App\Framework\Csrf\StoredTokenReader;
use App\Framework\Csrf\SymfonySessionTokenStorage;
use App\Framework\Csrf\TokenStorage;

use App\FrontPage\Application\SubmissionsQuery;
use App\FrontPage\Infrastructure\DbalSubmissionsQuery;

use App\Submissions\Domain\SubmissionRepository;
use App\Submissions\Domain\DbalSubmissionRepository;

use App\User\Domain\UserRepository;
use App\User\Infrastructure\DbalUserRepository;
use App\User\Application\NickNameTakenQuery;
use App\User\Infrastructure\DbalNickNameTakenQuery;

use App\Framework\Rbac\User;
use App\Framework\Rbac\CurrentUserFactory;
use App\Framework\Rbac\SymfonySessionCurrentUserFactory;


$injector = new Injector();

$injector->define(TemplateDirectory::class, [':rootDirectory' => ROOT_DIR]);

$injector->delegate(
    TemplateRenderer::class,
    function () use ($injector) : TemplateRenderer {
        $factory = $injector->make(TwigTemplateRendererFactory::class);
        return $factory->create();
    }
);

$injector->delegate(
    User::class,
    function() use ($injector) : User {
        $factory = $injector->make(CurrentUserFactory::class);
        return $factory->create();
    }  
);

$injector->alias(CurrentUserFactory::class, SymfonySessionCurrentUserFactory::class);

$injector->alias(SubmissionsQuery::class, DbalSubmissionsQuery::class);
$injector->share(SubmissionsQuery::class);

$injector->alias(SubmissionRepository::class, DbalSubmissionRepository::class);
$injector->alias(UserRepository::class, DbalUserRepository::class);
$injector->alias(NickNameTakenQuery::class, DbalNickNameTakenQuery::class);


$injector->define(DatabaseUrl::class, [':url' => 'sqlite:///' . ROOT_DIR . '/storage/db.sqlite3']);

$injector->delegate(Connection::class, function () use ($injector) : Connection {
    $factory = $injector->make(ConnectionFactory::class);
    return $factory->create();
});
$injector->share(Connection::class);

$injector->alias(TokenStorage::class, SymfonySessionTokenStorage::class);

$injector->alias(SessionInterface::class, Session::class);

// We dont need to add it. injector will figure out this for us;
// $injector->define(StoredTokenReader::class);

return $injector;
