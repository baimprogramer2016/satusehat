@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Tambah Pasien</h3>
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
                        <li><a href="{{ route('pasien') }}" class=" btn btn-white btn-dim btn-outline-primary"><em
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
                        <form action="{{ route('pasien-simpan') }}" method="POST">
                            @csrf
                            <div class="preview-block">
                                <span class="preview-title-lg overline-title">Patient</span>
                                <div class="row gy-4">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="form-label" for="original_code">Nama</label>
                                            <div class="form-control-wrap">
                                                <input type="text"
                                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                                    name='name' placeholder="Nama Pasien" value="{{ old('name') }}" />
                                                {{-- @error('original_code')
                                                <div class="alert alert-sm alert-danger">Tidak Boleh Kosong/ Sudah ada
                                                </div>
                                                @enderror --}}

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="form-label" for="original_code">No. KTP Pasien</label>
                                            <div class="form-control-wrap">
                                                <div class="form-text-hint">
                                                    <span class="overline-title p-0"><span class="btn text-success"
                                                            id="clickCheckPasien"
                                                            onclick="return checkIHSPasien('patient')">Klik
                                                            Check</span></span>
                                                </div>
                                                <input type="number"
                                                    class="form-control @error('nik') is-invalid @enderror" id="nik"
                                                    name='nik' placeholder="No. KTP" value="{{ old('nik') }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="form-label" for="satusehat_id"> ID IHS Pasien
                                                (Otomatis)</label>
                                            <div class="form-control-wrap">
                                                <input type="text"
                                                    class="form-control @error('satusehat_id') is-invalid @enderror"
                                                    id="satusehat_id" name='satusehat_id' placeholder="Satu Sehat ID"
                                                    value="{{ old('satusehat_id') }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label class="form-label" for="original_code"> ID Pasien RS
                                            </label>
                                            <div class="form-control-wrap">
                                                <input type="text"
                                                    class="form-control @error('original_code') is-invalid @enderror"
                                                    id="original_code" name='original_code' placeholder="ID Pasien RS"
                                                    value="{{ old('original_code') }}" />
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
        var nik_pasien = $("#nik").val()
        if(nik_pasien == "")
        {
            $("#clickCheckPasien").html("Klik Check");
            alert("Nik Pasien Harus diisi");
        }else{

             $.ajax({
                method: "GET",
                url: "{{ route('pasien-check-nik') }}",
                data: {
                    nik : nik_pasien,
                    pa : param_pa
                },
                success: function(response)
                {
                    $("#clickCheckPasien").html("Klik Check");
                    $("#satusehat_id").val(response.id_ihs);
                    $("#satusehat_id").addClass(response.color);
                }

            });
        }
        }

</script>
@endpush