<div class="nk-upload-form">
    <h5 class="title mb-3">Kirim ke Satu Sehat</h5>
</div>
<div class="nk-upload-list">
    <div class="row g">
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-label" for="original_code">Keterangan</label>
                <div class="form-control-wrap">
                    <input name="name" readonly value="{{ $data_medication_dispense->encounter_original_code}}"
                        type="text" class="form-control form-control-sm" id="name" required>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-3">
            <div class="form-group">
                <button id='kirim-ss' onclick="kirimSatuSehat('{{ Crypt::encrypt($data_medication_dispense->id) }}')"
                    class="btn btn-lg btn-success btn-sm btn-action">Kirim</button>
                <button class="btn btn-lg btn-danger btn-sm" data-dismiss="modal">Batal</button>
            </div>
        </div>
        <div class="col-md-12 mt-3">
            <label class="form-label" for="original_code">Hasil : <span class="result-message">...</span></label>
            <div class="form-control-wrap">
                <textarea id="response_ss" class="form-control bg-dark text-white" cols="30" rows="10"></textarea>
            </div>

        </div>
    </div>
</div>