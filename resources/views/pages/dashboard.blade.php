@extends('layouts.app')

@section('content')
<div class="nk-block-head nk-block-head-sm">
    <div class="nk-block-between">
        <div class="nk-block-head-content">
            <h3 class="nk-block-title page-title">Dashboard</h3>
            <div class="nk-block-des text-soft">
                {{-- <p>Monitor Pengiriman</p> --}}
            </div>
        </div><!-- .nk-block-head-content -->
        <div class="nk-block-head-content">
            <div class="toggle-wrap nk-block-tools-toggle">
                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em
                        class="icon ni ni-more-v"></em></a>
                <div class="toggle-expand-content" data-content="pageMenu">
                    <ul class="nk-block-tools g-3">
                        <li><a href="#" class="btn btn-white btn-dim btn-outline-primary"><em
                                    class="icon ni ni-download-cloud"></em><span>Download</span></a>
                        </li>
                        <li><a href="#" class="btn btn-white btn-dim btn-outline-primary"><em
                                    class="icon ni ni-reports"></em><span>Laporan</span></a>
                        </li>
                        {{-- <li class="nk-block-tools-opt">
                            <div class="drodown">
                                <a href="#" class="dropdown-toggle btn btn-icon btn-primary" data-toggle="dropdown"><em
                                        class="icon ni ni-plus"></em></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="link-list-opt no-bdr">
                                        <li><a href="#"><em class="icon ni ni-user-add-fill"></em><span>Add
                                                    User</span></a></li>
                                        <li><a href="#"><em class="icon ni ni-coin-alt-fill"></em><span>Add
                                                    Order</span></a></li>
                                        <li><a href="#"><em class="icon ni ni-note-add-fill-c"></em><span>Add
                                                    Page</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </li> --}}
                    </ul>
                </div><!-- .toggle-expand-content -->
            </div><!-- .toggle-wrap -->
        </div><!-- .nk-block-head-content -->
    </div><!-- .nk-block-between -->
