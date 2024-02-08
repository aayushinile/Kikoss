@extends('layouts.admin')
@section('title', 'Kikos - Manage Photo-Booth')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/taxi-booking-requests.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- CSS for full calender -->
    <link rel="stylesheet" href="{{ assets('assets/admin-css/fullcalendar.min.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.6.0.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.1/umd/popper.min.js"></script>
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>Taxi Booking requests</h4>
    </div>
    <div class="body-main-content">

        <div class="booking-availability-section">
            <div class="row">
                <div class="col-md-8">
                    <div class="kikcard">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                {{-- <div class="mr-auto">
                                    <h4 class="heading-title">Booking Requests</h4>
                                </div> --}}
                                <div class="btn-option-info wd10">
                                    <div class="search-filter">
                                        <form action="{{ route('TaxiBookingRequest') }}" method="POST">
                                            @csrf
                                            <div class="row g-1">

                                                <div class="col-md-3">
                                                    <div class="search-form-group">
                                                        <div class="TotalRequestoverview">Total Request Received:
                                                            <span>{{ count($bookings) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <a href="{{ url('taxi-booking-request') }}" class="btn-gr"><i
                                                                class="fa fa-refresh" aria-hidden="true"></i></a>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="search-form-group">
                                                        <input type="text" name="search" class="form-control"
                                                            value="{{ $search ? $search : '' }}"
                                                            placeholder=" Search User Name,Booking ID">
                                                        <span class="search-icon"><img
                                                                src="{{ assets('assets/admin-images/search-icon.svg') }}"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="date" name="date"
                                                            value="{{ $date ? $date : '' }}" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn-gr"><i class="fa fa-search"
                                                                aria-hidden="true"></i></button>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <a href="#" class="btn-gr" onclick="exportToCSV(this)"
                                                            data-id="taxi_booking_requests">Download Data</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="kik-table table-responsive">
                                <table class="table xp-table" id="taxi_booking_requests">
                                    <thead>
                                        <tr class="table-hd">
                                            <th>Sr No.</th>
                                            <th>User Name</th>
                                            <th>Booked For</th>
                                            <th>Phone</th>
                                            <th>Booking ID</th>
                                            <th>Booking Date & Time</th>
                                            <th>Pickup Location</th>
                                            <th>Drop Off Location</th>
                                            <th>Travel Distance </th>
                                            <th>Hotel Name</th>
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($bookings as $i=> $item)
                                            <tr>
                                                <td>
                                                    <div class="sno">{{ $i + 1 }}</div>
                                                </td>
                                                <td>{{ $item->user_name }}</td>
                                                <td>{{ $item->fullname }}</td>
                                                <td>{{ $item->mobile }}</td>
                                                <td>{{ $item->booking_id ?? 'N/A' }}</td>
                                                <td>{{ date('d M, Y', strtotime($item->booking_time)) }}

                                                <td>{{ $item->pickup_location }}</td>
                                                <td>{{ $item->drop_location }}</td>

                                                <td>{{ $item->distance }} KM</td>
                                                <td> {{ $item->hotel_name }} </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" align="center"> No Booking requests</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-left">
                                    {{ $bookings->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="kikcard">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div class="mr-auto">
                                    <h4 class="heading-title">Taxi Availability Calendar</h4>
                                </div>
                            </div>
                        </div>
                        <div class="kikcard">
                            <div class="card-body">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Manage Dates popup -->
    <div class="modal kik-modal fade" id="eventModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="iot-modal-form">
                        <form id="eventForm">
                            @csrf
                            <h3>Manage Dates</h3>
                            <div class="form-group">
                                <h4>Selected Date</h4>
                                <input type="date" name="date" min="{{ date('Y-m-d') }}" class="form-control"
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
                                                value="Booked Taxi"id="Tour Bookings" required>
                                            <label for="Tour Bookings">Taxi Booking</label>
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
                    '/getTaxiBookingEvent',
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
                    url: '/addTaxiBookingEvent',
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
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                editable: true,
                eventSources: [
                    '/getTaxiBookingEvent',
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

            $('#eventForm').submit(function(event) {
                event.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '/addTaxiBookingEvent',
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
    <script>
        function exportToCSV(ele) {
            // Get table data
            var table = document.getElementById(ele.getAttribute("data-id"));
            var rows = table.querySelectorAll('tbody tr');

            // Create CSV content
            var csvContent = "data:text/csv;charset=utf-8,";
            csvContent += headersToCSV(table);
            csvContent += rowsToCSV(rows);

            // Create and trigger a download link
            var encodedUri = encodeURI(csvContent);
            var link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", ele.getAttribute("data-id") + '.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function headersToCSV(table) {
            var headers = [];
            var headerCols = table.querySelectorAll('thead th');
            for (var i = 0; i < headerCols.length; i++) {
                headers.push(headerCols[i].innerText);
            }
            return headers.join(',') + '\n';
        }

        function rowsToCSV(rows) {
            var csv = [];
            for (var i = 0; i < rows.length; i++) {
                var row = [];
                var cols = rows[i].querySelectorAll('td');
                for (var j = 0; j < cols.length; j++) {
                    row.push(cols[j].innerText);
                }
                csv.push(row.join(','));
            }
            return csv.join('\n');
        }
    </script>
@endsection
