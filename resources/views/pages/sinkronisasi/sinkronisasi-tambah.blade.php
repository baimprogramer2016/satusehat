<div class="nk-upload-form">
    <h5 class="title mb-3">Tambah Sinkronisasi</h5>
</div>
<div class="nk-upload-list">
    <form action="{{ route('sinkronisasi-simpan') }}" method="POST" id="form-action">
        @csrf
        <div class="row g">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label" for="kode">Kode</label>
                    <div class="form-control-wrap">
                        <input name="kode" type="text" class="form-control form-control-sm" id="kode" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="description">Deskripsi</label>
                    <div class="form-control-wrap">
                        <input name="description" type="text" class="form-control form-control-sm" id="description"
                            required>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label" for="odbc">Odbc</label>
                    <div class="form-control-wrap">
                        <input name="odbc" type="text" class="form-control form-control-sm" id="odbc" required>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label" for="target">Target</label>
                    <div class="form-control-wrap">
                        <input name="target" type="text" class="form-control form-control-sm" id="target" required>
                    </div>
                </div>
            </div>

            {{-- <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label" for="active">Aktif</label>
                    <div class="form-control-wrap">
                        <select class="form-control form-control-sm" id="active" name="active" id="active">
                            <option value="0">Tidak Aktif</option>
                            <option value="1">Aktif</option>
                        </select>
                    </div>
                </div>
            </div> --}}
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label" for="record">Jumlah</label>
                    <div class="form-control-wrap">
                        <input name="record" readonly value="0" type="text" class="form-control form-control-sm"
                            id="record" required>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label" for="part">Part</label>
                    <div class="form-control-wrap">
                        <input name="part" readonly value="0" type="text" class="form-control form-control-sm" id="part"
                            required>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label" for="page">Page</label>
                    <div class="form-control-wrap">
                        <input name="page" readonly value="0" type="text" class="form-control form-control-sm" id="page"
                            required>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label" for="upload">Sukses</label>
                    <div class="form-control-wrap">
                        <input name="upload" readonly value="0" type="text" class="form-control form-control-sm"
                            id="upload" required>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label" for="prefix">Prefix</label>
                    <div class="form-control-wrap">
                        <input name="prefix" type="text" class="form-control form-control-sm" id="prefix" required>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label" for="schedule">Jadwal</label>
                    <div class="form-control-wrap">
                        <input name="schedule" type="text" class="form-control form-control-sm" id="schedule" required>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label" for="status">Status</label>
                    <div class="form-control-wrap">
                        <input name="status" readonly value="" type="text" class="form-control form-control-sm"
                            id="status" required>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label" for="sp">SP</label>
                    <div class="form-control-wrap">
                        <input name="sp" type="text" class="form-control form-control-sm" id="sp" required>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label" for="last_process">Terakhir Berjalan</label>
                    <div class="form-control-wrap">
                        <input name="last_process" value="" readonly type="text" class="form-control form-control-sm"
                            id="last_process" required>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="form-label" for="query">Query</label>
                    <div class="form-control-wrap">
                        <div class="form-control-wrap">
                            <textarea name="query" class="form-control form-control-sm" id="query"
                                placeholder="Masukan Query"></textarea>
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-md-12 mt-3">
                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-success btn-sm btn-action">Hasil Query</button>
                    <button type="submit" class="btn btn-lg btn-primary btn-sm btn-action">Simpan</button>
                </div>
            </div>
            <div class="col-sm-12 mt-3">
                <p>Result :</p>
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
                    text: "Anda akan Menambahkan Data?",
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