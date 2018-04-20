<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Area</title>

    <link rel="stylesheet" href="{{URL::to('css/admin.css') }}" type="text/css">
    @yield('styles')
</head>
<body>
@include('includes.admin-header')

    @yield('content')
<script type="text/javascript">
    var baseUrl='{{ URL::to('/') }}';
</script>
<script src="{{URL::to('js/jquery.js') }}"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{URL::to('js/bootstrap.min.js') }}"></script>
@yield('scripts')
</body>
</html>