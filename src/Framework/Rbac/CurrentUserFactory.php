<?php declare(strict_types=1);

namespace App\Framework\Rbac;

interface CurrentUserFactory
{
    public function create() : User;
}