<?php declare(strict_types=1);

namespace App\Framework\Rbac\Role;

use App\Framework\Dbal\Role;

final class Author extends Role
{
    protected function getPermissions() : array
    {
        return [
            new SubmitLink()
        ];
    }
}