@extends('layouts.admin')
@section('title', 'Kikos - Manage Photo-Booth')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/taxi-booking-requests.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>Taxi Booking requests</h4>
    </div>
    <div class="body-main-content">

        <div class="booking-availability-section">
            <div class="row">
                <div class="col-md-12">
                    <div class="kikcard">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div class="mr-auto">
                                    <h4 class="heading-title">Booking Requests</h4>
                                </div>
                                <div class="btn-option-info wd7">
                                    <div class="search-filter">
                                        <div class="search-filter">
                                            <div class="row g-1">

                                                <div class="col-md-3">
                                                    <div class="search-form-group">
                                                        <div class="TotalRequestoverview">Total Request Received:
                                                            <span>5689</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="search-form-group">
                                                        <input type="text" name="" class="form-control"
                                                            placeholder="Search User name, Amount & virtual tour name..">
                                                        <span class="search-icon"><img
                                                                src="{{ assets('assets/admin-images/search-icon.svg') }}"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="date" name="" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <a href="#" class="btn-gr">Download Data</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="kik-table">
                                <table class="table xp-table  " id="customer-table">
                                    <thead>
                                        <tr class="table-hd">
                                            <th>Sr No.</th>
                                            <th>Name</th>
                                            <th>Booking ID</th>
                                            <th>Booking Date & Time</th>
                                            <th>Pickup Location</th>
                                            <th>Drop Off Location</th>
                                            <th>Travel Distanse </th>
                                            <th>Hotel Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="sno">1</div>
                                            </td>
                                            <td>Jane Doe</td>
                                            <td>TR0619879238351</td>
                                            <td>03 Sep, 2023, 09:33:12 am</td>
                                            <td>8642 Yule Street, Arvada CO 80007</td>
                                            <td>597 East Miracle Drive, Fayetteville AR 72701</td>
                                            <td>05 KM</td>
                                            <td> Big Bang Hotel </td>
                                            <td>
                                                <div class="action-btn-info">
                                                    <a class="action-btn dropdown-toggle" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="las la-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item view-btn" href="users-detail.html"><i
                                                                class="las la-eye"></i> View</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="sno">2</div>
                                            </td>
                                            <td>Jane Doe</td>
                                            <td>TR0619879238351</td>
                                            <td>03 Sep, 2023, 09:33:12 am</td>
                                            <td>8642 Yule Street, Arvada CO 80007</td>
                                            <td>597 East Miracle Drive, Fayetteville AR 72701</td>
                                            <td>05 KM</td>
                                            <td> Big Bang Hotel </td>
                                            <td>
                                                <div class="action-btn-info">
                                                    <a class="action-btn dropdown-toggle" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="las la-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item view-btn" href="users-detail.html"><i
                                                                class="las la-eye"></i> View</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td>
                                                <div class="sno">3</div>
                                            </td>
                                            <td>Jane Doe</td>
                                            <td>TR0619879238351</td>
                                            <td>03 Sep, 2023, 09:33:12 am</td>
                                            <td>8642 Yule Street, Arvada CO 80007</td>
                                            <td>597 East Miracle Drive, Fayetteville AR 72701</td>
                                            <td>05 KM</td>
                                            <td> Big Bang Hotel </td>
                                            <td>
                                                <div class="action-btn-info">
                                                    <a class="action-btn dropdown-toggle" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="las la-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item view-btn" href="users-detail.html"><i
                                                                class="las la-eye"></i> View</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

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
            </div>
        </div>

    </div>
@endsection
