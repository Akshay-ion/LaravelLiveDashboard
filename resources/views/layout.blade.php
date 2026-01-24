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
    <link rel="stylesheet" href="{{ asset('assets/sweetalert2.min.css')}}">
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
                    <a class="nav-link {{ request()->routeIs('category.index') ? 'active' : '' }}" aria-current="page" href="{{ route('category.index') }}">Category</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('product.index') ? 'active' : '' }}" href="{{ route(name: 'product.index') }}">Product</a>
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
<script src="{{ asset('assets/sweetalert2.min.js')}}"></script>

<!-- Include CDNs -->
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.0/dist/echo.iife.js"></script>

<script>
    // Make Pusher available to Echo
    window.Pusher = Pusher;

    // Initialize Echo IIFE
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: 'local',              // dummy key for Reverb
        wsHost: window.location.hostname,
        wsPort: 8080,              // your Reverb port
        forceTLS: false,
        encrypted: false,
        disableStats: true,
        cluster: undefined
    });

    // Subscribe to channel & event
    window.Echo.channel('dashboard')
        .listen('.DashboardUpdated', function(e) {
            console.log('LIVE DASHBOARD EVENT', e);
            document.getElementById('categoryCount').textContent = e.categoryCount;
        });
</script>
@stack('scripts')

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function sweetAlertMessage(type, message, button = false) {
        Swal.fire({
            title: type.charAt(0).toUpperCase() + type.slice(1),
            text: message,
            icon: type,
            showConfirmButton: button,
            timer: button ? null : 2000,
            timerProgressBar: !button,
        });
    }

    function sweetAlertDelete(route, dataTable) {
        Swal.fire({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this data!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: route,
                    dataType: "json",
                    success: function (response) {
                        if (response.status === 200) {
                            dataTable.ajax.reload();
                            sweetAlertMessage("success", response.message);
                        } else {
                            sweetAlertMessage("error", response.message, true);
                        }
                    },
                    error: function (xhr, status, error) {
                        sweetAlertMessage(
                            "error",
                            "An error occurred while processing your request.",
                            true
                        );
                        console.error(error);
                    },
                });
            }
        });
    }


</script>
</body>
</html>
