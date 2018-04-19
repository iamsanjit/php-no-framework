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
        ?string $token,
        ?string $title,
        ?string $url
    ) {
        $this->storedTokenValidator = $storedTokenValidator;
        $this->token = $token;
        $this->title = $title;
        $this->url = $url;
    }

    public function getValidationErrors() : array
    {
        $errors = [];

        if (!$this->token) {
            $errors[] = 'Token required';
        } elseif  (!$this->storedTokenValidator->validate('submission', new Token($this->token))) {
            $errors[] = 'Invalid token.';
        }

        if (!$this->title) {
            $errors[] = 'Title Required.';
        } else if (strlen($this->title) < 1 || strlen($this->title) > 200) {
            $errors[] = 'Title must be between 1 and 200 chracters.';
        }
        
        if (!$this->url) {
            $errors[] = 'Url Required.';
        } else if  (strlen($this->url) < 1 || strlen($this->url) > 200) {
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