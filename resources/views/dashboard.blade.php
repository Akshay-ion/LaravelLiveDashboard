@extends('layout')

@section('content')
<div class="container py-5">
    <!-- Dashboard Heading -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="text-center fw-bold">Dashboard</h1>
        </div>
    </div>

    <!-- Cards Row -->
    <div class="row g-4">
        <!-- Total Categories Card -->
        <div class="col-md-6">
            <div class="card text-white bg-primary rounded-4 shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-folder-fill fs-1 opacity-75 mb-2"></i>
                    <p class="fs-2 mb-0">{{ $categoryCount }}</p>
                    <h5 class="card-title fw-bold">Total Categories</h5>
                </div>
            </div>
        </div>

        <!-- Total Products Card -->
        <div class="col-md-6">
            <div class="card text-white bg-success rounded-4 shadow h-100">
                <div class="card-body text-center">
                    <i class="bi bi-box-seam fs-1 opacity-75 mb-2"></i>
                    <p class="fs-2 mb-0">{{ $productCount }}</p>
                    <h5 class="card-title fw-bold">Total Products</h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
