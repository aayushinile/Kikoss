@extends('layouts.admin')
@section('title', 'Kikos - Dashboard')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-plugins/apexcharts/apexcharts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/home.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/apexcharts/apexcharts.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-js/dashboard-function.js') }}" type="text/javascript"></script>
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>Dashboard</h4>
    </div>
    <div class="body-main-content">
        <div class="overview-section">
            <div class="row row-cols-xl-5 row-cols-xl-3 row-cols-md-2 g-2">
                <div class="col flex-fill">
                    <div class="overview-card">
                        <div class="overview-card-body">
                            <div class="overview-content">
                                <div class="overview-content-text">
                                    <p>Total Users</p>
                                    @php
                                        $users_count = \App\Models\User::where('type', 2)->count();
                                    @endphp
                                    <h2>{{ $users_count }}</h2>
                                </div>
                                <div class="overview-content-icon">
                                    <img src="{{ assets('assets/admin-images/users.svg') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col flex-fill">
                    <div class="overview-card">
                        <div class="overview-card-body">
                            <div class="overview-content">
                                <div class="overview-content-text">
                                    <p>Total Tour booked</p>
                                    @php
                                        $users_count = \App\Models\User::where('type', 2)->count();
                                    @endphp
                                    <h2>0</h2>
                                </div>
                                <div class="overview-content-icon">
                                    <img src="{{ assets('assets/admin-images/Total-Tour-booked.svg') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col flex-fill">
                    <div class="overview-card">
                        <div class="overview-card-body">
                            <div class="overview-content">
                                <div class="overview-content-text">
                                    <p>Total Virtual Tour Purchased</p>
                                    <h2>0</h2>
                                </div>
                                <div class="overview-content-icon">
                                    <img src="{{ assets('assets/admin-images/Virtual-tour.svg') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col flex-fill">
                    <div class="overview-card">
                        <div class="overview-card-body">
                            <div class="overview-content">
                                <div class="overview-content-text">
                                    <p>Total Photo Booth Purchases</p>
                                    <h2>0</h2>
                                </div>
                                <div class="overview-content-icon">
                                    <img src="{{ assets('assets/admin-images/PhotoBooth.svg') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col flex-fill">
                    <div class="overview-card">
                        <div class="overview-card-body">
                            <div class="overview-content">
                                <div class="overview-content-text">
                                    <p>Total Taxi Booking Request</p>
                                    <h2>0</h2>
                                </div>
                                <div class="overview-content-icon">
                                    <img src="{{ assets('assets/admin-images/TaxiBooking.svg') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="booking-availability-section">
            <div class="row">
                <div class="col-md-8">
                    <div class="heading-section">
                        <div class="d-flex align-items-center">
                            <div class="mr-auto">
                                <h4 class="heading-title">Tour Booking Requests</h4>
                            </div>
                            <div class="btn-option-info">
                            </div>
                        </div>
                    </div>

                    <div class="kikcard">
                        <div class="card-body">
                            <div class="kik-table">
                                <table class="table xp-table  " id="customer-table">
                                    <thead>
                                        <tr class="table-hd">
                                            <th>Name</th>
                                            <th>Tour Name</th>
                                            <th>Duration</th>
                                            <th>Tour Book Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($Tourrequests->isEmpty())
                                            <tr>
                                                <td colspan="11" class="text-center">
                                                    No record found
                                                </td>
                                            </tr>
                                        @elseif(!$Tourrequests->isEmpty())
                                            @foreach ($Tourrequests as $val)
                                                <tr>
                                                    <td>
                                                        {{ $val->Users->fullname ?? '' }}
                                                    </td>

                                                    <td>
                                                        {{ $val->Tour->title ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ $val->Tour->duration ?? '' }} Hours
                                                    </td>
                                                    <td>
                                                        {{ date('Y-m-d', strtotime($val->booking_date)) ?? '' }}
                                                    </td>
                                                    <td>
                                                        Pending for Approval
                                                    </td>

                                                    <td>
                                                        <div class="action-btn-info">
                                                            <a class="action-btn dropdown-toggle" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="las la-ellipsis-v"></i>
                                                            </a>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item view-btn" href="#"><i
                                                                        class="las la-eye"></i> View</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="kik-table-pagination">
                                <ul class="kik-pagination">
                                    <li class="disabled" id="example_previous">
                                        <a href="#" aria-controls="example" data-dt-idx="0" tabindex="0"
                                            class="page-link">Previous</a>
                                    </li>
                                    <li class="active">
                                        <a href="#" class="page-link">1</a>
                                    </li>
                                    <li class="">
                                        <a href="#" aria-controls="example" data-dt-idx="2" tabindex="0"
                                            class="page-link">2</a>
                                    </li>
                                    <li class="">
                                        <a href="#" aria-controls="example" data-dt-idx="3" tabindex="0"
                                            class="page-link">3</a>
                                    </li>
                                    <li class="next" id="example_next">
                                        <a href="#" aria-controls="example" data-dt-idx="7" tabindex="0"
                                            class="page-link">Next</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="heading-section">
                        <div class="d-flex align-items-center">
                            <div class="mr-auto">
                                <h4 class="heading-title">Tours Availability Calendar</h4>
                            </div>
                            <div class="btn-option-info">
                            </div>
                        </div>
                    </div>

                    <div class="kikcard">
                        <div class="card-body">

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="booking-availability-section">
            <div class="row">
                <div class="col-md-12">
                    <div class="kikcard">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div class="mr-auto">
                                    <!-- <h4 class="heading-title">User</h4> -->
                                </div>
                                <div class="btn-option-info wd9">
                                    <div class="search-filter">
                                        <div class="search-filter">
                                            <div class="row g-1">

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="date" name="" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <a href="#" class="btn-gr">Download report</a>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <a href="#" class="btn-bl">Tour Booking</a>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <a href="#" class="btn-bla">Virtual Tour</a>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <a href="#" class="btn-br">Photo Booth</a>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <a href="#" class="btn-gra">Taxi Booking</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="kik-chart">
                                <div id="chartBar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
