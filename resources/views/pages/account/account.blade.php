@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Akun</h3>
            <div class="nk-block-des text-soft">
                <p>Halaman Pengaturan </p>
            </div>
        </div><!-- .nk-block-head-content -->

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

                        <div class="nk-upload-list">
                            <form action="{{ route('akun-simpan') }}" method="POST" id="form-action">
                                @csrf
                                <div class="col-12 g">
                                    <div class="col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <label class="form-label" for="username">Username</label>
                                            <div class="form-control-wrap">
                                                <input name="username" readonly value="{{ $data_account->username }}"
                                                    type="text" class="form-control form-control-sm" id="username"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <label class="form-label" for="name">Nama</label>
                                            <div class="form-control-wrap">
                                                <input name="name" value="{{ $data_account->name }}" type="text"
                                                    class="form-control form-control-sm" id="name" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <label class="form-label" for="email">Email</label>
                                            <div class="form-control-wrap">
                                                <input name="email" value="{{ $data_account->email }}" type="text"
                                                    class="form-control form-control-sm" id="email" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <label class="form-label" for="pass_lama">Password Lama</label> <span
                                                class='text-danger'>* Kosongkan jika tidak dirubah</span>
                                            <div class="form-control-wrap">
                                                <input name="pass_lama" type="password"
                                                    value="@error('pass_value') {{ $message }} @enderror"
                                                    class="form-control form-control-sm @error('pass_lama') is-invalid @enderror"
                                                    id="pass_lama">
                                                @error('pass_lama')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 ">
                                        <div class="form-group">
                                            <label class="form-label" for="pass_baru">Password Baru</label> <span
                                                class='text-danger'>* Kosongkan jika tidak dirubah</span>
                                            <div class="form-control-wrap">
                                                <input name="pass_baru" value="" type="password"
                                                    class="form-control form-control-sm" id="pass_baru">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <div class="form-group">
                                            <button type="submit"
                                                class="btn btn-lg btn-primary btn-sm btn-action">Ubah</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{-- <div class="nk-modal-action justify-end">
                            <ul class="btn-toolbar g-4 align-center">
                                <li><button data-dismiss="modal" class="link link-primary">Cancel</button></li>
                                <li><button class="btn btn-primary">Add Files</button></li>
                            </ul>
                        </div> --}}


                    </div>
                </div><!-- .card-preview -->
            </div><!-- .card -->
        </div><!-- .col -->
    </div>

</div>
@push('script')


<script>
    $(document).ready(function() {

        $('.btn-action').on("click", function(e) {

            e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Anda akan Mengubah Data?",
                    showCancelButton: true,
                    confirmButtonColor: "#2c3782",
                    confirmButtonText: 'Lanjut',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.value) {
                        $('form#form-action').submit();
                    }
                });

        });
    });

</script>
@endpush
@endsection