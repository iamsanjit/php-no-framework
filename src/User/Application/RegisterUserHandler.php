<?php declare(strict_types=1);

namespace App\User\Application;

use App\User\Domain\UserRepository;
use App\User\Domain\User;


final class RegisterUserHandler
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(RegisterUser $registerUser) : void
    {
        $user = User::register(
            $registerUser->getNickname(),
            $registerUser->getPassword()
        );
        
        $this->userRepository->add($user);
    }
}