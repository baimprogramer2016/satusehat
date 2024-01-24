<?php

namespace App\Repositories\ServiceRequest;

use App\Models\ServiceRequest;
use Carbon\Carbon;

class ServiceRequestRepository implements ServiceRequestInterface
{
    private $model;
    public function __construct(
        ServiceRequest $serviceRequestInterface
    ) {
        $this->model = $serviceRequestInterface;
    }
    public function getQuery()
    {
        return $this->model->query();
    }
}
