@extends('layouts.admin')
@section('title', 'Kikos - Dashboard')
@push('css')
    <style>
        .special-date {
            background-color: #ffcc00 !important;
            /* Set your desired background color */
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/home.css') }}">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.js'></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>

    <script>
        // document.addEventListener('DOMContentLoaded', function() {
        //     var calendarEl = document.getElementById('calendar_id');
        //     var calendar = new FullCalendar.Calendar(calendarEl, {
        //         initialView: 'dayGridMonth'
        //     });
        //     calendar.render();
        //     calendar.on('dateClick', function(info) {
        //         console.log('clicked on ' + info.dateStr);
        //     });
        // });
        document.addEventListener('DOMContentLoaded', function() {

            var bookings = @json($Tourrequests);
            var events = [];

            bookings.data.forEach(element => {
                events.push({
                    title: "Booked",
                    start: element.booking_date,
                    color: "blue"
                });
            });
            var calendarEl = document.getElementById('calendar_id');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: events,

                dateClick: function(info) {
                    var open = true;
                    var date = "{{ date('y-m-d') }}";
                    var status = "Available";

                    var booked = @json($booked_dates);

                    booked.forEach(element => {

                        if (element.split(" ")[0] == info.dateStr) {
                            open = false;
                            date = info.dateStr;
                        }
                    });
                    console.log('clicked on ' + info.dateStr);
                    if (open) {
                        var button = document.getElementById("manage_dates_btn");
                        button.click();

                        // Parse the date string and create a Date object
                        var popup_date = document.getElementById("popup_date");
                        console.log(date);
                        // Set the value of the date inputz
                        popup_date.value = info.dateStr;

                        var radioButtons = document.querySelectorAll('.availabilityRadioButton');

                        // Loop through the radio buttons
                        radioButtons.forEach(function(radioButton) {
                            // Check the radio button with the value "Available"
                            if (radioButton.value === status) {
                                radioButton.checked = true;
                            }
                        });
                    }

                }
            });

            calendar.render();


        });
    </script>
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
                            <div id="calendar_id"></div>
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
    <a data-bs-toggle="modal" data-bs-target="#ManageDates" class="btn-gr" style="opacity: 0.1"
        id="manage_dates_btn">Manage Dates</a>

    <!-- Manage Dates popup -->
    <div class="modal kik-modal fade" id="ManageDates" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="iot-modal-form">
                        <h3>Manage Dates</h3>
                        <form action="{{ route('set-date') }}" method="post">
                            <div class="form-group">
                                <h4>Selected Date</h4>
                                <input type="date" id="popup_date" class="form-control">
                            </div>

                            @csrf
                            <div class="form-group">
                                <ul class="kik-datesstatus-list">
                                    <li>
                                        <div class="kikradio">
                                            <input type="radio" name="datesstatustype" value="Not Available"
                                                class="availabilityRadioButton" id="Not Available">
                                            <label for="Not Available">Not Available</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="kikradio">
                                            <input type="radio" name="datesstatustype" value="Available"
                                                class="availabilityRadioButton" id="Available">
                                            <label for="Available">Available</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="kikradio">
                                            <input type="radio" name="datesstatustype" value="Tour Bookings"
                                                class="availabilityRadioButton" id="Tour Bookings">
                                            <label for="Tour Bookings">Tour Bookings</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="kik-modal-action">
                                <button class="yesbtn">Confirm & Save</button>
                                <button class="Cancelbtn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        $('#exampleModal').on('show.bs.modal', event => {
            var button = $(event.relatedTarget);
            var modal = $(this);
            // Use above variables to manipulate the DOM

        });
    </script>


@endsection
