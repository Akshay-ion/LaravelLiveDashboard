@extends('layout')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="text-center fw-bold">Dashboard</h1>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6 offset-md-3">
            <div class="card text-white bg-primary rounded-4 shadow h-100">
                <div class="card-body text-center">
                    <p class="fs-2 mb-0" id="categoryCount">
                        {{ $categoryCount }}
                    </p>
                    <h5 class="card-title fw-bold">Total Categories</h5>
                </div>
            </div>
        </div>
        <div class="col-md-6 offset-md-3">
            <div class="card text-white bg-success rounded-4 shadow h-100">
                <div class="card-body text-center">
                    <p class="fs-2 mb-0" id="productCount">
                        {{ $productCount }}
                    </p>
                    <h5 class="card-title fw-bold">Total Products</h5>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
