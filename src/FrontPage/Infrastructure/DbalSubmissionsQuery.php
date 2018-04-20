<?php declare (strict_types = 1);

namespace App\FrontPage\Infrastructure;

use App\FrontPage\Application\SubmissionsQuery;
use Doctrine\DBAL\Connection;
use App\FrontPage\Application\Submission;

final class DbalSubmissionsQuery implements SubmissionsQuery
{
    private $submissions;
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;        
    }

    public function execute() : array
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('submissions.title', 'submissions.url', 'authors.nickname');
        $qb->from('submissions');
        $qb->join(
            'submissions', 'users', 'authors', 'submissions.author_user_id = authors.id'
        );
        $qb->orderBy('submissions.creation_date', 'DESC');
        
        $sql = $qb->getSql();
        $stmt = $this->connection->query($sql);

        $submissions = [];
        while($row = $stmt->fetch()) {
            $submissions[] = new Submission($row['title'], $row['url'], $row['nickname']);
        }

        return $submissions;
    }
}
