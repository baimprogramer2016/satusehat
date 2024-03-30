@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Tambah Encounter</h3>
            <div class="nk-block-des text-soft">
                <p>Halaman Kunjungan Pasien</p>
            </div>
        </div><!-- .nk-block-head-content -->
        <div class="nk-block-head-content">

            <div class="toggle-wrap nk-block-tools-toggle">
                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em
                        class="icon ni ni-more-v"></em></a>
                <div class="toggle-expand-content" data-content="pageMenu">
                    <ul class="nk-block-tools g-3">
                        <li><a href="{{ route('encounter') }}" class=" btn btn-white btn-dim btn-outline-primary"><em
                                    class="icon ni ni-list"></em><span>Data</span></a>
                        </li>

                    </ul>
                </div><!-- .toggle-expand-content -->
            </div><!-- .toggle-wrap -->
        </div>

    </div><!-- .nk-block-between -->
</div><!-- .nk-block-head -->
<div class="nk-block">
    <div class="row g-gs">

        <div class="col-xl-12 col-xxl-12">

            <div class="card card-bordered card-full">
                @if (session('pesan'))
                <x-alert pesan="{{ session('pesan') }}" warna="{{ session('warna','success') }}" />
                @endif
                <div class="card card-preview">
                    <div class="card-inner">
                        <form action="{{ route('encounter-simpan') }}" method="POST">
                            @csrf
                            <div class="preview-block">
                                <span class="preview-title-lg overline-title">Encounter</span>
                                <div class="row gy-4">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="form-label" for="original_code">No
                                                Registrasi</label>
                                            <div class="form-control-wrap">
                                                <input type="text"
                                                    class="form-control @error('original_code') is-invalid @enderror"
                                                    id="original_code" name='original_code' placeholder="No Registrasi"
                                                    value="{{ old('original_code') }}" />
                                                @error('original_code')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="form-label" for="original_code">ID Pasien</label>
                                            <div class="form-control-wrap">
                                                <div class="form-text-hint">
                                                    <span class="overline-title p-0"><span class="btn text-success"
                                                            id="clickCheckPasien"
                                                            onclick="return checkIHSPasien('patient')">Klik
                                                            Check</span></span>
                                                </div>
                                                <input type="text"
                                                    class="form-control @error('id_pasien') is-invalid @enderror"
                                                    id="id_pasien" name='id_pasien' placeholder="ID Pasien"
                                                    value="{{ old('id_pasien') }}" />
                                                @error('id_pasien')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="form-label" for="original_code">No. KTP Pasien</label>
                                            <div class="form-control-wrap">
                                                <input type="number"
                                                    class="form-control @error('subject_nik') is-invalid @enderror"
                                                    id="subject_nik" name='subject_nik' readonly placeholder="No. KTP"
                                                    value="{{ old('subject_nik') }}" />
                                                @error('subject_nik')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="form-label" for="original_code"> ID IHS Pasien</label>
                                            <div class="form-control-wrap">
                                                <input type="text" readonly
                                                    class="form-control @error('subject_reference') is-invalid @enderror"
                                                    id="subject_reference" name='subject_reference'
                                                    placeholder="Subject Reference"
                                                    value="{{ old('subject_reference') }}" />
                                                @error('subject_reference')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="form-label" for="subject_display"> Nama Pasien</label>
                                            <div class="form-control-wrap">
                                                <input type="text" readonly
                                                    class="form-control @error('subject_display') is-invalid @enderror"
                                                    id="subject_display" name='subject_display'
                                                    placeholder="Subject Display"
                                                    value="{{ old('subject_display') }}" />
                                                @error('subject_display')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="form-label" for="original_code">ID Dokter</label>
                                            <div class="form-control-wrap">
                                                <div class="form-text-hint">
                                                    <span class="overline-title p-0"><span class="btn text-success"
                                                            id="clickCheckPraktisi"
                                                            onclick="return checkIHSPraktisi('practitioner')">Klik
                                                            Check</span></span>
                                                </div>
                                                <input type="text"
                                                    class="form-control @error('id_praktisi') is-invalid @enderror"
                                                    id="id_praktisi" name='id_praktisi' placeholder="ID Dokter"
                                                    value="{{ old('id_praktisi') }}" />
                                                @error('id_praktisi')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="form-label" for="original_code">No. KTP Dokter</label>
                                            <div class="form-control-wrap">
                                                <input type="number" readonly
                                                    class="form-control @error('participant_nik') is-invalid @enderror"
                                                    id="participant_nik" name='participant_nik' placeholder="No. KTP"
                                                    value="{{ old('participant_nik') }}" />
                                                @error('participant_nik')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="form-label" for="participant_nik">ID IHS
                                                Dokter (Otomatis)</label>
                                            <div class="form-control-wrap">
                                                <input type="text" readonly
                                                    class="form-control @error('participant_individual_reference') is-invalid @enderror"
                                                    id="participant_individual_reference"
                                                    name='participant_individual_reference'
                                                    placeholder="Participant Reference"
                                                    value="{{ old('participant_individual_reference') }}" />
                                                @error('participant_individual_reference')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="form-label" for="participant_nik">Nama Dokter</label>
                                            <div class="form-control-wrap">
                                                <input type="text" readonly
                                                    class="form-control @error('participant_individual_display') is-invalid @enderror"
                                                    id="participant_individual_display"
                                                    name='participant_individual_display' placeholder="ID IHS Dokter"
                                                    value="{{ old('participant_individual_display') }}" />
                                                @error('participant_individual_display')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="form-label" for="default-06">Poli</label>
                                            <div class="form-control-wrap">
                                                <div class="form-control-select">
                                                    <select
                                                        class="form-control @error('location_reference') is-invalid @enderror"
                                                        id="default-06" name="location_reference">
                                                        @foreach ($data_location as $item_location)
                                                        <option {{ old('location_reference')==$item_location->
                                                            satusehat_id ? 'selected' : ''
                                                            }} value="{{ $item_location->satusehat_id }}">
                                                            {{ $item_location->name }}
                                                        </option>
                                                        @endforeach

                                                    </select>
                                                    @error('location_reference')
                                                    <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="form-label" for="default-06">Jenis Rawat</label>
                                            <div class="form-control-wrap">
                                                <div class="form-control-select">
                                                    <select
                                                        class="form-control @error('jenis_rawat') is-invalid @enderror"
                                                        id="jenis_rawat" name="jenis_rawat">
                                                        @foreach ($jenis_rawat as $item_rawat)
                                                        <option {{ old('jenis_rawat')==$item_rawat['kode'] ? 'selected'
                                                            : '' }} value="{{ $item_rawat['kode'] }}">
                                                            {{ $item_rawat['keterangan2'] }}
                                                        </option>
                                                        @endforeach

                                                    </select>
                                                    @error('jenis_rawat')
                                                    <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="form-label" for="default-06">Tanggal Rawat</label>
                                            <div class="form-control-wrap">
                                                <div class="form-icon form-icon-right">
                                                    <em class="icon ni ni-calendar-alt"></em>
                                                </div>
                                                <input type="text"
                                                    class="form-control form-control form-control-outlined date-picker @error('period_start') is-invalid @enderror"
                                                    id="period_start" name="period_start"
                                                    value="{{ old('period_start') }}" />
                                                @error('period_start')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label class="form-label" for="default-06">Jam</label>
                                            <div class="form-control-wrap">
                                                <input type="number"
                                                    class="form-control @error('period_start_hour') is-invalid @enderror"
                                                    id="period_start_hour" name='period_start_hour' placeholder="Jam"
                                                    value="{{ old('period_start_hour') }}" />
                                                @error('period_start_hour')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label class="form-label" for="default-06">Menit</label>
                                            <div class="form-control-wrap">
                                                <input type="number"
                                                    class="form-control @error('period_start_minute') is-invalid @enderror"
                                                    id="period_start_minute" name='period_start_minute'
                                                    placeholder="Jam" value="{{ old('period_start_minute') }}" />
                                                @error('period_start_minute')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="form-label" for="default-06">Tanggal Pulang</label>
                                            <div class="form-control-wrap">
                                                <div class="form-icon form-icon-right">
                                                    <em class="icon ni ni-calendar-alt"></em>
                                                </div>
                                                <input type="text"
                                                    class="form-control form-control form-control-outlined date-picker @error('period_end') is-invalid @enderror"
                                                    id="period_end" name="period_end" value="{{ old('period_end') }}" />
                                                @error('period_end')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label class="form-label" for="default-06">Jam</label>
                                            <div class="form-control-wrap">
                                                <input type="number"
                                                    class="form-control @error('period_end_hour') is-invalid @enderror"
                                                    id="period_end_hour" name='period_end_hour' placeholder="Jam"
                                                    value="{{ old('period_end_hour') }}" />
                                                @error('period_end_hour')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label class="form-label" for="default-06">Menit</label>
                                            <div class="form-control-wrap">
                                                <input type="number"
                                                    class="form-control @error('period_end_minute') is-invalid @enderror"
                                                    id="period_end_minute" name='period_end_minute' placeholder="Jam"
                                                    value="{{ old('period_end_minute') }}" />
                                                @error('period_end_minute')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <span class="preview-title-lg overline-title mt-4">Proses Kunjungan</span>
                                <div class="row gy-4">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="form-label" for="default-06">Tanggal Datang</label>
                                            <input type="hidden" name="status_history_arrived_status" value="arrived">
                                            <div class="form-control-wrap">
                                                <div class="form-icon form-icon-right">
                                                    <em class="icon ni ni-calendar-alt"></em>
                                                </div>
                                                <input type="text"
                                                    class="form-control form-control form-control-outlined date-picker @error('status_history_arrived_start') is-invalid @enderror"
                                                    id="status_history_arrived_start"
                                                    name="status_history_arrived_start"
                                                    value="{{ old('status_history_arrived_start') }}" />
                                                @error('status_history_arrived_start')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="form-label" for="default-06">Jam Datang</label>
                                            <div class="form-control-wrap">
                                                <input type="number"
                                                    class="form-control @error('status_history_arrived_hour') is-invalid @enderror"
                                                    id="status_history_arrived_hour" name='status_history_arrived_hour'
                                                    placeholder="Jam"
                                                    value="{{ old('status_history_arrived_hour') }}" />
                                                @error('status_history_arrived_hour')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="form-label" for="default-06">Menit Datang</label>
                                            <div class="form-control-wrap">
                                                <input type="number"
                                                    class="form-control @error('status_history_arrived_minute') is-invalid @enderror"
                                                    id="status_history_arrived_minute"
                                                    name='status_history_arrived_minute' placeholder="Menit"
                                                    value="{{ old('status_history_arrived_minute') }}" />
                                                @error('status_history_arrived_minute')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gy-4">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="form-label" for="default-06">Tanggal
                                                Ditangani/Pemeriksaan</label>
                                            <input type="hidden" name="status_history_inprogress_status"
                                                value="in-progress">
                                            <div class="form-control-wrap">
                                                <div class="form-icon form-icon-right">
                                                    <em class="icon ni ni-calendar-alt"></em>
                                                </div>
                                                <input type="text"
                                                    class="form-control form-control form-control-outlined date-picker @error('status_history_inprogress_start') is-invalid @enderror"
                                                    id="status_history_inprogress_start"
                                                    name="status_history_inprogress_start"
                                                    value="{{ old('status_history_inprogress_start') }}" />
                                                @error('status_history_inprogress_start')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="form-label" for="default-06">Jam Ditangani/Pemeriksaan</label>
                                            <div class="form-control-wrap">
                                                <input type="number"
                                                    class="form-control @error('status_history_inprogress_hour') is-invalid @enderror"
                                                    id="status_history_inprogress_hour"
                                                    name='status_history_inprogress_hour' placeholder="Jam"
                                                    value="{{ old('status_history_inprogress_hour') }}" />
                                                @error('status_history_inprogress_hour')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="form-label" for="default-06">Menit
                                                Ditangani/Pemeriksaan</label>
                                            <div class="form-control-wrap">
                                                <input type="number"
                                                    class="form-control @error('status_history_inprogress_minute') is-invalid @enderror"
                                                    id="status_history_inprogress_minute"
                                                    name='status_history_inprogress_minute' placeholder="Menit"
                                                    value="{{ old('status_history_inprogress_minute') }}" />
                                                @error('status_history_inprogress_minute')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gy-4">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="form-label" for="default-06">Tanggal Pulang</label>
                                            <input type="hidden" name="status_history_finished_status" value="finished">
                                            <div class="form-control-wrap">
                                                <div class="form-icon form-icon-right">
                                                    <em class="icon ni ni-calendar-alt"></em>
                                                </div>
                                                <input type="text"
                                                    class="form-control form-control form-control-outlined date-picker @error('status_history_finished_start') is-invalid @enderror"
                                                    id="status_history_finished_start"
                                                    name="status_history_finished_start"
                                                    value="{{ old('status_history_finished_start') }}" />
                                                @error('status_history_finished_start')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="form-label" for="default-06">Jam Pulang</label>
                                            <div class="form-control-wrap">
                                                <input type="number"
                                                    class="form-control @error('status_history_finished_hour') is-invalid @enderror"
                                                    id="status_history_finished_hour"
                                                    name='status_history_finished_hour' placeholder="Jam"
                                                    value="{{ old('status_history_finished_hour') }}" />
                                                @error('status_history_finished_hour')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="form-label" for="default-06">Menit Pulang</label>
                                            <div class="form-control-wrap">
                                                <input type="number"
                                                    class="form-control @error('status_history_finished_minute') is-invalid @enderror"
                                                    id="status_history_finished_minute"
                                                    name='status_history_finished_minute' placeholder="Menit"
                                                    value="{{ old('status_history_finished_minute') }}" />
                                                @error('status_history_finished_minute')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div><!-- .card-preview -->
            </div><!-- .card -->
        </div><!-- .col -->
    </div>

