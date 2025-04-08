<?php

namespace App\Controllers;

use App\Models\JobModel;
use App\Pages\JobRenderer;

class JobController
{
    public function getJobs(string $title, array $filter = []): void
    {
        $jobModel = new JobModel();
        $jobs = [];

        if ($filter) {
           $jobs = $jobModel->getJobs($filter);
        }
        else {
            $jobs = $jobModel->getJobs();
        }

        $jobRenderer = new JobRenderer();
        $html = $jobRenderer->renderJobs($title, $jobs);

        echo $html;
    }

    public function getFilteredJobs(string $title, array $filter): void
    {
        $jobModel = new JobModel();
        $jobs = $jobModel->filterJobs($filter);

        $jobRenderer = new JobRenderer();
        $html = $jobRenderer->renderJobs($title, $jobs);

        echo $html;
    }
}