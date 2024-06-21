<?php

namespace App\Repositories\Encounter;

use App\Models\Encounter;
use App\Repositories\Location\LocationInterface;
use Carbon\Carbon;
use App\Traits\GeneralTrait;

class EncounterRepository implements EncounterInterface
{

    use GeneralTrait;
    private $model, $location_model;
    public function __construct(Encounter $encounterModel, LocationInterface $locationInterface)
    {
        $this->model = $encounterModel;
        $this->location_model = $locationInterface;
    }

    # untuk mendapatkan keseluruhan data
    public function getQuery($request = [])
    {
        $q = $this->model->query();

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

            $query->whereBetween('period_start', [
                Carbon::createFromFormat('Y-m-d', $request['tanggal_awal'])->startOfDay(),
                Carbon::createFromFormat('Y-m-d', $request['tanggal_akhir'])->endOfDay(),
            ]);

            return $query;
        });


        return $q;
    }


    # BUNDLE
    public function getDataBundleReadyJob()
    {
        return $this->model
            ->take(env('MAX_RECORD')) //ambil hanya 100 saja
            ->whereNull('satusehat_id')
            ->where('satusehat_send', '!=', 1)
            ->whereNull('satusehat_statuscode')
            // ->whereIn('original_code', ['A122304622'])
            ->get();
    }

    //tes2
    public function updateDataBundleEncounterJob($param = [])
    {
        $data = $this->model->find($param['id']);
        $data->satusehat_id = $param['satusehat_id'];
        $data->satusehat_send = $param['satusehat_send'];
        $data->satusehat_date = $param['satusehat_date'];
        $data->satusehat_statuscode = $param['satusehat_statuscode'];
        $data->satusehat_request = $param['satusehat_request'];
        $data->satusehat_response = $param['satusehat_response'];
        $data->update();
        return $data;
    }

    # END BUNDLE

    public function getDataEncounterFind($id)
    {
        return $this->model->find($id);
    }

    public function updateStatusEncounter($id, $satusehat_id, $request, $response)
    {
        $data = $this->model->find($id);
        $data->satusehat_id = $satusehat_id;
        $data->satusehat_request = $request;
        $data->satusehat_response = $response;
        $data->satusehat_send = ($satusehat_id != null) ? 1 : 0;
        $data->satusehat_statuscode =  ($satusehat_id != null) ? '200' : '500';
        $data->satusehat_date = Carbon::now()->format('Y-m-d H:i:s');
        $data->update();

        return $data;
    }

    public function getDataEncounterByOriginalCode($original_code)
    {
        return $this->model->where('original_code', $original_code)->first();
    }

    public function storeEncounter($request =  [])
    {
        $cari_jenis_rawat = $this->cariJenisRawat($request['jenis_rawat']);
        $location_display = $this->location_model->getDataLocationByIDSS($request['location_reference']);


        $this->model->create([
            'original_code' => $request['original_code'],
            'identifier_value' => $request['original_code'],
            'status' => 'finished',
            'class_code' => $cari_jenis_rawat['kode'],
            'class_display' => $cari_jenis_rawat['keterangan'],
            'participant_coding_code' => "ATND",
            'participant_coding_display' => "attender",
            'subject_nik' => $request['subject_nik'],
            'subject_reference' => $request['subject_reference'],
            'subject_display' => $request['subject_display'],
            'participant_nik' => $request['participant_nik'],
            'participant_individual_reference' => $request['participant_individual_reference'],
            'participant_individual_display' => $request['participant_individual_display'],
            'location_reference' => $request['location_reference'],
            'location_display' => $location_display['name'],
            'satusehat_send' => 4,
            'period_start' => $this->formatDate($request['period_start'], $request['period_start_hour'], $request['period_start_minute']),
            'period_end' => $this->formatDate($request['period_end'], $request['period_end_hour'], $request['period_end_minute']),
            'status_history_arrived_status' => $request['status_history_arrived_status'],
            'status_history_arrived_start' => $this->formatDate($request['status_history_arrived_start'], $request['status_history_arrived_hour'], $request['status_history_arrived_minute']),
            'status_history_arrived_end' => $this->formatDate($request['status_history_inprogress_start'], $request['status_history_inprogress_hour'], $request['status_history_inprogress_minute']),
            'status_history_inprogress_status' => $request['status_history_inprogress_status'],
            'status_history_inprogress_start' => $this->formatDate($request['status_history_inprogress_start'], $request['status_history_inprogress_hour'], $request['status_history_inprogress_minute']),
            'status_history_inprogress_end' => $this->formatDate($request['status_history_finished_start'], $request['status_history_finished_hour'], $request['status_history_finished_minute']),
            'status_history_finished_status' => $request['status_history_finished_status'],
            'status_history_finished_start' => $this->formatDate($request['status_history_finished_start'], $request['status_history_finished_hour'], $request['status_history_finished_minute']),
            'status_history_finished_end' => $this->formatDate($request['status_history_finished_start'], $request['status_history_finished_hour'], $request['status_history_finished_minute']),
            'uuid' => $this->getUUID(),
            'uuid_procedure' => $this->getUUID()
            // Tambahkan field-field lainnya di sini...
        ]);

        return $this->model;
    }
    public function updateEncounter($request =  [], $id)
    {
        $cari_jenis_rawat = $this->cariJenisRawat($request['jenis_rawat']);
        $location_display = $this->location_model->getDataLocationByIDSS($request['location_reference']);

        $data = $this->getDataEncounterFind($id);

        $data->original_code = $request['original_code'];
        $data->identifier_value = $request['original_code'];
        $data->status = 'finished';
        $data->class_code = $cari_jenis_rawat['kode'];
        $data->class_display = $cari_jenis_rawat['keterangan'];
        $data->participant_coding_code = "ATND";
        $data->participant_coding_display = "attender";
        $data->subject_nik = $request['subject_nik'];
        $data->subject_reference = $request['subject_reference'];
        $data->subject_display = $request['subject_display'];
        $data->participant_nik = $request['participant_nik'];
        $data->participant_individual_reference = $request['participant_individual_reference'];
        $data->participant_individual_display = $request['participant_individual_display'];
        $data->location_reference = $request['location_reference'];
        $data->location_display = $location_display['name'];
        $data->satusehat_send = 4;
        $data->period_start = $this->formatDate($request['period_start'], $request['period_start_hour'], $request['period_start_minute']);
        $data->period_end = $this->formatDate($request['period_end'], $request['period_end_hour'], $request['period_end_minute']);
        $data->status_history_arrived_status = $request['status_history_arrived_status'];
        $data->status_history_arrived_start = $this->formatDate($request['status_history_arrived_start'], $request['status_history_arrived_hour'], $request['status_history_arrived_minute']);
        $data->status_history_arrived_end = $this->formatDate($request['status_history_inprogress_start'], $request['status_history_inprogress_hour'], $request['status_history_inprogress_minute']);
        $data->status_history_inprogress_status = $request['status_history_inprogress_status'];
        $data->status_history_inprogress_start = $this->formatDate($request['status_history_inprogress_start'], $request['status_history_inprogress_hour'], $request['status_history_inprogress_minute']);
        $data->status_history_inprogress_end = $this->formatDate($request['status_history_finished_start'], $request['status_history_finished_hour'], $request['status_history_finished_minute']);
        $data->status_history_finished_status = $request['status_history_finished_status'];
        $data->status_history_finished_start = $this->formatDate($request['status_history_finished_start'], $request['status_history_finished_hour'], $request['status_history_finished_minute']);
        $data->status_history_finished_end = $this->formatDate($request['status_history_finished_start'], $request['status_history_finished_hour'], $request['status_history_finished_minute']);
        $data->update();

        return $this->model;
    }
}
