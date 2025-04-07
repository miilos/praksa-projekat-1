<?php

namespace App\Controllers;

use App\Models\JobModel;
use App\Pages\JobRenderer;

class JobController
{
    public function getAllJobs($title): void
    {
        $jobModel = new JobModel();
        $jobs = $jobModel->getAllJobs();

        $jobRenderer = new JobRenderer();
        $html = $jobRenderer->renderJobs($title, $jobs);

        echo $html;
    }
}