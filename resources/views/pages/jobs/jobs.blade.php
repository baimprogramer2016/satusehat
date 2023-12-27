@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Queue</h3>
            <div class="nk-block-des text-soft">
                <p>Halaman Pengaturan </p>
            </div>
        </div><!-- .nk-block-head-content -->

    </div><!-- .nk-block-between -->
</div><!-- .nk-block-head -->
<div class="nk-block">
    <div class="row g-gs">

        <div class="col-xl-12 col-xxl-12">

            <div class="card card-bordered card-full">
                @if (session('pesan'))
                <x-alert pesan="{{ session('pesan') }}" warna="{{ session('warna','success') }}" />
                @endif
                <div class="card card-preview">
                    <div class="card-inner">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>Queue</th>
                                    <th>Payload</th>
                                    <th>Attemps</th>
                                    <th>Reserved At</th>
                                    <th>Available At</th>
                                    <th>Created At</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_job as $item_job)
                                <tr>
                                    <td>{{ json_decode($item_job->payload)->uuid }}</td>
                                    <td>{{ json_decode($item_job->payload)->displayName }}</td>
                                    <td>{{ $item_job->attemps }}</td>
                                    <td>{{ $item_job->reserved_at }}</td>
                                    <td>{{ $item_job->available_at }}</td>
                                    <td>{{ $item_job->created_at }}</td>

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