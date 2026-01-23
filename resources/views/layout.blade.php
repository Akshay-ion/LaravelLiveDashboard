<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Live Dashboard</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/dataTables.dataTables.min.css')}}">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('category.index') }}">Category</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route(name: 'product.index') }}">Product</a>
                </li>
            </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        @yield('content')
    </div>

<script src="{{ asset('assets/jquery.min.js')}}"></script>
<script src="{{ asset('assets/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('assets/dataTables.min.js')}}"></script>
<script src="{{ asset('assets/sweetalert.min.js')}}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function sweetAlertMessage(type, message, button=false){
        swal({
            title: type.charAt(0).toUpperCase() + type.slice(1),
            text: message,
            icon: type,
            button: button ? "OK" : false,
            timer: button ? null : 2000, 
        });
    }

    function sweetalertDelete(type, message, route){
        swal({
            title: type.charAt(0).toUpperCase() + type.slice(1),
            text: message,
            icon: type,
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                callback();
            }
        });
    }
</script>
@stack('scripts')
</body>
</html>
