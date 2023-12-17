<div class="nk-upload-form">
    <h5 class="title mb-3">Encounter Detail </h5>
</div>
<div class="nk-upload-list">
    <div class="card card-preview">
        <div class="card-inner">
            <h5>Condition</h5>
            <table class="table table-bordered data-table-detail mt-3">
                <thead>
                    <tr>
                        <th>ICD</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_condition as $item_condition)
                    <tr>
                        <td>{{ $item_condition->code_icd }}</td>
                        <td>{{ $item_condition->code_icd_display }}</td>
                        @if($item_condition !=null)
                        <td>{!! ($item_condition->satusehat_send ==1) ? config('constan.status.terkirim') :
                            config('constan.status.menunggu') !!}
                        </td>
                        @else
                        <td></td>
                        @endif
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <div class="card-inner">
            <h5>TTV</h5>
            <table class="table table-bordered data-table-detail mt-3">
                <thead>
                    <tr>
                        <th>Tipe</th>
                        <th>Nilai</th>
                        <th>Satuan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_observation as $item_observation)
                    <tr>
                        <td>{{ $item_observation->type_observation }}</td>
                        <td>{{ $item_observation->quantity_value }}</td>
                        <td>{{ $item_observation->quantity_unit }}</td>
                        @if($item_observation !=null)
                        <td>{!! ($item_observation->satusehat_send ==1) ? config('constan.status.terkirim') :
                            config('constan.status.menunggu') !!}
                        </td>
                        @else
                        <td></td>
                        @endif
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <div class="card-inner">
            <h5>Procedure</h5>
            <table class="table table-bordered data-table-detail mt-3">
                <thead>
                    <tr>
                        <th>ICD</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_procedure as $item_procedure)
                    <tr>
                        <td>{{ $item_procedure->code_icd }}</td>
                        <td>{{ $item_procedure->code_icd_display }}</td>
                        @if($item_procedure !=null)
                        <td>{!! ($item_procedure->satusehat_send ==1) ? config('constan.status.terkirim') :
                            config('constan.status.menunggu') !!}
                        </td>
                        @else
                        <td>x</td>
                        @endif
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div><!-- .card-preview -->

</div>
<script>
    $('.data-table-detail').DataTable({
          language : {
                sLengthMenu: "Show _MENU_"
            },
    });
</script>