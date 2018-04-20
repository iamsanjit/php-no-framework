<?php declare(strict_types=1);

namespace App\Submissions\Domain;

use Ramsey\Uuid\UuidInterface;


final class AuthorId
{
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromUuid(UuidInterface $id)
    {
        return new AuthorId($id->toString());
    }

    public function toString()
    {
        return $this->id;
    }
}