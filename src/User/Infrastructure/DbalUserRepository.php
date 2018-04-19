<?php declare(strict_types=1);

namespace App\User\Infrastructure;

use Doctrine\DBAL\Connection;
use App\User\Domain\UserRepository;
use App\User\Domain\User;


final class DbalUserRepository implements UserRepository
{

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;    
    }

    public function add(User $user) : void
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->insert('users')->values([
            'id' => $qb->createNamedParameter($user->getId()->toString()),
            'nickname' => $qb->createNamedParameter($user->getNickname()),
            'password_hash' => $qb->createNamedParameter($user->getPasswordHash()),
            'creation_date' => $qb->createNamedParameter($user->getCreationDate()->format('y-m-d H:i:s'))
        ]);

        $qb->execute();
    }

}