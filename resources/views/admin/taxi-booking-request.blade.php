@extends('layouts.admin')
@section('title', 'Kikos - Manage Photo-Booth')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/taxi-booking-requests.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                        </div>
                        <div class="card-body">
                            <div class="kik-table">
                                <table class="table xp-table  " id="taxi_booking_requests">
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
            </div>
        </div>

    </div>
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
