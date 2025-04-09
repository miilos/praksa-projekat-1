<?php

namespace App\Controllers;

use App\Managers\SuccessManager;
use App\Models\JobModel;
use App\Pages\JobRenderer;

class JobController
{
    public function getJobs(string $title, array $filter = []): void
    {
        $jobs = [];

        if ($filter) {
           $jobs = JobModel::getJobs($filter);
        }
        else {
            $jobs = JobModel::getJobs();
        }

        $jobRenderer = new JobRenderer();
        $html = $jobRenderer->renderJobs($title, $jobs);

        echo $html;
    }

    public function getFilteredJobs(string $title, array $filter): void
    {
        $jobs = JobModel::filterJobs($filter);

        $jobRenderer = new JobRenderer();
        $html = $jobRenderer->renderJobs($title, $jobs);

        echo $html;
    }

    public function createJob(array $data): array|bool
    {
        $jobModel = new JobModel(
            $data['employerId'],
            $data['jobName'],
            $data['description'],
            $data['field'],
            (int)$data['startSalary'],
            $data['shifts'],
            $data['location'],
            isset($data['flexibleHours']) ? true : false,
            isset($data['workFromHome']) ? true : false
        );

        if ($jobModel->validate()) {
            $jobModel->createJob();
            SuccessManager::redirectToSuccessPage('job-created');
            return true;
        }
        else {
           return $jobModel->getValidationErrors();
        }
    }
}