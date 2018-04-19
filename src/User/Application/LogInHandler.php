<?php declare(strict_types=1);

namespace App\User\Application;

use App\User\Domain\UserRepository;
use App\User\Domain\User;


final class LogInHandler
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(LogIn $command) : void
    {
        $user = $this->userRepository->findByNickname($command->getUsername());

        if ($user === null) return;
        
        // It doesn't return anything because its a command not a query
        $user->login($command->getPassword());

        $this->userRepository->save($user);
    }
}