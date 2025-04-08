<?php

namespace App\Controllers;

use App\Models\JobApplicationModel;

class JobApplicationController
{
    public function apply($data): bool
    {
        $jobApplicationModel = new jobapplicationModel();
        $applicationSuccess = $jobApplicationModel->createJobApplication($data);

        if ($applicationSuccess) {

        }
    }
}