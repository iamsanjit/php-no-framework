<?php declare(strict_types=1);

namespace App\Framework\Rbac;

use Ramsey\Uuid\UuidInterface;


final class AuthenticatedUser implements User
{
    private $roles = [];
    private $id;

    public function __construct(UuidInterface $id, array $roles)
    {
        $this->id = $id;
        $this->roles = $roles;
    }

    public function hasPermission(Permission $permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }
}