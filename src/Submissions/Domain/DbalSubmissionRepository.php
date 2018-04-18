<?php declare(strict_types=1);

namespace App\Submissions\Domain;

use Doctrine\DBAL\Connection;


final class DbalSubmissionRepository implements SubmissionRepository
{

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;    
    }

    public function add(Submission $submission) : void
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->insert('submissions')->values([
            'id' => $qb->createNamedParameter($submission->getId()->toString()),
            'title' => $qb->createNamedParameter($submission->getTitle()),
            'url' => $qb->createNamedParameter($submission->getUrl()),
            'creation_date' => $qb->createNamedParameter($submission->getCreationDate()->format('Y-m-d H:i:s'))
        ]);

        $qb->execute();
    }

}