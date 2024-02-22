<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;

class LaporanExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    use Exportable;
    public $parameter;
    public $dashboard_repo;
    public function __construct(
        $parameter,
        $dashboard_repo
    ) {
        $this->parameter = $parameter;
        $this->dashboard_repo = $dashboard_repo;
    }
    public function collection()
    {
        switch ($this->parameter['resource']) {
            case 'encounter':
                $result =  $this->dashboard_repo->getLaporanEncounter($this->parameter);
                break;
            case 'condition':
                $result =  $this->dashboard_repo->getLaporanCondition($this->parameter);
                break;
            case 'observation':
                $result =  $this->dashboard_repo->getLaporanObservation($this->parameter);
                break;
            case 'medication_request':
                $result =  $this->dashboard_repo->getLaporanMedicationRequest($this->parameter);
                break;
            case 'medication_dispense':
                $result =  $this->dashboard_repo->getLaporanMedicationDispense($this->parameter);
                break;
            case 'laboratorium':
                $result =  $this->dashboard_repo->getLaporanLaboratorium($this->parameter);
                break;
            default:
                $result =  [];
        }

        return $result;
        // return new Collection([
        //     [1, 2, 3],
        //     [4, 5, 6]
        // ]);
    }
}
