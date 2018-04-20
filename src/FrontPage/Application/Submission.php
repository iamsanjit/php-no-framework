<?php declare(strict_types = 1);
    
namespace App\FrontPage\Application;

final class Submission
{
    private $title;
    private $url;

    public function __construct(string $title, string $url, string $author)
    {
        $this->url = $url;
        $this->title = $title;
        $this->author = $author;
    }

    public function getUrl() : string
    {
        return $this->url;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getAuthor() : string
    {
        return $this->author;
    }
}
