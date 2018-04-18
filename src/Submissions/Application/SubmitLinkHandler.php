<?php declare(strict_types=1);

namespace App\Submissions\Application;

use App\Submissions\Domain\SubmissionRepository;
use App\Submissions\Domain\Submission;


final class SubmitLinkHandler
{
    private $submissionRepository;

    public function __construct(SubmissionRepository $submissionRepository)
    {
        $this->submissionRepository = $submissionRepository;
    }

    public function handle(SubmitLink $submitLink) : void
    {
        $submission = Submission::submit(
            $submitLink->getUrl(),
            $submitLink->getTitle()
        );
        $this->submissionRepository->add($submission);
    }
}