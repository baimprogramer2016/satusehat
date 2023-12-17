<?php

namespace App\Http\Controllers;

use App\Repositories\Kfa\KfaInterface;
use App\Repositories\Parameter\ParameterInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\Datatables;
use App\Traits\GeneralTrait;
use App\Traits\ApiTrait;
use Illuminate\Support\Facades\Http;
use Throwable;



class KfaController extends Controller
{
    use GeneralTrait;
    use ApiTrait;
    private $kfa_repo;
    public $parameter_repo;

    public function __construct(
        KfaInterface $kfaRepository,
        ParameterInterface $parameterInterface
    ) {
        $this->kfa_repo = $kfaRepository;
        $this->parameter_repo = $parameterInterface;
    } //

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->kfa_repo->getQuery();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('pv', function ($item_patient) {

                    $pv = $item_patient->kode_pv . ' - ' . $item_patient->nama_pv;
                    return $pv;
                })
                ->addColumn('pa', function ($item_patient) {

                    $pv = $item_patient->kode_pa . ' - ' . $item_patient->nama_pa;
                    return $pv;
                })
                ->addColumn('kekuatan', function ($item_patient) {

                    $pv = $item_patient->numerator . ' ' . $item_patient->numerator_satuan . ' / ' . $item_patient->denominator . ' ' . $item_patient->denominator_satuan;
                    return $pv;
                })
                ->rawColumns(['pv', 'pa', 'kekuatan'])
                ->make(true);
        }

        // return $this->patient->getAll();
        return view('pages.kfa.kfa');
    }

    public function getKfa(Request $request)
    {
        try {
            # loop 100x

            for ($i = 1; $i <= env('MAX_PAGE_GET_KFA'); $i++) {
                # Auth : Jika expire akan melakukan proses generate token ulang, jika tidak menggunakan token lama
                $this->auth_login_kfa();
                # data generate token
                $data           = $this->parameter_repo->getDataParameterFirst();

                # Rest API -> Hit ke API KFA Kemenkes
                $page           = $data->page_kfa;
                $max_record     = env('MAX_RECORD');
                $url            = env('BASE_URL') . '/kfa-v2/products/all?page=' . $page . '&product_type=farmasi&size=' . $max_record; //92000160,92001283
                $response       = Http::withToken($data->access_token_kfa)->get($url);

                # 204 no content , 200 oke
                if ($response->status() == 200) {
                    $data_kfa = $response['items']['data'];

                    # looping induk
                    foreach ($data_kfa as $key => $item_kfa) {

                        # tampung dalam variable $input
                        $input['kode_kfa']          = $item_kfa['kfa_code'];
                        $input['nama_kfa']          = $item_kfa['name'];
                        $input['kode_pv']           = $item_kfa['product_template']['kfa_code'];
                        $input['nama_pv']           = $item_kfa['product_template']['name'];
                        $input['kode_sediaan']      = $item_kfa['dosage_form']['code'];
                        $input['nama_sediaan']      = $item_kfa['dosage_form']['name'];
                        $input['satuan_disesuaikan'] = null;
                        $input['bahan_baku_aktif']  = null;
                        $input['act_code']          = null;
                        $input['act_display']       = null;

                        # looping item dan proses input ada disini
                        foreach ($item_kfa['active_ingredients'] as $item_ai) {
                            $input['kode_pa']           = $item_ai['kfa_code'];
                            $input['nama_pa']           = $item_ai['zat_aktif'];
                            $input['kode_sistem']       = 'http://unitsofmeasure.org';

                            # pecah pada function split_nominator
                            $res_ks = $this->split_nominator($item_ai['kekuatan_zat_aktif']);

                            $input['numerator']                 = $res_ks['numerator'];
                            $input['numerator_satuan']          = $res_ks['numerator_satuan'];
                            $input['denominator']               = $res_ks['denominator'];
                            $input['denominator_satuan']        = $res_ks['denominator_satuan'];
                            $input['denominator_penyesuaian']   = $res_ks['denominator_penyesuaian'];


                            $this->kfa_repo->insertKfa($input);
                        }
                    }
                    // break;
                    $data->page_kfa = $page + 1;
                    $data->save();
                } else {
                    # Jika Sudah tidak ada data yang ditampilkan
                    return response()->json([
                        "status"    => Response::HTTP_NO_CONTENT,
                        "message"   => 'No Data Available'
                    ]);
                }
            }
            return 'Berhasil';
        } catch (Throwable $e) {
            # Catch Error
            return response()->json([
                "status"    => Response::HTTP_BAD_REQUEST,
                "message"   => "Failed : " . $e
            ]);
        }
    }
}
