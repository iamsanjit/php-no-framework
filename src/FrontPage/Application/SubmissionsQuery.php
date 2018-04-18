<?php declare(strict_types = 1);


namespace App\FrontPage\Application;

interface SubmissionsQuery
{
    /**
     * @return Submission[]
     */
    public function execute() : array;
}
