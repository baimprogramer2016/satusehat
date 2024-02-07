<div class="nk-upload-form">
    <h5 class="title mb-3">Ubah Snomed</h5>
</div>
<div class="nk-upload-list">

    <form action="{{ route('snomed-update') }}" method="POST" id="form-action">
        @csrf
        <div class="row g">
            <div class="col-md-4">
                <div class="form-group">
                    <input type="hidden" name="id_ubah" value="{{ Crypt::encrypt($data_snomed->id) }}">
                    <label class="form-label" for="snomed_code">Kode</label>
                    <div class="form-control-wrap">
                        <input name="snomed_code" type="text" class="form-control form-control-sm" id="snomed_code"
                            value="{{ $data_snomed->snomed_code }}" required>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="snomed_display">Keterangan</label>
                    <div class="form-control-wrap">
                        <input name="snomed_display" value="{{ $data_snomed->snomed_display }}" type="text"
                            class="form-control form-control-sm" id="snomed_display" required>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label" for="default-06">Resource</label>
                    <div class="form-control-wrap ">
                        <div class="form-control-select">
                            <select class="form-control" id="default-06" name="description" id="description"
                                data-search="on">
                                <option value="">Pilih</option>
                                <option value="{{ $data_snomed->description }}">{{ $data_snomed->description }}
                                </option>
                                @foreach ($data_desc_resource as $item_desc_resource)
                                <option value="{{ $item_desc_resource->description }}">{{
                                    $item_desc_resource->description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-3">
                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-primary btn-sm btn-action">Ubah</button>
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