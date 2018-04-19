<?php declare(strict_type=1);

namespace App\User\Presentation;

use App\Framework\Csrf\StoredTokenValidator;
use App\Framework\Csrf\Token;
use App\User\Application\RegisterUser;
use App\User\Application\NickNameTakenQuery;

final class RegisterUserForm
{
    private $storedTokenValidator;
    private $token;
    private $nickname;
    private $password;

    public function __construct(
        StoredTokenValidator $storedTokenValidator,
        NickNameTakenQuery $nickNameTakenQuery,
        ?string $token,
        ?string $nickname,
        ?string $password
    ) {
        $this->storedTokenValidator = $storedTokenValidator;
        $this->nickNameTakenQuery = $nickNameTakenQuery;
        $this->token = $token;
        $this->nickname = $nickname;
        $this->password = $password;
    }

    /** @return string[] */
    public function getValidationErrors() : array
    {
        // Can inject validator class into form to validate efficently
        $errors = [];

        if (!$this->token) {
            $errors[] = 'Missing token.';
        } else if (!$this->storedTokenValidator->validate('registration', new Token($this->token))) {
            $errors[] = 'Invalid token.';
        }

        if (!$this->nickname) {
            $errors[] = 'Nickname required.';
        } else if (strlen($this->nickname) < 1 || strlen($this->nickname) > 16) {
            $errors[] = 'Nickname must be 1 and 16 characters long.';
        } else if ($this->nickNameTakenQuery->execute($this->nickname)) {
            $errors[] = 'Nickname already taken.';
        }

        if (!$this->password) {
            $errors[] = 'Password required.';
        } else if (strlen($this->password) < 6) {
            $errors[] = 'Password must be at least 6 characters long.';
        }

        return $errors;
    }

    public function hasValidationErrors() : bool
    {
        return (count($this->getValidationErrors()) > 0);
    }

    public function toCommand() : RegisterUser
    {
        return new RegisterUser(
            $this->nickname,
            $this->password
        );
    }
}