<div class="nk-upload-form">
    <h5 class="title mb-3">Tambah Lokasi</h5>
</div>
<div class="nk-upload-list">
    <div class="row g">
        <div class="col-md-12 mb-2">
            <div class="form-group">
                <label class="form-label" for="poli">Poli</label>
                <div class="form-control-wrap ">
                    <div class="form-control-select">
                        <select onClick="return getValuePoli()" class="form-control" id="poli" name="poli" id="poli">
                            @foreach ($data_poli as $item_poli)
                            <option value="{{ $item_poli->original_code }}">{{ $item_poli->name
                                .'-'.$item_poli->original_code }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('lokasi-simpan') }}" method="POST" id="form-action">
        @csrf
        <div class="row g">

            <div class="col-md-4 mb-2">
                <div class="form-group">
                    <label class="form-label" for="original_code">Kode</label>
                    <div class="form-control-wrap">
                        <input name="original_code" type="text" class="form-control form-control-sm" id="original_code"
                            required>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="form-group">
                    <label class="form-label" for="name">Nama</label>
                    <div class="form-control-wrap">
                        <input name="name" type="text" class="form-control form-control-sm" id="name" required>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="form-group">
                    <label class="form-label" for="satusehat_id">Satu Sehat ID</label>
                    <div class="form-control-wrap">
                        <input name="satusehat_id" readonly type="text" class="form-control form-control-sm"
                            id="satusehat_id" required>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="form-group">
                    <label class="form-label" for="physical_type_code">Tipe</label>
                    <div class="form-control-wrap ">
                        <div class="form-control-select">
                            <select class="form-control" id="physical_type_code" name="physical_type_code"
                                id="managing_organization">
                                @foreach ($data_type as $item_type)
                                <option value="{{ $item_type->code }}">{{ $item_type->display }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="form-group">
                    <label class="form-label" for="managing_organization">Bagian</label>
                    <div class="form-control-wrap ">
                        <div class="form-control-select">
                            <select class="form-control" id="managing_organization" name="managing_organization"
                                id="managing_organization">
                                @foreach ($data_bagian as $item_bagian)
                                <option value="{{ $item_bagian->satusehat_id }}">{{ $item_bagian->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="form-group">
                    <label class="form-label" for="satusehat_send">Status</label>
                    <div class="form-control-wrap">
                        <select class="form-control" id="satusehat_send" name="satusehat_send" id="satusehat_send">
                            @foreach ($data_status as $item_status)
                            <option value="{{ $item_status->status }}">{{ $item_status->description }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>


            <div class="col-md-12 mt-3">
                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-primary btn-sm btn-action">Simpan</button>
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

    function getValuePoli(){
        var e = document.getElementById("poli");
        var value = e.value;
        var text = e.options[e.selectedIndex].text;

        poli_split = text.split("-");
        document.getElementById("original_code").value = poli_split[1];
        document.getElementById("name").value = poli_split[0];

    }
</script>