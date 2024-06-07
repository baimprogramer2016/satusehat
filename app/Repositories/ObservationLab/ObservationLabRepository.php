<?php

namespace App\Repositories\ObservationLab;

use App\Models\Observation;
use App\Models\ObservationLab;
use Carbon\Carbon;

class ObservationLabRepository implements ObservationLabInterface
{
    private $model, $observation_model;
    public function __construct(
        ObservationLab $observationLabModel,
        Observation $observationModel
    ) {
        $this->model = $observationLabModel;
        $this->observation_model = $observationModel;
    }
    public function getQuery()
    {
        return $this->model->query()->where('procedure', 'lab');
    }

    //untuk observation


    public function getObservationLabQuery($request = [])
    {
        $q = $this->observation_model->query()->where('type_observation', 'Laboratory');

        //FILTER
        $q->when($request['status_kirim'] != '', function ($query) use ($request) {
            switch ($request['status_kirim']) {
                case 'waiting':
                    $query->whereNull('satusehat_statuscode');
                    break;
                case 'failed':
                    $query->where('satusehat_statuscode', '500');
                    break;
                default:
                    $query->where('satusehat_statuscode', '200');
            }
            return $query;
        });
        $q->when($request['tanggal_awal'] != '', function ($query) use ($request) {

            $query->whereBetween('effective_datetime', [
                Carbon::createFromFormat('Y-m-d', $request['tanggal_awal'])->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $request['tanggal_akhir'])->endOfDay(),
            ]);

            return $query;
        });


        return $q;
    }

    #ini untuk bundle
    public function getDataObservationLabByOriginalCode($original_code)
    {
        return $this->model->where('encounter_original_code', $original_code)
            ->where('procedure', 'lab')
            // ->whereNotIn('procedure_unit', ['mm/jam', 'mm3', 'Pg'])
            ->orderBy('id', 'asc')->get();
    }
    public function getDataObservationLabBundleByOriginalCode($original_code)
    {
        return $this->model
            ->select('ss_service_request.*')
            ->join('ss_observation', 'ss_service_request.uuid_observation', '=', 'ss_observation.uuid')
            ->where('ss_service_request.encounter_original_code', $original_code)
            ->where('ss_service_request.procedure', 'lab')
            ->whereNull('ss_observation.satusehat_id')
            ->orderBy('ss_service_request.id', 'asc')
            ->get();
    }

    public function getDataObservationLabFind($uuid)
    {
        return $this->model->where('uuid_observation', $uuid)->first(); //dari table service request
    }

    public function updateStatusObservationLab($uuid, $satusehat_id, $request, $response) //update di table observation
    {
        $data = $this->observation_model->where('uuid', $uuid)
            ->update([
                'satusehat_id' => $satusehat_id,
                'satusehat_request' => $request,
                'satusehat_response' => $response,
                'satusehat_send' => ($satusehat_id != null) ? 1 : 0,
                'satusehat_statuscode' => ($satusehat_id != null) ? '200' : '500',
                'satusehat_date' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);


        return $data;
    }
    public function getDataObservationLabReadyJob()
    {
        return $this->model->join('ss_encounter', 'ss_service_request.encounter_original_code', 'ss_encounter.original_code')
            ->join('ss_observation', 'ss_service_request.uuid_observation', 'ss_observation.uuid')
            ->whereNotNull('ss_encounter.satusehat_id')
            ->take(env('MAX_RECORD')) //ambil hanya 100 saja
            ->where('ss_service_request.procedure', 'lab')
            ->where('ss_encounter.satusehat_send', '=', 1)
            ->where('ss_encounter.satusehat_statuscode', '=', '200')

            ->whereNotNull('ss_service_request.satusehat_id_specimen')
            ->where('ss_service_request.satusehat_send_specimen', '=', 1)
            ->where('ss_service_request.satusehat_statuscode_specimen', '=', '200')

            ->where('ss_observation.satusehat_send', '!=', 1)
            ->whereNull('ss_observation.satusehat_id')
            ->whereNull('ss_observation.satusehat_statuscode')
            // ->whereIn('original_code', ['A112306380'])
            ->select('ss_service_request.*')
            ->get();
    }
}
