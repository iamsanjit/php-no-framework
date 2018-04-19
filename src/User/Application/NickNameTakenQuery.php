<?php declare(strict_types=1);

namespace App\User\Application;

interface NickNameTakenQuery
{
    public function execute(string $nickname) : bool;
}