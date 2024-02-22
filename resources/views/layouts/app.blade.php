<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
        content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('images/satusehat_logo.png') }}">
    <!-- Page Title  -->
    <title>Satu sehat - Dashboard</title>
    <!-- StyleSheets  -->
    {{--
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css?ver=2.2.0') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('assets/css/theme.css?ver=2.2.0') }}">

    @stack('style')
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.min.css') }}">
</head>

<body class="nk-body npc-invest bg-lighter ">
    <div class="nk-app-root">
        <!-- wrap @s -->
        <div class="nk-wrap ">
            <!-- main header @s -->
            @include('layouts.header')
            <!-- main header @e -->
            <!-- content @s -->
            <div class="nk-content nk-content-fluid">
                <div class="container-xl wide-xl">
                    <div class="nk-content-inner">
                        <div class="nk-content-body" id='loader'>
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
            <!-- content @e -->
            <!-- footer @s -->
            @include('layouts.footer')

            <!-- footer @e -->
        </div>
        @if (file_exists(public_path('uploads/logo.png')))
        <div class="floating-image-container">
            <img src="{{ asset('uploads/logo.png') }}" alt="Floating Image" class="floating-image">
        </div>
        @endif

        <!-- wrap @e -->
    </div>
    <!-- app-root @e -->
    <!-- JavaScript -->
    <script src="{{ asset('assets/js/bundle.js?ver=2.2.0') }}"></script>
    <script src="{{ asset('assets/js/scripts.js?ver=2.2.0') }}"></script>
    <script src="{{ asset('assets/js/charts/gd-invest.js?ver=2.2.0') }}"></script>
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/jquery-3.7.0.js') }}"></script> --}}
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>

    {{-- <script src="{{ asset('assets/js/jquery-1.9.1.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script> --}}
</body>

</html>
@stack('script')
@stack('script-modal')