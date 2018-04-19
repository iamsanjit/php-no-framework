<?php declare(strict_types=1);

namespace App\Submissions\Presentation;

use App\Framework\Csrf\StoredTokenValidator;
use Symfony\Component\HttpFoundation\Request;
use App\FrontPage\Application\Submission;

final class SubmitFormFactory
{
    private $storedTokenValidator;

    public function __construct(StoredTokenValidator $storedTokenValidator)
    {
        $this->storedTokenValidator = $storedTokenValidator;
    }

    public function createFromRequest(Request $request) : SubmissionForm
    {
        return new SubmissionForm(
            $this->storedTokenValidator,
            $request->get('csrf_token'), 
            $request->get('title'),
            $request->get('url')
        );
    }
}