@extends('layouts.admin')
@section('title', 'Kikos - Dashboard')
@push('css')
    <style>
        .special-date {
            background-color: #021906 !important;
            /* Set your desired background color */
        }
        .tab-content {
            display: none;
            padding: 40px; /* Adjust padding value as needed */
        }

        .tab-content.active {
            display: block;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/home.css') }}">
    <!-- CSS for full calender -->
    <link rel="stylesheet" href="{{ assets('assets/admin-css/fullcalendar.min.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.6.0.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.1/umd/popper.min.js"></script>

    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-plugins/apexcharts/apexcharts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/home.css') }}">

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
                <a href="{{ url('users') }}">
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
                </a>
                <a href="{{ url('manage-booking') }}">
                    <div class="col flex-fill">
                        <div class="overview-card">
                            <div class="overview-card-body">
                                <div class="overview-content">
                                    <div class="overview-content-text">
                                        <p>Total Tour booked</p>
                                        @php
                                            $tourBooking_count = \App\Models\TourBooking::where('tour_type', 1)
                                                ->whereIn('status', [0, 1, 2])
                                                ->count();
                                        @endphp
                                        <h2>{{ $tourBooking_count }}</h2>
                                    </div>
                                    <div class="overview-content-icon">
                                        <img src="{{ assets('assets/admin-images/Total-Tour-booked.svg') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{ url('manage-virtual-tour') }}">
                    <div class="col flex-fill">
                        <div class="overview-card">
                            <div class="overview-card-body">
                                <div class="overview-content">
                                    <div class="overview-content-text">
                                        <p>Total Virtual Tour Purchased</p>
                                        @php
                                            $VirtualtourBooking_count = \App\Models\TourBooking::where('tour_type', 2)
                                                ->whereIn('status', [0, 1, 2])
                                                ->count();
                                        @endphp
                                        <h2>{{ $VirtualtourBooking_count }}</h2>
                                    </div>
                                    <div class="overview-content-icon">
                                        <img src="{{ assets('assets/admin-images/Virtual-tour.svg') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{ url('manage-photo-booth') }}">
                    <div class="col flex-fill">
                        <div class="overview-card">
                            <div class="overview-card-body">
                                <div class="overview-content">
                                    <div class="overview-content-text">
                                        <p>Total Photo Booth Purchases</p>
                                        @php
                                            $PhotoBoothBooking_count = \App\Models\BookingPhotoBooth::whereIn('status', [0, 1, 2])->count();
                                        @endphp
                                        <h2>{{ $PhotoBoothBooking_count }}</h2>
                                    </div>
                                    <div class="overview-content-icon">
                                        <img src="{{ assets('assets/admin-images/PhotoBooth.svg') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{ url('taxi-booking-request') }}">
                    <div class="col flex-fill">
                        <div class="overview-card">
                            <div class="overview-card-body">
                                <div class="overview-content">
                                    <div class="overview-content-text">
                                        <p>Total Taxi Booking Request</p>
                                        @php
                                            $taxi_booking_count = \App\Models\TaxiBooking::where('status', 0)->count();
                                        @endphp
                                        <h2>{{ $taxi_booking_count }}</h2>
                                    </div>
                                    <div class="overview-content-icon">
                                        <img src="{{ assets('assets/admin-images/TaxiBooking.svg') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
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
                                                        {{ date('d M, Y, h:i:s a', strtotime($val->booking_date)) ?? '' }}
                                                    </td>
                                                    <td>
                                                        Pending for Approval
                                                    </td>
                                                    @php
                                                    $image = $val->images['attribute_name'] ?? '';
                                                    @endphp
                                                    <td>
                                                        <div class="action-btn-info">
                                                            <a class="dropdown-item view-btn" data-bs-toggle="modal"
                                                                href="#BookingRequest"
                                                                onclick='accept_tour("{{ $val->id }}","{{ $val->booking_id }}","{{ $val->Tour->title }}","{{ $val->booking_date }}","{{ $val->Tour->duration }}","{{$val->total_amount}}","{{$val->transaction_id}}",
                                                                "{{$image}}")'><i
                                                                    class="las la-eye"></i> View</a>
                                                            {{-- <div class="dropdown-menu">
                                                                <a class="dropdown-item view-btn" href="#"><i
                                                                        class="las la-eye"></i> View</a>
                                                            </div> --}}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
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
                            <div id="calendar"></div>
                        </div>
                    </div>
                    <div class="modal fade" id="dateModal" tabindex="-1" role="dialog" aria-labelledby="dateModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="dateModalLabel">Date Information</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p id="dateInfo"></p>
                                </div>
                            </div>
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
                                            <div class="row g-1 ">

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="date" name="" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <a href="#" class="btn-gr">Download Excel</a>
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

    <!-- Manage Dates popup -->
    <div class="modal kik-modal fade" id="eventModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="iot-modal-form">
                        <form id="eventForm">
                            @csrf
                            <h3>Manage Dates</h3>
                            <div class="form-group">
                                <h4>Selected Date</h4>
                                <input type="date" name="start" min="{{ date('Y-m-d') }}" class="form-control"
                                    required>
                            </div>
                            <div class="form-group">
                                <ul class="kik-datesstatus-list">
                                    <li>
                                        <div class="kikradio">
                                            <input type="radio" name="datesstatustype" value="Not Available"
                                                id="Not Available"required>
                                            <label for="Not Available">Not Available</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="kikradio">
                                            <input type="radio" name="datesstatustype" value="Available"
                                                id="Available"required>
                                            <label for="Available">Available</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="kikradio">
                                            <input type="radio" name="datesstatustype"
                                                value="Booked Tour"id="Tour Bookings" required>
                                            <label for="Tour Bookings">Tour Bookings</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="kik-modal-action">
                                <button type="submit" class="yesbtn">Confirm & Save</button>
                                <button class="Cancelbtn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Booking -->
    <div class="modal kik-modal fade" id="BookingRequest" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="iot-modal-form">
                        <h3>Tour Booking Request</h3>
                        @php
                        $image_path = $val->images['attribute_name'] ?? '';
                        @endphp
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="kik-request-item-card">
                            <div class="kik-request-item-card-head">
                                <div class="request-id-text">Booking ID:<span id="Booking_id"></span></div>
                                <div class="request-status-text"><i class="las la-hourglass-start"></i> Pending
                                    for
                                    Approval</div>
                            </div>
                            <div class="kik-request-item-card-body">
                                <div class="request-package-card">
                                    <div class="request-package-card-media">
                                    <img src="">
                                    </div>
                                    <div class="request-package-card-text">
                                        <h2 id="title"></h2>
                                        <p><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                viewBox="0 0 18 18" fill="none">
                                                <path
                                                    d="M6 4.3125C5.6925 4.3125 5.4375 4.0575 5.4375 3.75V1.5C5.4375 1.1925 5.6925 0.9375 6 0.9375C6.3075 0.9375 6.5625 1.1925 6.5625 1.5V3.75C6.5625 4.0575 6.3075 4.3125 6 4.3125Z"
                                                    fill="#3DA1E3" />
                                                <path
                                                    d="M12 4.3125C11.6925 4.3125 11.4375 4.0575 11.4375 3.75V1.5C11.4375 1.1925 11.6925 0.9375 12 0.9375C12.3075 0.9375 12.5625 1.1925 12.5625 1.5V3.75C12.5625 4.0575 12.3075 4.3125 12 4.3125Z"
                                                    fill="#3DA1E3" />
                                                <path
                                                    d="M6.375 10.875C6.2775 10.875 6.18 10.8525 6.09 10.815C5.9925 10.7775 5.9175 10.725 5.8425 10.6575C5.7075 10.515 5.625 10.3275 5.625 10.125C5.625 10.0275 5.6475 9.92999 5.685 9.83999C5.7225 9.74999 5.775 9.6675 5.8425 9.5925C5.9175 9.525 5.9925 9.47248 6.09 9.43498C6.36 9.32248 6.6975 9.3825 6.9075 9.5925C7.0425 9.735 7.125 9.92999 7.125 10.125C7.125 10.17 7.1175 10.2225 7.11 10.275C7.1025 10.32 7.0875 10.365 7.065 10.41C7.05 10.455 7.0275 10.5 6.9975 10.545C6.975 10.5825 6.9375 10.62 6.9075 10.6575C6.765 10.7925 6.57 10.875 6.375 10.875Z"
                                                    fill="#3DA1E3" />
                                                <path
                                                    d="M9 10.8749C8.9025 10.8749 8.805 10.8524 8.715 10.8149C8.6175 10.7774 8.5425 10.7249 8.4675 10.6574C8.3325 10.5149 8.25 10.3274 8.25 10.1249C8.25 10.0274 8.2725 9.9299 8.31 9.8399C8.3475 9.7499 8.4 9.66741 8.4675 9.59241C8.5425 9.52491 8.6175 9.47239 8.715 9.43489C8.985 9.31489 9.3225 9.38241 9.5325 9.59241C9.6675 9.73491 9.75 9.9299 9.75 10.1249C9.75 10.1699 9.7425 10.2224 9.735 10.2749C9.7275 10.3199 9.7125 10.3649 9.69 10.4099C9.675 10.4549 9.6525 10.4999 9.6225 10.5449C9.6 10.5824 9.5625 10.6199 9.5325 10.6574C9.39 10.7924 9.195 10.8749 9 10.8749Z"
                                                    fill="#3DA1E3" />
                                                <path
                                                    d="M11.625 10.8749C11.5275 10.8749 11.43 10.8524 11.34 10.8149C11.2425 10.7774 11.1675 10.7249 11.0925 10.6574C11.0625 10.6199 11.0325 10.5824 11.0025 10.5449C10.9725 10.4999 10.95 10.4549 10.935 10.4099C10.9125 10.3649 10.8975 10.3199 10.89 10.2749C10.8825 10.2224 10.875 10.1699 10.875 10.1249C10.875 9.9299 10.9575 9.73491 11.0925 9.59241C11.1675 9.52491 11.2425 9.47239 11.34 9.43489C11.6175 9.31489 11.9475 9.38241 12.1575 9.59241C12.2925 9.73491 12.375 9.9299 12.375 10.1249C12.375 10.1699 12.3675 10.2224 12.36 10.2749C12.3525 10.3199 12.3375 10.3649 12.315 10.4099C12.3 10.4549 12.2775 10.4999 12.2475 10.5449C12.225 10.5824 12.1875 10.6199 12.1575 10.6574C12.015 10.7924 11.82 10.8749 11.625 10.8749Z"
                                                    fill="#3DA1E3" />
                                                <path
                                                    d="M6.375 13.4999C6.2775 13.4999 6.18 13.4774 6.09 13.4399C6 13.4024 5.9175 13.3499 5.8425 13.2824C5.7075 13.1399 5.625 12.9449 5.625 12.7499C5.625 12.6524 5.6475 12.5549 5.685 12.4649C5.7225 12.3674 5.775 12.2849 5.8425 12.2174C6.12 11.9399 6.63 11.9399 6.9075 12.2174C7.0425 12.3599 7.125 12.5549 7.125 12.7499C7.125 12.9449 7.0425 13.1399 6.9075 13.2824C6.765 13.4174 6.57 13.4999 6.375 13.4999Z"
                                                    fill="#3DA1E3" />
                                                <path
                                                    d="M9 13.4999C8.805 13.4999 8.61 13.4174 8.4675 13.2824C8.3325 13.1399 8.25 12.9449 8.25 12.7499C8.25 12.6524 8.2725 12.5549 8.31 12.4649C8.3475 12.3674 8.4 12.2849 8.4675 12.2174C8.745 11.9399 9.255 11.9399 9.5325 12.2174C9.6 12.2849 9.6525 12.3674 9.69 12.4649C9.7275 12.5549 9.75 12.6524 9.75 12.7499C9.75 12.9449 9.6675 13.1399 9.5325 13.2824C9.39 13.4174 9.195 13.4999 9 13.4999Z"
                                                    fill="#3DA1E3" />
                                                <path
                                                    d="M11.625 13.4999C11.43 13.4999 11.235 13.4174 11.0925 13.2824C11.025 13.2149 10.9725 13.1324 10.935 13.0349C10.8975 12.9449 10.875 12.8474 10.875 12.7499C10.875 12.6524 10.8975 12.5549 10.935 12.4649C10.9725 12.3674 11.025 12.2849 11.0925 12.2174C11.265 12.0449 11.5275 11.9624 11.7675 12.0149C11.82 12.0224 11.865 12.0374 11.91 12.0599C11.955 12.0749 12 12.0974 12.045 12.1274C12.0825 12.1499 12.12 12.1874 12.1575 12.2174C12.2925 12.3599 12.375 12.5549 12.375 12.7499C12.375 12.9449 12.2925 13.1399 12.1575 13.2824C12.015 13.4174 11.82 13.4999 11.625 13.4999Z"
                                                    fill="#3DA1E3" />
                                                <path
                                                    d="M15.375 7.37988H2.625C2.3175 7.37988 2.0625 7.12488 2.0625 6.81738C2.0625 6.50988 2.3175 6.25488 2.625 6.25488H15.375C15.6825 6.25488 15.9375 6.50988 15.9375 6.81738C15.9375 7.12488 15.6825 7.37988 15.375 7.37988Z"
                                                    fill="#3DA1E3" />
                                                <path
                                                    d="M12 17.0625H6C3.2625 17.0625 1.6875 15.4875 1.6875 12.75V6.375C1.6875 3.6375 3.2625 2.0625 6 2.0625H12C14.7375 2.0625 16.3125 3.6375 16.3125 6.375V12.75C16.3125 15.4875 14.7375 17.0625 12 17.0625ZM6 3.1875C3.855 3.1875 2.8125 4.23 2.8125 6.375V12.75C2.8125 14.895 3.855 15.9375 6 15.9375H12C14.145 15.9375 15.1875 14.895 15.1875 12.75V6.375C15.1875 4.23 14.145 3.1875 12 3.1875H6Z"
                                                    fill="#3DA1E3" />
                                            </svg>
                                            <span id="booking_date">
                                            </span>
                                        </p>
                                        <div class="duration-time-text">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="19"
                                                viewBox="0 0 18 19" fill="none">
                                                <path
                                                    d="M9 17.5625C4.5525 17.5625 0.9375 13.9475 0.9375 9.5C0.9375 5.0525 4.5525 1.4375 9 1.4375C13.4475 1.4375 17.0625 5.0525 17.0625 9.5C17.0625 13.9475 13.4475 17.5625 9 17.5625ZM9 2.5625C5.175 2.5625 2.0625 5.675 2.0625 9.5C2.0625 13.325 5.175 16.4375 9 16.4375C12.825 16.4375 15.9375 13.325 15.9375 9.5C15.9375 5.675 12.825 2.5625 9 2.5625Z"
                                                    fill="#3DA1E3" />
                                                <path
                                                    d="M11.7825 12.4476C11.685 12.4476 11.5875 12.4251 11.4975 12.3651L9.17248 10.9776C8.59498 10.6326 8.16748 9.87507 8.16748 9.20757V6.13257C8.16748 5.82507 8.42248 5.57007 8.72998 5.57007C9.03748 5.57007 9.29248 5.82507 9.29248 6.13257V9.20757C9.29248 9.47757 9.51748 9.87507 9.74998 10.0101L12.075 11.3976C12.345 11.5551 12.4275 11.9001 12.27 12.1701C12.1575 12.3501 11.97 12.4476 11.7825 12.4476Z"
                                                    fill="#3DA1E3" />
                                            </svg>
                                            <span id="duration">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="request-point-section">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="request-point-item">
                                                <h3>Transaction ID</h3>
                                                <h4 id="transaction_id"></h4>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="request-point-item">
                                                <h3>Amount Recieved On</h3>
                                                <h4 id="created_date">
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kik-request-item-card-foot">
                                <div class="request-price-text">Amount Paid:<span id="total_amount"></span></div>
                                <div class="request-cancellation-btn">
                                    <input type="hidden" value="" name="tour_id" id="tour_id">
                                    <a class="rejectbtn" id="rejectbtn">Reject</a>
                                    <a class="acceptbtn"id="acceptbtn">Accept</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Code for calendar --}}
    {{-- <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                editable: true,
                eventSources: [
                    '/get-events',
                ],
                eventRender: function(event, element) {
                    // Empty the content before appending new data
                    element.empty();

                    // Check if event.description is defined before appending
                    if (event.description) {
                        // Append the event description
                        element.append("<br/>" + event.description);
                    }
                },
                dayRender: function(date, cell) {
                    // Check if there are events on this date
                    var eventsOnThisDate = $('#calendar').fullCalendar('clientEvents', function(event) {
                        // Check if the start date of the event matches the current date
                        return event.start.isSame(date, 'day');
                    });

                    // If there are events, set the background color
                    if (eventsOnThisDate.length > 0) {
                        cell.css('background-color', 'red'); // Set your desired background color
                    }
                },
                dayClick: function(date, jsEvent, view) {
                    $('#eventModal').modal('show');
                    $('#start_date').val(date.format());
                }
            });

            $('#eventForm').submit(function(event) {
                event.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '/add-event',
                    data: $('#eventForm').serialize(),
                    success: function(response) {
                        // Refresh the FullCalendar to display the new event
                        $('#calendar').fullCalendar('refetchEvents');
                        $('#eventModal').modal('hide');
                        location.reload();
                    }
                });
            });
        });
    </script> --}}
    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                editable: true,
                eventSources: [
                    '/get-events',
                ],
                eventRender: function(event, element) {
                    // Check if event has color defined
                    if (event.color) {
                        // Get the date of the event
                        var eventDate = moment(event.start).format('YYYY-MM-DD');

                        // Find the cell corresponding to the event date
                        var cell = $('.fc-day[data-date="' + eventDate + '"]');

                        // Set the background color of the cell
                        cell.css('background-color', event.color);
                    }

                    // Empty the content before appending new data
                    element.empty();

                    // Check if event.description is defined before appending
                    if (event.description) {
                        // Append the event description
                        element.append("<br/>" + event.description);
                    }
                },
                dayClick: function(date, jsEvent, view) {
                    $('#eventModal').modal('show');
                    $('#start_date').val(date.format());
                }
            });
            $('.fc-header-toolbar .fc-button').filter(function() {
                return $(this).text().trim().length > 0; // Only buttons with text
            }).each(function() {
                 var text = $(this).text();
                $(this).text(text.charAt(0).toUpperCase() + text.slice(1));
             });

            $('#eventForm').submit(function(event) {
                event.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '/add-event',
                    data: $('#eventForm').serialize(),
                    success: function(response) {
                        // Refresh the FullCalendar to display the new event
                        $('#calendar').fullCalendar('refetchEvents');
                        $('#eventModal').modal('hide');
                        location.reload();
                    }
                });
            });
        });
    </script>

    <!-------------------- Append Popup for booking approval using with Jquery -------------------->
    <script>
        function accept_tour(tour_id, booking_id, title, booking_date, duration, total_amount, transaction_id, image) {
            console.log(image);
            // if (image == '') {
            //     imageUrl = 'https://nileprojects.in/roadman/dev/public/assets/admin-images/no-image.png';
            // } else {
            //     imageUrl = 'https://nileprojects.in/roadman/dev/public/upload/store-logo/' + image;
            // }

            var total_amount = '$' + total_amount;
            var currentURL = window.location.href;
            //alert(currentURL);
            //Remove the "manage-booking" part
            var base_url = currentURL.replace('/home', '');

            var reject_url = base_url + '/reject-tour-booking/' + tour_id;
            /*URL for reject booking , append on reject button*/
            var accept_url = base_url + '/accept-tour-booking/' + tour_id + '#BookingAcceptedRequest';
            /*URL for accept booking , append on accept button*/
            var duration = 'Duration: ' + duration + ' Hours';
            // Date formate
            var created_date = formatDate(booking_date);
            var booking_date = 'Selected Date: ' + created_date;

            document.getElementById("title").innerText = title;
            document.getElementById("tour_id").value = tour_id;
            document.getElementById("Booking_id").innerText = booking_id;
            document.getElementById("created_date").innerText = created_date;

            document.getElementById("booking_date").innerText = booking_date;
            document.getElementById("duration").innerText = duration;
            document.getElementById("transaction_id").innerText = transaction_id;
            document.getElementById("total_amount").innerText = total_amount;
            var url = document.getElementById("rejectbtn");
            url.href = reject_url;
            var url_accept = document.getElementById("acceptbtn");
            url_accept.href = accept_url;
            var imgElement = document.querySelector(".request-package-card-media img");
            imgElement.src = "{{ assets('upload/tour-thumbnail') }}" +'/' + image;
            // Update the image source

            // $('.mix-2 img').remove();
            // var imageElement = $('<img>').attr({
            //     'height': '60',
            //     'width': '60',
            //     'src': imageUrl
            // });
            // $('.mix-2').append(imageElement);
        }

        function formatDate(dateString) {
            // Create a Date object from the input string
            var date = new Date(dateString);

            // Options for formatting the date
            var options = {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            };

            // Format the date using the toLocaleDateString method
            var formattedDate = date.toLocaleDateString('en-US', options);

            return formattedDate;
        }
    </script>
@endsection
