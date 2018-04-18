<?php declare(strict_types=1);

namespace App\Framework\Csrf;

final class Token
{
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function toString() : string
    {
        return $this->token;
    }

    public static function generate() : Token
    {
        $token = bin2hex(random_bytes(256));
        return new Token($token);
    }

    public function equals(Token $token) : bool
    {
        return $this->token === $token->toString();
    }
}