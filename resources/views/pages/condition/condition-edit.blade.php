@extends('layouts.app')

@section('content')
@push('style')
<link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
@endpush
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Edit Condition</h3>
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
                        <li><a href="{{ route('condition') }}" class=" btn btn-white btn-dim btn-outline-primary"><em
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
                        <form action="{{ route('condition-update', $data_condition->id) }}" method="POST">
                            @csrf
                            <div class="preview-block">
                                <span class="preview-title-lg overline-title">Condition</span>
                                <div class="row gy-4" id="panel_list">

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label" for="encounter_original_code[]">No
                                                Registrasi</label>
                                            <div class="form-control-wrap">

                                                <input type="text"
                                                    class="form-control form-control-sm  @error('encounter_original_code') is-invalid @enderror"
                                                    id="encounter_original_code" name='encounter_original_code'
                                                    placeholder="No Registrasi"
                                                    value="{{ old('encounter_original_code',$data_condition->encounter_original_code) }}" />
                                                @error('encounter_original_code')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="form-group">
                                            <label class="form-label" for="code_icd_display"> ICD 10
                                            </label>
                                            <div class="form-control-wrap">

                                                <input type="text"
                                                    class="form-control form-control-sm autocomplete-input @error('code_icd_display') is-invalid @enderror"
                                                    id="code_icd_display" name='code_icd_display'
                                                    placeholder="Keterangan"
                                                    value="{{ old('code_icd_display',$data_condition->code_icd.' # '.$data_condition->code_icd_display) }}" />
                                                @error('code_icd_display')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="form-label" for="onset_datetime">Tanggal Tindakan</label>
                                            <div class="form-control-wrap">
                                                <div class="form-icon form-icon-right">
                                                    <em class="icon ni ni-calendar-alt"></em>
                                                </div>
                                                <input type="datetime"
                                                    class="form-control  form-control-sm form-control-outlined date-picker @error('onset_datetime') is-invalid @enderror"
                                                    id="onset_datetime" name="onset_datetime"
                                                    value="{{ old('onset_datetime',date(" m/d/Y",
                                                    strtotime($data_condition->onset_datetime))) }}" />
                                                @error('onset_datetime')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label class="form-label" for="onset_datetime_hour[]">Waktu</label>
                                            <div class="form-control-wrap">
                                                <input type="time"
                                                    class="form-control  form-control-sm @error('onset_datetime_hour') is-invalid @enderror"
                                                    value="{{ old('onset_datetime_hour',date('H:i',strtotime($data_condition->onset_datetime)))
                                                }}" id="onset_datetime_hour" name='onset_datetime_hour'
                                                    placeholder="Waktu" />
                                                @error('onset_datetime_hour')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label class="form-label" for="default-06">Rank</label>
                                            <div class="form-control-wrap">
                                                <input type="number"
                                                    class="form-control  form-control-sm @error('rank') is-invalid @enderror"
                                                    id="rank" name='rank' placeholder="rank"
                                                    value="{{ old('rank',$data_condition->rank) }}" readonly />
                                                @error('rank')
                                                <div class="alert alert-sm alert-danger alert-custom">{{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <button type="submit" class="btn btn-sm btn-primary mt-3">Ubah</button>
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
<script src="{{ asset('assets/js/jquery-ui.js') }}"></script>
<script>
    $(document).ready(function(){
    $('.autocomplete-input').each(function() {
        var inputId = $(this).attr('id');

        $(this).autocomplete({
            source: function(request, response) {
                var id = request.term;
                var url     = '{{ route("condition-search-icd-10", ":id") }}';
                url = url.replace(':id',id);

                $.ajax({
                    url: url, // Ganti dengan URL yang sesuai
                    method: "GET",
                    success: function(data) {
                        console.log("dua ",data);
                        response(data);
                    }
                });
            },
            minLength: 2,
            // select: function(event, ui) {
            //     var selectedId = $(this).attr('id');
            //     console.log('Input field dengan ID ' + selectedId + ' dipilih dengan nilai ' + ui.item.value);
            // }
        });
    });
});
</script>
@endpush