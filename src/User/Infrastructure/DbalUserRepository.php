<?php declare(strict_types=1);

namespace App\User\Infrastructure;

use DateTimeImmutable;
use LogicException;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;

use App\User\Domain\UserRepository;
use App\User\Domain\User;
use Ramsey\Uuid\Uuid;
use App\User\Domain\UserWasLoggedIn;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


final class DbalUserRepository implements UserRepository
{

    private $connection;
    private $session;

    public function __construct(Connection $connection, SessionInterface $session)
    {
        $this->connection = $connection;
        $this->session = $session;    
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

    public function save(User $user) : void
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->update('users')
            ->set('nickname', $qb->createNamedParameter($user->getNickname()))
            ->set('failed_login_attempts', $qb->createNamedParameter($user->getFailedLoginAttempts()))
            ->set('last_failed_login_attempt', $qb->createNamedParameter(
                $user->getLastFailedLoginAttempt(),
                Type::DATETIME
            ))
            ->where("id='{$user->getId()->toString()}'")
            ->execute();

        // Handling recorded events on User
        foreach ($user->getRecordedEvents() as $event) {
            if ($event instanceof UserWasLoggedIn) {
                $this->session->set('userId', $user->getId()->toString());
                continue;
            }
            throw new LogicException(get_class($event) . 'was not handled.');
        }
        $user->clearRecordedEvents();
    }

    public function findByNickname(string $nickname) : ?User
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select(
            'id',
            'nickname',
            'password_hash',
            'failed_login_attempts',
            'last_failed_login_attempt',
            'creation_date')
        ->from('users')
        ->where("nickname = {$qb->createNamedParameter($nickname)}");

        $stmt = $qb->execute();
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return $this->createUserFromRow($row);
    }

    private function createUserFromRow(array $row) : ?User
    {
        if (!$row) {
            return null;
        }

        $lastFailedLoginAttempt = null;
        if ($row['last_failed_login_attempt']) {
            $lastFailedLoginAttempt = new DateTimeImmutable($row['last_failed_login_attempt']);
        }

        return new User(
            Uuid::uuid4($row['id']),
            $row['nickname'],
            $row['password_hash'],
            new DateTimeImmutable($row['creation_date']),
            (int) $row['failed_login_attempts'],
            $lastFailedLoginAttempt
        );

    }

}