</div>
@endsection
@push('script')
<script>
    function checkIHSPasien(param_pa){


        $("#clickCheckPasien").html("Proses..")
        var id_pasien = $("#id_pasien").val()

        if(id_pasien == "")
        {
            $("#clickCheckPasien").html("Klik Check");
            alert("ID Pasien Harus diisi");
        }else{

             $.ajax({
                method: "GET",
                url: "{{ route('encounter-check-nik') }}",
                data: {
                    id_pasien : id_pasien,
                    pa : param_pa
                },
                success: function(response)
                {
                    console.log(response);
                    $("#clickCheckPasien").html("Klik Check");
                    // console.log(response.id_ihs);
                    $("#subject_nik").val(response.nik);
                    $("#subject_display").val(response.nama);
                    $("#subject_reference").val(response.id_ihs);
                    $("#subject_reference").addClass(response.color);
                    $("#subject_display").addClass(response.color);
                    $("#subject_nik").addClass(response.color);
                }

            });
        }
        }
        function checkIHSPraktisi(param_pa){

        $("#clickCheckPraktisi").html("Proses..")
        var id_praktisi = $("#id_praktisi").val()

        if(id_praktisi == "")
        {
            $("#clickCheckPraktisi").html("Klik Check");
            alert("ID Dokter Harus diisi");
        }else{

            $.ajax({
                method: "GET",
                url: "{{ route('encounter-check-nik') }}",
                data: {
                    id_praktisi : id_praktisi,
                    pa : param_pa
                },
                success: function(response)
                {
                    console.log(response);
                    $("#clickCheckPraktisi").html("Klik Check");
                    // console.log(response.id_ihs);
                    $("#participant_nik").val(response.nik);
                    $("#participant_individual_display").val(response.nama);
                    $("#participant_individual_reference").val(response.id_ihs);
                    $("#participant_individual_reference").addClass(response.color);
                    $("#participant_individual_display").addClass(response.color);
                    $("#participant_nik").addClass(response.color);
                }

            });
        }
        }
</script>
@endpush