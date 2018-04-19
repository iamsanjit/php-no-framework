<?php declare(strict_types=1);

namespace App\User\Presentation;

use Symfony\Component\HttpFoundation\Request;
use App\Framework\Csrf\StoredTokenValidator;
use App\User\Application\NickNameTakenQuery;

final class RegisterFormFactory
{
    private $storedTokenValidator;
    private $nickNameTakenQuery;

    public function __construct(
        StoredTokenValidator $storedTokenValidator,
        NicknameTakenQuery $nickNameTakenQuery
    ) {
        $this->storedTokenValidator = $storedTokenValidator;
        $this->nickNameTakenQuery = $nickNameTakenQuery;
    }

    public function createFromRequest(Request $request) : RegisterUserForm
    {
        return new RegisterUserForm(
            $this->storedTokenValidator,
            $this->nickNameTakenQuery,
            $request->get('csrf_token'),
            $request->get('nickname'),
            $request->get('password')
        );
    }
}