<?php declare(strict_types = 1);
    
namespace App\FrontPage\Application;

final class Submisssion
{
    private $title;
    private $url;

    public function __consturct(string $title, string $url)
    {
        $this->url = $url;
        $this->title = $title;
    }

    public function getUrl() : string
    {
        return $this->url;
    }

    public function getTitle() : string
    {
        return $this->title;
    }
}
