@extends('layouts.admin')
@section('title', 'Kikos - User Detail')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/user.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.datatables.net/v/dt/dt-1.13.8/b-2.4.2/b-html5-2.4.2/datatables.min.css" rel="stylesheet">

    <script src="https://cdn.datatables.net/v/dt/dt-1.13.8/b-2.4.2/b-html5-2.4.2/datatables.min.js"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>User Management</h4>
        <div class="search-filter">
            <div class="row g-2">
                {{-- <div class="col-md-12">
                    <div class="search-form-group">
                        <input type="text" name="" class="form-control" placeholder="Search">
                        <span class="search-icon"><img src="{{ assets('assets/admin-images/search-icon.svg') }}"></span>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    <div class="body-main-content">
        <div class="User-Management-section">
            <div class="User-profile-section">
                <div class="row g-1 align-items-center">
                    <div class="col-md-3">
                        <div class="side-profile-item">
                            <div class="side-profile-media"><img src="{{ assets('assets/admin-images/user-default.png') }}">
                            </div>
                            <div class="side-profile-text">
                                <h2>{{ $data->fullname ?? '' }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="User-contact-info">
                                    <div class="User-contact-info-icon">
                                        <img src="{{ assets('assets/admin-images/sms.svg') }}">
                                    </div>
                                    <div class="User-contact-info-content">
                                        <h2>Email Address</h2>
                                        <p>{{ $data->email ?? '' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="User-contact-info">
                                    <div class="User-contact-info-icon">
                                        <img src="{{ assets('assets/admin-images/call.svg') }}">
                                    </div>
                                    <div class="User-contact-info-content">
                                        <h2>Phone Number</h2>
                                        <p>+1 {{ $data->mobile ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="User-contact-info">
                                    <div class="User-contact-info-content">
                                        <h2>Status</h2>
                                        <div class="switch-toggle">
                                            <p>Inactive</p>
                                            <div class="">
                                                <label class="toggle" for="myToggle">
                                                    <input class="toggle__input"
                                                        @if ($data->status == 1) checked @endif name=""
                                                        type="checkbox" id="myToggle">
                                                    <div class="toggle__fill"></div>
                                                </label>
                                            </div>
                                            <p>Active</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overview-section">
                <div class="row row-cols-xl-5 row-cols-xl-3 row-cols-md-2 g-2">
                    <div class="col flex-fill">
                        <div class="overview-card">
                            <div class="overview-card-body">
                                <div class="overview-content">
                                    <div class="overview-content-text">
                                        <p>Total Amount Received:</p>
                                        <h2>${{ $total_amount }}</h2>
                                    </div>
                                    <div class="overview-content-icon">
                                        <img src="{{ assets('assets/admin-images/dollar-circle.svg') }}">
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
                                        <p>Tour Booked</p>
                                        <h2>{{ $normal_tours->total() }}</h2>
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
                                        <p>Virtual Tour Purchased</p>
                                        <h2>{{ $virtual_tours->total() }}</h2>
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
                                        <p>Total Purchased</p>
                                        <h2>{{ $normal_tours->total() + $virtual_tours->total() + $PhotoBooths->total() }}
                                        </h2>
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
                                        <p>Taxi Booking History</p>
                                        <h2>{{ $taxi_booking_count }}
                                        </h2>
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


            <div class="row">
                <div class="col-md-12">
                    <div class="kikcard">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div class="mr-auto">
                                    <h4 class="heading-title">Booking Transaction History</h4>
                                </div>
                                <div class="btn-option-info wd78">
                                    <div class="search-filter">
                                        <div class="search-filter">
                                            <div class="row g-1">

                                                <div class="col-md-2 d-flex">


                                                    <div class="mt-2">
                                                        <a
                                                            href="{{ url('user-details/' . encrypt_decrypt('encrypt', $data->id)) }}"><i
                                                                class="fa fa-undo" aria-hidden="true"></i></a>
                                                        &nbsp;
                                                    </div>
                                                    <div class="form-group">

                                                        <input type="date"
                                                            value="{{ request()->has('date') ? request('date') : '' }}"
                                                            onchange="location.replace('{{ url('user-details/' . encrypt_decrypt('encrypt', $data->id)) }}?date='+this.value)"
                                                            name="date" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <a class="btn-gr" id="xport" onclick="exportToCSV(this)"
                                                            data-id="normal_tours">Download report</a>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <a onclick="getCheck(this)" data-type="normal_tours"
                                                            data-id="normal_tours" class="btn-bl">Tour
                                                            Booking</a>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <a class="btn-bla" onclick="getCheck(this)"
                                                            data-type="virtual_tours" data-id="virtual_tours">Virtual
                                                            Tour</a>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group" onclick="getCheck(this)"
                                                        data-type="photo_booth" data-id="photo_booth">
                                                        <a class="btn-br">Photo Booth</a>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <a class="btn-gra" onclick="getCheck(this)"
                                                            data-type="taxi_bookings" data-id="taxi_bookings">Taxi
                                                            Booking</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body normal_tours">
                            <div class="kik-table">
                                <table class="table xp-table  " id="normal_tours">
                                    <thead>
                                        <tr class="table-hd">
                                            <th>Sr No.</th>
                                            <th>Tour Name & Duration</th>
                                            <th>Duration</th>
                                            <th>Tour Book Date</th>
                                            <th>Amount Paid</th>
                                            <th>Person</th>
                                            <th>Amount Recieved On</th>
                                            <th>Payment Made Via</th>
                                            <th>Status</th>
                                            <th>Transaction ID</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($normal_tours as $i => $item)
                                            <tr>
                                                <td>
                                                    <div class="sno">{{ $i + 1 }}</div>
                                                </td>
                                                <td>{{ $item->Tour->name }}</td>
                                                <td>{{ $item->Tour->duration }} Hours</td>
                                                <td>{{ date('d M, Y', strtotime($item->booking_date)) }}</td>
                                                <td>${{ $item->total_amount }}.00</td>
                                                <td> {{ $item->no_adults + $item->senior_citizen + $item->no_childerns }}
                                                    People </td>
                                                <td>{{ $item->transaction ? date('d M, Y, h:i:s a', strtotime($item->transaction->created_at)) : 'N/A' }}
                                                </td>
                                                <td> {{ $item->payment_provider }} </td>
                                                <td>
                                                    @if ($item->status == 1)
                                                        <div class="status-text confirmed-status"><i
                                                                class="las la-check-circle"></i>Confirmed
                                                        </div>
                                                    @else
                                                        <div class="status-text rejected-status"><i
                                                                class="las la-times-circle"></i>Rejected (Refund
                                                            Initiated)
                                                        </div>
                                                    @endif
                                                </td>
                                                <td> {{ $item->transaction ? $item->transaction->transaction_id : 'N/A' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" align="center">No tours found</td>
                                            </tr>
                                        @endforelse


                                    </tbody>
                                </table>
                            </div>
                            <div class="kik-table-pagination">
                                <ul class="kik-pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($normal_tours->onFirstPage())
                                        <li class="disabled">
                                            <span>Previous</span>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{ $normal_tours->previousPageUrl() }}" aria-controls="example"
                                                tabindex="0" class="page-link"
                                                data-dt-idx="{{ $normal_tours->currentPage() - 2 }}">Previous</a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($normal_tours->getUrlRange(1, $normal_tours->lastPage()) as $page => $url)
                                        <li class="{{ $page == $normal_tours->currentPage() ? 'active' : '' }}">
                                            <a href="{{ $url }}" aria-controls="example" tabindex="0"
                                                class="page-link"
                                                data-dt-idx="{{ $page - 1 }}">{{ $page }}</a>
                                        </li>
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($normal_tours->hasMorePages())
                                        <li>
                                            <a href="{{ $normal_tours->nextPageUrl() }}" aria-controls="example"
                                                tabindex="0" class="page-link"
                                                data-dt-idx="{{ $normal_tours->currentPage() }}">Next</a>
                                        </li>
                                    @else
                                        <li class="disabled">
                                            <span>Next</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>

                        </div>
                        <div class="card-body virtual_tours d-none">
                            <div class="kik-table">
                                <table class="table xp-table  " id="virtual_tours">
                                    <thead>
                                        <tr class="table-hd">
                                            <th>Sr No.</th>
                                            <th>Tour Name</th>
                                            <th>Duration</th>

                                            <th>Amount Paid</th>

                                            <th>Amount Recieved On</th>

                                            <th>Payment Made Via</th>
                                            <th>Transaction ID</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($virtual_tours as $i => $item)
                                            <tr>
                                                <td>
                                                    <div class="sno">{{ $i + 1 }}</div>
                                                </td>
                                                <td>{{ $item->Tour->name }}</td>
                                                <td>{{ $item->Tour->duration }} Hours</td>

                                                <td>${{ $item->total_amount }}.00</td>

                                                <td>{{ $item->transaction ? date('d M, Y, h:i:s a', strtotime($item->transaction->created_at)) : 'N/A' }}
                                                </td>

                                                <td> {{ $item->payment_provider }} </td>

                                                <td> {{ $item->transaction ? $item->transaction->transaction_id : 'N/A' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" align="center">No tours found</td>
                                            </tr>
                                        @endforelse


                                    </tbody>
                                </table>
                            </div>
                            <div class="kik-table-pagination">
                                <ul class="kik-pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($virtual_tours->onFirstPage())
                                        <li class="disabled">
                                            <span>Previous</span>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{ $virtual_tours->previousPageUrl() }}" aria-controls="example"
                                                tabindex="0" class="page-link"
                                                data-dt-idx="{{ $virtual_tours->currentPage() - 2 }}">Previous</a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($virtual_tours->getUrlRange(1, $virtual_tours->lastPage()) as $page => $url)
                                        <li class="{{ $page == $virtual_tours->currentPage() ? 'active' : '' }}">
                                            <a href="{{ $url }}" aria-controls="example" tabindex="0"
                                                class="page-link"
                                                data-dt-idx="{{ $page - 1 }}">{{ $page }}</a>
                                        </li>
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($virtual_tours->hasMorePages())
                                        <li>
                                            <a href="{{ $virtual_tours->nextPageUrl() }}" aria-controls="example"
                                                tabindex="0" class="page-link"
                                                data-dt-idx="{{ $virtual_tours->currentPage() }}">Next</a>
                                        </li>
                                    @else
                                        <li class="disabled">
                                            <span>Next</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>

                        </div>
                        <div class="card-body photo_booth d-none">
                            <div class="kik-table">
                                <table class="table xp-table  " id="photo_booth">
                                    <thead>
                                        <tr class="table-hd">
                                            <th>Sr No.</th>
                                            <th>Tour Name & Duration</th>

                                            <th>Amount Paid</th>

                                            <th>Amount Recieved On</th>
                                            <th>Media Purchase</th>
                                            <th>Payment Made Via</th>
                                            <th>Transaction ID</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($PhotoBooths as $i => $item)
                                            <tr>
                                                <td>
                                                    <div class="sno">{{ $i + 1 }}</div>
                                                </td>
                                                <td>{{ $item->booth && $item->booth->TourNameBooth ? $item->booth->TourNameBooth->name : 'N/A' }}
                                                </td>

                                                <td>${{ $item->total_amount }}.00</td>

                                                <td>{{ $item->transaction ? date('d M, Y, h:i:s a', strtotime($item->transaction->created_at)) : 'N/A' }}
                                                </td>
                                                <td> 4 Photos , 5 videos </td>
                                                <td> {{ $item->payment_provider }} </td>

                                                <td> {{ $item->transaction ? $item->transaction->transaction_id : 'N/A' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" align="center">No tours found</td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                            <div class="kik-table-pagination">
                                <ul class="kik-pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($PhotoBooths->onFirstPage())
                                        <li class="disabled">
                                            <span>Previous</span>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{ $PhotoBooths->previousPageUrl() }}" aria-controls="example"
                                                tabindex="0" class="page-link"
                                                data-dt-idx="{{ $PhotoBooths->currentPage() - 2 }}">Previous</a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($PhotoBooths->getUrlRange(1, $PhotoBooths->lastPage()) as $page => $url)
                                        <li class="{{ $page == $PhotoBooths->currentPage() ? 'active' : '' }}">
                                            <a href="{{ $url }}" aria-controls="example" tabindex="0"
                                                class="page-link"
                                                data-dt-idx="{{ $page - 1 }}">{{ $page }}</a>
                                        </li>
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($PhotoBooths->hasMorePages())
                                        <li>
                                            <a href="{{ $PhotoBooths->nextPageUrl() }}" aria-controls="example"
                                                tabindex="0" class="page-link"
                                                data-dt-idx="{{ $PhotoBooths->currentPage() }}">Next</a>
                                        </li>
                                    @else
                                        <li class="disabled">
                                            <span>Next</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>

                        </div>
                        <div class="card-body taxi_bookings d-none">
                            <div class="kik-table">
                                <table class="table xp-table  " id="taxi_bookings">
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

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($taxi_booking_requests as $i=> $item)
                                            <tr>
                                                <td>
                                                    <div class="sno">{{ $i + 1 }}</div>
                                                </td>
                                                <td>{{ $item->Username ? $item->Username->fullname : 'N/A' }}</td>
                                                <td>TR0619879238351</td>
                                                <td>{{ date('d M, Y, h:i:s a', strtotime($item->booking_time)) }}

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
                            </div>
                            <div class="kik-table-pagination">
                                <ul class="kik-pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($taxi_booking_requests->onFirstPage())
                                        <li class="disabled">
                                            <span>Previous</span>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{ $taxi_booking_requests->previousPageUrl() }}"
                                                aria-controls="example" tabindex="0" class="page-link"
                                                data-dt-idx="{{ $taxi_booking_requests->currentPage() - 2 }}">Previous</a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($taxi_booking_requests->getUrlRange(1, $taxi_booking_requests->lastPage()) as $page => $url)
                                        <li class="{{ $page == $taxi_booking_requests->currentPage() ? 'active' : '' }}">
                                            <a href="{{ $url }}" aria-controls="example" tabindex="0"
                                                class="page-link"
                                                data-dt-idx="{{ $page - 1 }}">{{ $page }}</a>
                                        </li>
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($taxi_booking_requests->hasMorePages())
                                        <li>
                                            <a href="{{ $taxi_booking_requests->nextPageUrl() }}" aria-controls="example"
                                                tabindex="0" class="page-link"
                                                data-dt-idx="{{ $taxi_booking_requests->currentPage() }}">Next</a>
                                        </li>
                                    @else
                                        <li class="disabled">
                                            <span>Next</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <script>
        function getCheck(ele) {
            $(".card-body").addClass("d-none")
            $(`.${ele.getAttribute('data-type')}`).removeClass("d-none");
            $("#xport").attr("data-id", ele.getAttribute('data-type'));


        }
        $(document).ready(function() {
            $('#myToggle').on('change', function() {

                var newStatus = this.checked ? '1' : '0';

                $.ajax({
                    url: '{{ url('toggleUserStatus') }}',

                    type: 'POST',
                    data: {
                        user_id: {{ $data->id }},
                        status: newStatus,
                        _token: '{{ csrf_token() }}'
                    }, // Add CSRF token
                    success: function(response) {
                        // Update the UI or perform other actions based on the response
                        console.log('User status toggled to: ' + response.status);
                        if (response.success) {
                            toastr.success(response.message);

                        } else {
                            toastr.error(response.message)

                        }
                    },
                    error: function(error) {
                        console.error('Error toggling user status:', error);
                    }
                });
            });
        });

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
