<?php declare (strict_types = 1);

namespace App\Framework\Rbac;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Framework\Rbac\Role\Author;
use Ramsey\Uuid\Uuid;


final class SymfonySessionCurrentUserFactory implements CurrentUserFactory
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function create(): User
    {
        if (!$this->session->has('userId')) {
            return new Guest();
        }

        return new AuthenticatedUser(
            Uuid::fromString($this->session->get('userId')),
            [new Author()]
        );
    }
}