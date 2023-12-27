<div class="nk-header nk-header-fluid is-theme">
    <div class="container-xl wide-xl">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger mr-sm-2 d-lg-none">
                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="headerNav"><em
                        class="icon ni ni-menu"></em></a>
            </div>
            <div class="nk-header-brand">
                <a href="#" class="logo-link">
                    <img class="logo-light logo-img" src="{{ asset('images/logo_sht.png') }}"
                        srcset="./images/logo2x.png 2x" alt="logo">
                    <img class="logo-dark logo-img" src="{{ asset('images/logo_sht_dark.png') }}"
                        srcset="./images/logo-dark2x.png 2x" alt="logo-dark">
                </a>
            </div><!-- .nk-header-brand -->
            <div class="nk-header-menu" data-content="headerNav">
                <div class="nk-header-mobile">
                    <div class="nk-header-brand">
                        <a href="#" class="logo-link">
                            <img class="logo-light logo-img" src="{{ asset('images/logo_sht.png') }}"
                                srcset="./images/logo2x.png 2x" alt="logo">
                            <img class="logo-dark logo-img" src="{{ asset('images/logo_sht_dark.png') }}"
                                srcset="./images/logo-dark2x.png 2x" alt="logo-dark">
                        </a>
                    </div>
                    <div class="nk-menu-trigger mr-n2">
                        <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="headerNav"><em
                                class="icon ni ni-arrow-left"></em></a>
                    </div>
                </div>
                <ul class="nk-menu nk-menu-main ui-s2">
                    <li class="nk-menu-item ">
                        <a href="{{ route('dashboard') }}" class="nk-menu-link ">
                            <span class="nk-menu-text">Dashboard</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-text">Pengaturan</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="{{ route('parameter') }}" class="nk-menu-link"><span
                                        class="nk-menu-text">Parameter</span></a>
                            </li>

                            <li class="nk-menu-item has-sub">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-text">Jobs</span>
                                </a>
                                <ul class="nk-menu-sub">
                                    <li class="nk-menu-item">
                                        <a href="{{ route('jadwal') }}" class="nk-menu-link"><span
                                                class="nk-menu-text">Jadwal</span></a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="{{ route('jobs') }}" class="nk-menu-link"><span
                                                class="nk-menu-text">Queue</span></a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="{{ route('job-logs') }}" class="nk-menu-link"><span
                                                class="nk-menu-text">Job Logs</span></a>
                                    </li>
                                    {{-- <li class="nk-menu-item">
                                        <a href="html/transaction-crypto.html" class="nk-menu-link"><span
                                                class="nk-menu-text">Medication Dispense</span></a>
                                    </li> --}}
                                </ul><!-- .nk-menu-sub -->
                            </li><!-- .nk-menu-item -->
                            <li class="nk-menu-item">
                                <a href="{{ route('sinkronisasi') }}" class="nk-menu-link"><span
                                        class="nk-menu-text">Sinkronisasi</span></a>
                            </li>

                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-text">Master Data</span>
                        </a>
                        <ul class="nk-menu-sub">

                            <li class="nk-menu-item">
                                <a href="{{ route('organisasi') }}" class="nk-menu-link"><span
                                        class="nk-menu-text">Organisasi</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('lokasi') }}" class="nk-menu-link"><span
                                        class="nk-menu-text">Lokasi</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('pasien') }}" class="nk-menu-link"><span
                                        class="nk-menu-text">Pasien</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('praktisi') }}" class="nk-menu-link"><span
                                        class="nk-menu-text">Praktisi</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('kfa') }}" class="nk-menu-link"><span
                                        class="nk-menu-text">KFA</span></a>
                            </li>
                            {{-- <li class="nk-menu-item">
                                <a href="html/apps-chats.html" class="nk-menu-link"><span
                                        class="nk-menu-text">Snomed</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="html/apps-chats.html" class="nk-menu-link"><span
                                        class="nk-menu-text">Loinc</span></a>
                            </li> --}}

                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-text">Resource</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item has-sub">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-text">Resource 1</span>
                                </a>
                                <ul class="nk-menu-sub">
                                    <li class="nk-menu-item">
                                        <a href="{{ route('encounter') }}" class="nk-menu-link"><span
                                                class="nk-menu-text">Encounter</span></a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="{{ route('condition') }}" class="nk-menu-link"><span
                                                class="nk-menu-text">Condition</span></a>
                                    </li>
                                </ul><!-- .nk-menu-sub -->
                            </li><!-- .nk-menu-item -->
                            <li class="nk-menu-item has-sub">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-text">Resource 2</span>
                                </a>
                                <ul class="nk-menu-sub">
                                    <li class="nk-menu-item">
                                        <a href="{{ route('observation') }}" class="nk-menu-link"><span
                                                class="nk-menu-text">Observation</span></a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="{{ route('procedure') }}" class="nk-menu-link"><span
                                                class="nk-menu-text">Procedure</span></a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="{{ route('composition') }}" class="nk-menu-link"><span
                                                class="nk-menu-text">Composition</span></a>
                                    </li>
                                </ul><!-- .nk-menu-sub -->
                            </li><!-- .nk-menu-item -->
                            <li class="nk-menu-item has-sub">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-text">Resource 3</span>
                                </a>
                                <ul class="nk-menu-sub">
                                    <li class="nk-menu-item">
                                        <a href="{{ route('medication') }}" class="nk-menu-link"><span
                                                class="nk-menu-text">Medication</span></a>
                                    </li>
                                    <li class="nk-menu-item">
                                        <a href="{{ route('medication-request') }}" class="nk-menu-link"><span
                                                class="nk-menu-text">Medication Request</span></a>
                                    </li>
                                    {{-- <li class="nk-menu-item">
                                        <a href="html/transaction-crypto.html" class="nk-menu-link"><span
                                                class="nk-menu-text">Medication Dispense</span></a>
                                    </li> --}}
                                </ul><!-- .nk-menu-sub -->
                            </li><!-- .nk-menu-item -->
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->

                </ul><!-- .nk-menu -->
            </div><!-- .nk-header-menu -->
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                    <li class="dropdown notification-dropdown">
                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-toggle="dropdown">
                            <div class="icon-status icon-status-info"><em class="icon ni ni-bell"></em>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right dropdown-menu-s1">
                            <div class="dropdown-head">
                                <span class="sub-title nk-dropdown-title">Notifications</span>
                                <a href="#">Mark All as Read</a>
                            </div>
                            <div class="dropdown-body">
                                <div class="nk-notification">
                                    <div class="nk-notification-item dropdown-inner">
                                        <div class="nk-notification-icon">
                                            <em class="icon icon-circle bg-warning-dim ni ni-curve-down-right"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">You have requested to
                                                <span>Widthdrawl</span>
                                            </div>
                                            <div class="nk-notification-time">2 hrs ago</div>
                                        </div>
                                    </div>
                                    <div class="nk-notification-item dropdown-inner">
                                        <div class="nk-notification-icon">
                                            <em class="icon icon-circle bg-success-dim ni ni-curve-down-left"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">Your <span>Deposit
                                                    Order</span> is placed</div>
                                            <div class="nk-notification-time">2 hrs ago</div>
                                        </div>
                                    </div>
                                    <div class="nk-notification-item dropdown-inner">
                                        <div class="nk-notification-icon">
                                            <em class="icon icon-circle bg-warning-dim ni ni-curve-down-right"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">You have requested to
                                                <span>Widthdrawl</span>
                                            </div>
                                            <div class="nk-notification-time">2 hrs ago</div>
                                        </div>
                                    </div>
                                    <div class="nk-notification-item dropdown-inner">
                                        <div class="nk-notification-icon">
                                            <em class="icon icon-circle bg-success-dim ni ni-curve-down-left"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">Your <span>Deposit
                                                    Order</span> is placed</div>
                                            <div class="nk-notification-time">2 hrs ago</div>
                                        </div>
                                    </div>
                                    <div class="nk-notification-item dropdown-inner">
                                        <div class="nk-notification-icon">
                                            <em class="icon icon-circle bg-warning-dim ni ni-curve-down-right"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">You have requested to
                                                <span>Widthdrawl</span>
                                            </div>
                                            <div class="nk-notification-time">2 hrs ago</div>
                                        </div>
                                    </div>
                                    <div class="nk-notification-item dropdown-inner">
                                        <div class="nk-notification-icon">
                                            <em class="icon icon-circle bg-success-dim ni ni-curve-down-left"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">Your <span>Deposit
                                                    Order</span> is placed</div>
                                            <div class="nk-notification-time">2 hrs ago</div>
                                        </div>
                                    </div>
                                </div><!-- .nk-notification -->
                            </div><!-- .nk-dropdown-body -->
                            <div class="dropdown-foot center">
                                <a href="#">View All</a>
                            </div>
                        </div>
                    </li><!-- .dropdown -->
                    <li class="dropdown user-dropdown order-sm-first">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-avatar sm">
                                    <em class="icon ni ni-user-alt"></em>
                                </div>
                                <div class="user-info d-none d-xl-block">
                                    <div class="user-status">{{ Ucwords(Auth::User()->name) }}</div>
                                    <div class="user-name dropdown-indicator">{{ Auth::User()->username }}
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-menu-s1 is-light">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    <div class="user-avatar">
                                        <span>AB</span>
                                    </div>
                                    <div class="user-info">
                                        <span class="lead-text">{{ Ucwords(Auth::User()->name) }}</span>
                                        <span class="sub-text">{{ Auth::User()->username }}</span>
                                    </div>
                                    <div class="user-action">
                                        <a class="btn btn-icon mr-n2" href="html/invest/profile-setting.html"><em
                                                class="icon ni ni-setting"></em></a>
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="{{ route('proses-logout') }}"><em
                                                class="icon ni ni-signout"></em><span>Keluar</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </li><!-- .dropdown -->
                </ul><!-- .nk-quick-nav -->
            </div><!-- .nk-header-tools -->
        </div><!-- .nk-header-wrap -->
    </div><!-- .container-fliud -->
</div>