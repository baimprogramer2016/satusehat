<?php

namespace App\Repositories\JobLogs;

interface JobLogsInterface
{
    public function getDataJobLogs();
    public function insertJobLogsStart($param = []);
    public function updateJobLogsEnd($param = []);
    public function getDataJobLogAlreadyRun($kode);
}
