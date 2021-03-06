<?php declare(strict_types=1);

namespace App\Submissions\Domain;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

final class Submission
{
    private $id;
    private $authorId;
    private $url;
    private $title;
    private $creationDate;

    private function __construct(
        UuidInterface $id,
        AuthorId $authorId,
        string $title,
        string $url,
        DateTimeImmutable $creationDate
    ) {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->title = $title;
        $this->url = $url;
        $this->creationDate = $creationDate;
    }

    public function getId() : UuidInterface
    {
        return $this->id;
    }

    public function getAuthorId() : AuthorId
    {
        return $this->authorId;
    }

    public function getUrl() : string
    {
        return $this->url;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getCreationDate() : DateTimeImmutable
    {
        return $this->creationDate;
    }

    public static function submit(UuidInterface $authorId, string $title, string $url) : Submission
    {
        return new Submission(
            Uuid::uuid4(),
            AuthorId::fromUuid($authorId),
            $url,
            $title,
            new DateTimeImmutable()
        );
    }
}