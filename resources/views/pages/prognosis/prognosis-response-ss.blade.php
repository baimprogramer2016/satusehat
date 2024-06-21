<div class="nk-upload-form">
    <h5 class="title mb-3">Lihat Response</h5>
</div>
<div class="nk-upload-list p-0">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <textarea id="myTextArea" class="form-control bg-dark text-white" cols="30"
                rows="10">{{ $data_response }}</textarea>
        </div>
        <div class="col-md-12 mt-3">
            <div class="form-group">
                <button data-dismiss="modal" class="btn btn-lg btn-secondary btn-sm btn-action">Keluar</button>
            </div>
        </div>
    </div>

</div>
{{-- <div class="nk-modal-action justify-end">
    <ul class="btn-toolbar g-4 align-center">
        <li><button data-dismiss="modal" class="link link-primary">Cancel</button></li>
        <li><button class="btn btn-primary">Add Files</button></li>
    </ul>
</div> --}}


<script>
    var ugly = document.getElementById('myTextArea').value;
    var obj = JSON.parse(ugly);
    var pretty = JSON.stringify(obj, undefined, 4);
    document.getElementById('myTextArea').value = pretty;
</script>