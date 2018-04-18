<?php declare(strict_types=1);

namespace App\Submissions\Presentation;

use App\Framework\Csrf\StoredTokenValidator;
use App\Submissions\Application\SubmitLink;
use App\Framework\Csrf\Token;


final class SubmissionForm
{
    private $storedTokenValidator;
    private $token;
    private $title;
    private $url;

    public function __construct(
        StoredTokenValidator $storedTokenValidator,
        string $token,
        string $title,
        string $url
    ) {
        $this->storedTokenValidator = $storedTokenValidator;
        $this->token = trim($token);
        $this->title = trim($title);
        $this->url = trim($url);
    }

    public function getValidationErrors() : array
    {
        $errors = [];

        if (!$this->storedTokenValidator->validate('submission', new Token($this->token))) {
            $errors[] = 'Invalid token.';
        }

        if (strlen($this->title) < 1 || strlen($this->title) > 200) {
            $errors[] = 'Title must be between 1 and 200 chracters.';
        }

        if (strlen($this->url) < 1 || strlen($this->url) > 200) {
            $errors[] = 'URL must be between 1 and 200 chracters.';
        }

        return $errors;
    }

    public function hasValidationErrors() : bool
    {
        return (count($this->getValidationErrors()) > 0);
    }

    public function toCommand() : SubmitLink
    {
        return new SubmitLink($this->url, $this->title);
    }
}