</div><!-- .nk-block-head -->
<div class="nk-block">
    <div class="row g-gs">
        <div class="col-md-6">
            <div class="card card-bordered card-full">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-0">
                        <div class="card-title">
                            <h6 class="subtitle">Total Tahun ini</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="Total Deposited"></em>
                        </div>
                    </div>
                    <div class="card-amount">
                        <span class="amount"> 49,595.34 <span class="currency currency-usd">Encounter</span>
                        </span>
                        {{-- <span class="change up text-danger"><em class="icon ni ni-arrow-long-up"></em>1.93%</span>
                        --}}
                    </div>
                    <div class="invest-data">
                        <div class="invest-data-amount g-2">
                            <div class="invest-data-history">
                                <div class="title">Berhasil Terkirim</div>
                                <div class="amount">2,940.59 <span class="currency currency-usd"></span></div>
                            </div>
                            <div class="invest-data-history">
                                <div class="title">Gagal Terkirim</div>
                                <div class="amount">1,259.28 <span class="currency currency-usd"></span></div>
                            </div>
                        </div>
                        <div class="invest-data-ck">
                            <canvas class="iv-data-chart" id="totalDeposit"></canvas>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->
        <div class="col-md-6">
            <div class="card card-bordered card-full">
                <div class="card-inner">
                    <div class="card-title-group align-start mb-0">
                        <div class="card-title">
                            <h6 class="subtitle">Total ke seluruhan</h6>
                        </div>
                        <div class="card-tools">
                            <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip" data-placement="left"
                                title="Total Withdraw"></em>
                        </div>
                    </div>
                    <div class="card-amount">
                        <span class="amount"> 49,595.34 <span class="currency currency-usd">Encounter</span>
                        </span>
                        {{-- <span class="change down text-danger"><em
                                class="icon ni ni-arrow-long-down"></em>1.93%</span> --}}
                    </div>
                    <div class="invest-data">
                        <div class="invest-data-amount g-2">
                            <div class="invest-data-history">
                                <div class="title">Berhasil Terkirim</div>
                                <div class="amount">2,940.59 <span class="currency currency-usd"></span></div>
                            </div>
                            <div class="invest-data-history">
                                <div class="title">Gagal Terkirim</div>
                                <div class="amount">1,259.28 <span class="currency currency-usd"></span></div>
                            </div>
                        </div>
                        <div class="invest-data-ck">
                            <canvas class="iv-data-chart" id="totalWithdraw"></canvas>
                        </div>
                    </div>
                </div>
            </div><!-- .card -->
        </div><!-- .col -->


        <div class="col-md-12 col-xxl-12">
            <div class="card card-bordered card-full">
                <div class="card-inner d-flex flex-column h-100">
                    <div class="card-title-group mb-3">
                        <div class="card-title">
                            <h6 class="title">Resource Data</h6>

                        </div>
                        <div class="card-tools mt-n4 mr-n1">
                            <div class="drodown">
                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em
                                        class="icon ni ni-more-h"></em></a>
                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                    <ul class="link-list-opt no-bdr">
                                        <li><a href="#"><span>Minggu ini</span></a></li>
                                        <li><a href="#" class="active"><span>Bulan ini</span></a></li>
                                        <li><a href="#"><span>Tahun ini</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="progress-list gy-3">
                        <div class="progress-wrap">
                            <div class="progress-text">
                                <div class="progress-label">Encounter</div>
                                <div class="progress-amount">58%</div>
                            </div>
                            <div class="progress progress-md">
                                <div class="progress-bar" data-progress="58"></div>
                            </div>
                        </div>
                        <div class="progress-wrap">
                            <div class="progress-text">
                                <div class="progress-label">Condition</div>
                                <div class="progress-amount">18.49%</div>
                            </div>
                            <div class="progress progress-md">
                                <div class="progress-bar bg-orange" data-progress="18.49">
                                </div>
                            </div>
                        </div>
                        <div class="progress-wrap">
                            <div class="progress-text">
                                <div class="progress-label">Observation</div>
                                <div class="progress-amount">16%</div>
                            </div>
                            <div class="progress progress-md">
                                <div class="progress-bar bg-teal" data-progress="16"></div>
                            </div>
                        </div>
                        <div class="progress-wrap">
                            <div class="progress-text">
                                <div class="progress-label">Medication Request</div>
                                <div class="progress-amount">29%</div>
                            </div>
                            <div class="progress progress-md">
                                <div class="progress-bar bg-pink" data-progress="29"></div>
                            </div>
                        </div>
                        <div class="progress-wrap">
                            <div class="progress-text">
                                <div class="progress-label">Medication Dispense</div>
                                <div class="progress-amount">33%</div>
                            </div>
                            <div class="progress progress-md">
                                <div class="progress-bar bg-success" data-progress="33"></div>
                            </div>
                        </div>
                        <div class="progress-wrap">
                            <div class="progress-text">
                                <div class="progress-label">Laboratory</div>
                                <div class="progress-amount">33%</div>
                            </div>
                            <div class="progress progress-md">
                                <div class="progress-bar bg-secondary" data-progress="33"></div>
                            </div>
                        </div>
                        <div class="progress-wrap">
                            <div class="progress-text">
                                <div class="progress-label">Radiology</div>
                                <div class="progress-amount">33%</div>
                            </div>
                            <div class="progress progress-md">
                                <div class="progress-bar bg-warning" data-progress="33"></div>
                            </div>
                        </div>
                        <div class="progress-wrap">
                            <div class="progress-text">
                                <div class="progress-label">Allergy</div>
                                <div class="progress-amount">33%</div>
                            </div>
                            <div class="progress progress-md">
                                <div class="progress-bar bg-danger" data-progress="33"></div>
                            </div>
                        </div>
                    </div>
                    <div class="invest-top-ck mt-auto">
                        <canvas class="iv-plan-purchase" id="planPurchase"></canvas>
                    </div>
                </div>
            </div>
        </div><!-- .col -->

    </div>
</div>
@endsection