<?php declare(strict_types=1);

namespace App\Framework\Dbal;

final class DatabaseUrl
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function toString() : string
    {
        return $this->url;
    }
}
