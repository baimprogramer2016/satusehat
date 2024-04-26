<div class="nk-upload-form">
    <h5 class="title mb-3">Atur Bundle</h5>
</div>
<div class="nk-upload-list">

    @foreach ($data_bundle_set as $index => $item_bundle_set)
    <div class="row mb-2">
        <div class="custom-control custom-checkbox my-bundle-set">
            <input type="checkbox" onclick="eventStatus('{{ $item_bundle_set->resource }}','{{ $index+1 }}')"
                id="customCheck{{ $index+1 }}" {{ ($item_bundle_set->status == "1") ? 'checked' : ''
            }}
            class="custom-control-input"
            >
            <label for="customCheck{{ $index+1 }}" class="custom-control-label">{{ ucwords(str_replace('_','
                ',$item_bundle_set->description))
                }}</label>
        </div>
    </div>
    @endforeach

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