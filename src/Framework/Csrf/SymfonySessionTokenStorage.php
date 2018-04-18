<?php declare(strict_types=1);

namespace App\Framework\Csrf;

use Symfony\Component\HttpFoundation\Session\SessionInterface;


final class SymfonySessionTokenStorage implements TokenStorage
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function store(string $key, Token $token)
    {
        $this->session->set($key, $token->toString());
    }

    public function retrieve(string $key): ?Token
    {
        $tokenValue = $this->session->get($key);
        return ($tokenValue===null) ? null : new Token($tokenValue);
    }

}