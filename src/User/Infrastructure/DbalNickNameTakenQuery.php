<?php declare(strict_types=1);

namespace App\User\Infrastructure;

use Doctrine\DBAL\Connection;
use App\User\Application\NickNameTakenQuery;


final class DbalNickNameTakenQuery implements NickNameTakenQuery
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function execute(string $nickname) : bool
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('count(*)')
            ->from('users')
            ->where("nickname = {$qb->createNamedParameter($nickname)}");

        $stmt = $qb->execute();
        return (bool) $stmt->fetchColumn();
    }   

}