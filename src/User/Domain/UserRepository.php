<?php declare(strict_types=1);

namespace App\User\Domain;

interface UserRepository
{
    public function add(User $user) : void;

    public function findByNickname(string $nickname) : ?User;

    public function save(User $user) : void;
}