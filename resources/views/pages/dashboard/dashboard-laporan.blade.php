<div class="nk-upload-form">
    <h5 class="title mb-3">Laporan</h5>
</div>
<div class="nk-upload-list">
    <form action="{{ route('dashboard-laporan-download') }}" target="_blank" method="GET" id="form-action">
        @csrf
        <div class="row g">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label" for="resource">Resource</label>
                    <div class="form-control-wrap ">
                        <div class="form-control-select">
                            <select class="form-control" name="resource" id="resource" data-search="on" required>
                                <option value="encounter">Encounter</option>
                                <option value="condition">Condition</option>
                                <option value="observation">Observation</option>
                                <option value="medication_request">Medication Request</option>
                                <option value="medication_dispense">Medication Dispense</option>
                                <option value="laboratorium">Service Request - Laboratorium</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <div class="form-group">
                    <label class="form-label" for="status">Status</label>
                    <div class="form-control-wrap ">
                        <div class="form-control-select">
                            <select class="form-control" name="status" id="status" data-search="on" required>

                                <option value="all">Semua</option>
                                <option value="success">Terkirim</option>
                                <option value="failed">Gagal Terkirim</option>
                                <option value="waiting">Belum dikirim</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mt-3">
                <div class="form-group">
                    <label class="form-label" for="tanggal_awal">Tanggal Awal</label>
                    <div class="form-control-wrap">
                        <div class="form-icon form-icon-right">
                            <em class="icon ni ni-calendar-alt"></em>
                        </div>
                        <input type="text" class="form-control form-control-sm form-control-outlined date-picker"
                            id="tanggal_awal" name="tanggal_awal" required>
                        {{-- <label class="form-label-outlined" for="tanggal_awal">Tanggal Awal</label> --}}
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-3">
                <div class="form-group">
                    <label class="form-label" for="tanggal_akhir">Tanggal Awal</label>
                    <div class="form-control-wrap">
                        <div class="form-icon form-icon-right">
                            <em class="icon ni ni-calendar-alt"></em>
                        </div>
                        <input type="text" class="form-control form-control-sm form-control-outlined date-picker"
                            id="tanggal_akhir" name="tanggal_akhir" required>
                        {{-- <label class="form-label-outlined" for="tanggal_akhir">Tanggal Akhir</label> --}}
                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-3">
                <div class="form-group">
                    <button class="btn btn-lg btn-success btn-sm btn-action">Download</button>
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
<script src="{{ asset('assets/js/bundle.js?ver=2.2.0') }}"></script>
<script src="{{ asset('assets/js/scripts.js?ver=2.2.0') }}"></script>
{{-- <script>
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
</script> --}}