<?php

namespace App\Controllers;

use App\Models\JobApplicationModel;

class JobApplicationController
{
    public function apply(array $data): bool
    {
        $jobApplicationModel = new jobapplicationModel();
        $applicationSuccess = $jobApplicationModel->createJobApplication($data);

        if ($applicationSuccess) {

        }
    }
}