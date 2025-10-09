<!doctype html>
<html lang="{{App::getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="{{asset('img/favicon.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('img/favicon.png')}}">
    <meta name="keyword" content="{{ setting('meta_keyword') }}">
    <meta name="description" content="{{ setting('meta_description') }}">

    <!-- Shortcut Icon -->
    <link rel="shortcut icon" href="{{asset('img/favicon.png')}}">
    <link rel="icon" type="image/ico" href="{{asset('img/favicon.png')}}" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name') }}</title>

    @stack('before-styles')
   
	  <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" />
	  <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet" />
	  <link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
	  <link href="{{ asset('css/buttons.dataTables.min.css') }}" rel="stylesheet" />
	  <link href="{{ asset('css/select.dataTables.min.css') }}" rel="stylesheet" />
	  <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
	  <link href="{{ asset('css/date-picker.css') }}" rel="stylesheet" />
	  <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />
	  <link href="{{ asset('css/dropzone.min.css') }}" rel="stylesheet" />
	  <link href="{{ asset('css/jquery.smartmarquee.css') }}" rel="stylesheet" />
	  <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
	  <link rel="stylesheet" href="{{ asset('css/jquery.timepicker.min.css') }}">
	  <link rel="stylesheet" href="{{ asset('css/perfect-scrollbar.css') }}">

    <link rel="stylesheet" href="{{ asset('css/backend.css') }}">

    <link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+Bengali+UI&display=swap" rel="stylesheet" />
    <style>body{font-family:Ubuntu,"Noto Sans Bengali UI", Arial, Helvetica, sans-serif}</style>

    @stack('after-styles')

    <x-google-analytics config="{{ setting('google_analytics') }}" />
</head>
<body class="c-app">

    <!-- Sidebar -->
    @include('backend.includes.sidebar')
    <!-- /Sidebar -->

    <div class="c-wrapper">
        <!-- Header Block -->
        @include('backend.includes.header')
        <!-- / Header Block -->

        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid">

                    <div class="animated fadeIn">

                        @include('flash::message')
						@include('sweetalert::alert')
                        <!-- Errors block -->
                        @include('backend.includes.errors')
                        <!-- / Errors block -->

                        <!-- Main content block -->
                        @yield('content')
                        <!-- / Main content block -->

                    </div>
                </div>
            </main>
        </div>

        <!-- Footer block -->
        @include('backend.includes.footer')
        <!-- / Footer block -->
		
        <!-- Scripts -->
        @stack('before-scripts')
     <script src="{{ asset('js/jquery.min.js') }}"></script>
     <script src="{{ asset('js/jquery.js') }}"></script>
        <script src="{{ asset('js/backend.js') }}"></script>
      <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('js/util.js') }}"></script>
      <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
      <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
      <script src="{{ asset('js/custom.js') }}"></script>
       <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
       <!-- <script src="{{ asset('js/jquery.timepicker.min.js') }}"></script> -->
       <script src="{{ asset('js/dataTables.select.min.js') }}"></script>
       <script src="{{ asset('js/highcharts.js') }}"></script>
       <script src="{{ asset('js/perfect-scrollbar.min.js') }}"></script>
       <script src="{{ asset('js/moment.min.js') }}"></script>
       <!-- <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script> -->
       <script src="{{ asset('js/select2.full.min.js') }}"></script>
       <script src="{{ asset('js/dropzone.min.js') }}"></script>
       <script src="{{ asset('js/main.js') }}"></script>
       <script src="{{ asset('js/jquery.smartmarquee.js') }}"></script>
       <script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
      <script  src="{{ asset('js/buttons.flash.min.js') }}"></script>
      <script src="{{ asset('js/jszip.min.js') }}"></script>
      <script src="{{ asset('js/pdfmake.min.js') }}"></script>
      <script src="{{ asset('js/vfs_fonts.js') }}"></script>
      <script src="{{ asset('js/buttons.html5.min.js') }}"></script>
      <script src="{{ asset('js/idle.js') }}"></script>
       <script src="{{ asset('js/jquery-ui.js') }}"></script>
       <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
        @stack('after-scripts')
        <!-- / Scripts -->

    </body>
    </html>
