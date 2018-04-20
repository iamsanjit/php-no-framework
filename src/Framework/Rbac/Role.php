<?php declare(strict_types=1);

namespace App\Framework\Rbac;

use App\Framework\Rbac\Permission;


abstract class Role
{
    public function hasPermission(Permission $permission) : bool
    {
        return in_array($permission, $this->getPermissions());
    } 

    /** @return App\Framework\Rbac\Permission[] */
    abstract protected function getPermissions() : array;
}