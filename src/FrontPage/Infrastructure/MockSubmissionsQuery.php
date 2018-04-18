<?php declare(strict_types = 1);

namespace App\FrontPage\Infrastructure;

use App\FrontPage\Application\SubmissionsQuery;

final class MockSubmissionsQuery implements SubmissionsQuery
{
    private $submissions;

    public function __construct()
    {
        $this->submissions = [
            ['url' => 'https://www.google.com', 'title' => 'Google'],
            ['url' => 'https://www.github.com', 'title' => 'Github']
        ];
    }

    public function execute(): array
    {
        return $this->submissions;
    }
}
