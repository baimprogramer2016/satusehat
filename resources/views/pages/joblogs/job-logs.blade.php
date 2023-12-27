@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Job Logs</h3>
            <div class="nk-block-des text-soft">
                <p>Halaman Pengaturan </p>
            </div>
        </div><!-- .nk-block-head-content -->

    </div><!-- .nk-block-between -->
</div><!-- .nk-block-head -->
<div class="nk-block">
    <div class="row g-gs">

        <div class="col-xl-12 col-xxl-8">

            <div class="card card-bordered card-full">
                @if (session('pesan'))
                <x-alert pesan="{{ session('pesan') }}" warna="{{ session('warna','success') }}" />
                @endif
                <div class="card card-preview">
                    <div class="card-inner">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Status</th>
                                    <th>Mulai</th>
                                    <th>Berakhir</th>
                                    <th>Aksi</th>
                                    <th>Pesan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_job_logs as $item_job)
                                <tr>
                                    <td>{{ $item_job->kode }}</td>
                                    <td>
                                        @if($item_job->status == 'Process')
                                        <div class="spinner-border spinner-border-sm text-success" role="status">
                                        </div>
                                        <span class='ml-2 text-success'>{{ $item_job->status }}</span>
                                        @else
                                        <span class='ml-2'>{{ $item_job->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $item_job->start_date }}</td>
                                    <td>{{ $item_job->end_date }}</td>
                                    <td>{{ Ucwords($item_job->action) }}</td>
                                    <td>{{ $item_job->error_message }}</td>

                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div><!-- .card-preview -->
            </div><!-- .card -->
        </div><!-- .col -->
    </div>

</div>

@endsection

@push('script')
<script>
    var table =  $('.data-table').DataTable({
         stateSave: true,

          language : {
                sLengthMenu: "Show _MENU_"
            },
    });
</script>
@endpush