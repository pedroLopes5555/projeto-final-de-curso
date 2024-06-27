<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title??'Dasboard'}}</title>
    <!-- favicon icon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/logo.png') }}" />
    <link href=' {{ asset('assets/css/crud.css')}} ' rel='stylesheet' />
    <link rel="stylesheet" href="{{ asset('/lib/select2/select2.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/lib/datatables/datatables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome-all.min.css') }}">
    @yield('head')
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
</head>
<body>
    @yield('body')
</body>
<script src="{{ asset('/lib/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('/lib/datatables/datatables.min.js') }}"></script>
<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="{{ asset('/lib/select2/select2.min.js') }}"></script>
<script src="{{ asset('/assets/js/main.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/chart.min.js') }}"></script>
<script src="{{ asset('assets/js/bs-init.js') }}"></script>
<script src="{{ asset('assets/js/theme.js') }}"></script>

<script> 
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut",
    "positionClass": "toast-bottom-right",
    "css": {
    "background-color": "green !important",
    "background-image": "none !important"
    }
};
</script>
@yield('scripts')
