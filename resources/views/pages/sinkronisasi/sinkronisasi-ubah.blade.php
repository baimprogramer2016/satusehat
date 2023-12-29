<div class="nk-upload-form">
    <h5 class="title mb-3">Ubah Sinkronisasi</h5>
</div>
<div class="nk-upload-list">

    <form action="{{ route('sinkronisasi-update') }}" method="POST" id="form-action">
        @csrf
        <div class="row g">
            <div class="col-md-3">
                <div class="form-group">
                    <input name="command" type="hidden" value="{{ config('constan.sinkronisasi.run') }}"
                        class="form-control form-control-sm" id="kode">
                    <input type="hidden" name="id_ubah" value="{{ Crypt::encrypt($data_sinkronisasi->id) }}">
                    <label class="form-label" for="kode">Kode</label>
                    <div class="form-control-wrap">
                        <input name="kode" value='{{ $data_sinkronisasi->kode }}' type="text"
                            class="form-control form-control-sm" id="kode" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="description">Deskripsi</label>
                    <div class="form-control-wrap">
                        <input name="description" value='{{ $data_sinkronisasi->description }}' type="text"
                            class="form-control form-control-sm" id="description" required>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label" for="odbc">Koneksi</label>
                    <div class="form-control-wrap">
                        <input name="odbc" type="text" value='{{ $data_sinkronisasi->odbc }}'
                            class="form-control form-control-sm" id="odbc" required>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label" for="target">Target</label>
                    <div class="form-control-wrap">
                        <input name="target" type="text" value='{{ $data_sinkronisasi->target }}'
                            class="form-control form-control-sm" id="target" required>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label" for="record">Jumlah</label>
                    <div class="form-control-wrap">
                        <input name="record" readonly value='{{ $data_sinkronisasi->record }}' type="text"
                            class="form-control form-control-sm" id="record" required>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label" for="part">Part</label>
                    <div class="form-control-wrap">
                        <input name="part" readonly value='{{ $data_sinkronisasi->part }}' type="text"
                            class="form-control form-control-sm" id="part" required>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label" for="page">Page</label>
                    <div class="form-control-wrap">
                        <input name="page" readonly value='{{ $data_sinkronisasi->page }}' type="text"
                            class="form-control form-control-sm" id="page" required>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label" for="upload">Sukses</label>
                    <div class="form-control-wrap">
                        <input name="upload" readonly value='{{ $data_sinkronisasi->upload }}' type="text"
                            class="form-control form-control-sm" id="upload" required>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label" for="prefix">Prefix</label>
                    <div class="form-control-wrap">
                        <input name="prefix" type="text" value='{{ $data_sinkronisasi->prefix }}'
                            class="form-control form-control-sm" id="prefix" required>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label" for="cron">Jadwal</label>
                    <div class="form-control-wrap">
                        <input name="cron" type="text" value='{{ $data_sinkronisasi->cron }}'
                            class="form-control form-control-sm" id="cron" required>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label" for="process">Proses</label>
                    <div class="form-control-wrap">
                        <input name="process" readonly value='{{ $data_sinkronisasi->process }}' type="text"
                            class="form-control form-control-sm" id="process" required>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label" for="sp">SP</label>
                    <div class="form-control-wrap">
                        <input name="sp" type="text" value='{{ $data_sinkronisasi->sp }}'
                            class="form-control form-control-sm" id="sp" required>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label" for="last_process">Terakhir Berjalan</label>
                    <div class="form-control-wrap">
                        <input name="last_process" value='{{ $data_sinkronisasi->last_process }}' readonly type="text"
                            class="form-control form-control-sm" id="last_process" required>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-2">
                <div class="form-group">

                    <div class="custom-control custom-control-sm custom-checkbox mt-3">
                        <input name="status" type="checkbox" {{ ($data_sinkronisasi->status==1) ? 'checked':''}}
                        class="custom-control-input"
                        id="status">
                        <label class="custom-control-label" for="status">Jadwal Aktif / Tidak Aktif</label>
                    </div>
                    <div class="custom-control custom-control-sm custom-checkbox mt-3 ml-3">
                        <input name="tr_table" type="checkbox" {{ ($data_sinkronisasi->tr_table==1) ? 'checked':''}}
                        class="custom-control-input"
                        id="tr_table">
                        <label class="custom-control-label" for="tr_table">Kosongkan Target</label>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                    <label class="form-label" for="query">Query</label>
                    <div class="form-control-wrap">
                        <div class="form-control-wrap">
                            <textarea name="query" class="bg-secondary text-white form-control form-control-sm"
                                id="query" placeholder="Masukan Query">{{ $data_sinkronisasi->query }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="form-group">
                    <span onclick="return modalExecQuery()" class="btn btn-lg btn-success btn-sm">Tes
                        Query</span>
                    <button type="submit" class="btn btn-lg btn-primary btn-sm btn-action">Ubah</button>
                </div>
            </div>
            <div class="col-sm-12 mt-3">
                <p>Result :</p>
                <div class="result-table overflow-auto">

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