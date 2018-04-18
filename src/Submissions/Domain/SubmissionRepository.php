<?php declare(strict_types=1);

namespace App\Submissions\Domain;

use App\Submissions\Domain\Submission;

interface SubmissionRepository
{
    public function add (Submission $submission) : void;
}