<?php

namespace App\Pages;

use App\Models\JobModel;

class JobRenderer
{
    public function renderJobs($title, $jobs): string
    {
        $html = '
            <div class="jobs-container">
                <h1 class="jobs-title">' . $title . '</h1>
        ';

        foreach ($jobs as $job) {
            $html .= self::renderJob($job);
        }

        $html .= '</div>';

        return $html;
    }

    private function renderJob($job): string
    {
        $html = '
            <div class="job">
                <div class="job-general-info">
                    <h2 class="job-name">' . $job['jobName'] . '</h2>
    
                    <div class="job-employer-info">
                        <h3 class="job-employer-name">' . $job['employerName'] . '</h3>
    
                        <div class="job-employer-location">
                            <span class="material-symbols-outlined">
                                location_on
                            </span>
                            <span>' . $job['location'] . '</span>
                        </div>
                    </div>
                </div>
    
                <div class="job-description">
                    ' . $job['description'] . '
                </div>
    
                <div class="job-details">
                    <div class="job-details-detail job-details-detail--field">
                        ' . $job['field'] . '
                    </div>
                    {{work-from-home}}
                    {{flexible-hours}}
                </div>
    
                <div class="job-salary">
                    <p>Pocetna plata:</p>
                    <h2>' . $this->formatStartingSalary($job['startSalary']) . ' din</h2>
                </div>
    
                <div class="job-apply-btn">
                    <a href="./job.php?id=' . $job['jobId'] . '" class="btn btn--primary">Vidi jos</a>
                </div>
            </div>
        ';

        if ($job['workFromHome']) {
            $html = str_replace('{{work-from-home}}',
                '<div class="job-details-detail">
                        <span class="material-symbols-outlined">
                            home
                        </span>
                        Rad od kuce
                    </div>',
                $html);
        }
        else {
            $html = str_replace('{{work-from-home}}', '', $html);
        }

        if ($job['flexibleHours']) {
            $html = str_replace('{{flexible-hours}}',
                '<div class="job-details-detail">
                        <span class="material-symbols-outlined">
                            schedule
                        </span>
                        Klizno radno vreme
                    </div>',
                $html);
        }
        else {
            $html = str_replace('{{flexible-hours}}', '', $html);
        }

        return $html;
    }

    private function formatStartingSalary($salary): string
    {
        return substr_replace($salary, '.', -3, 0);
    }
}