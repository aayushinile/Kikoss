@extends('layouts.admin')
@section('title', 'Kikos - Payments')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managebooking.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- CSS for full calender -->
    <link rel="stylesheet" href="{{ assets('assets/admin-css/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.css">
    <script src="{{ assets('assets/admin-js/jquery-3.6.0.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.1/umd/popper.min.js"></script>
    <style>
        .daterangepicker.show-calendar .drp-buttons {
            display: none !important;
        }
        /* Custom CSS to adjust date colors in the calendar */
        .daterangepicker td.available:hover, .daterangepicker td.available.active {
            background-color: #337ab7; /* Adjust the background color of hovered and active dates */
            color: #fff; /* Adjust the text color of hovered and active dates */
        }

        .daterangepicker td.available {
            background-color: #fff; /* Adjust the background color of available dates */
            color: #000; /* Adjust the text color of available dates */
        }
    </style>
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>Payment Details</h4>
    </div>
    <div class="body-main-content">
        <div class="User-Management-section">

            <div class="booking-availability-section">
                <div class="row">
                    <div class="col-md-12">
                        <div class="tasks-content-info tab-content">
                            <div class="tab-pane active" id="BookingRequest">
                                <div class="kikcard">
                                <div class="card-header">
                            <div class="d-flex">
                                <div class="btn-option-info w-100">
                                    <div class="search-filter">
                                        <form action="{{ route('PaymentDetails') }}" method="POST">
                                            @csrf
                                            <div class="row g-1">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <div class="search-form-group">
                                                            <input type="text" name="search"
                                                                value="{{ $search ? $search : '' }}" class="form-control"
                                                                placeholder="Search User name, Transaction Id, Booking Id">
                                                            <span class="search-icon"><img
                                                                    src="{{ assets('assets/admin-images/search-icon.svg') }}"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                <div class="form-group">
    <select class="form-control" name="tour_id" id="tour_id">
        <option value="">Select By Tour Name</option>
        @if (!$tours->isEmpty())
            @foreach ($tourTitles as $tour)
                <option value="{{ $tour }}" {{ $tour_id == $tour ? 'selected' : '' }}>
                    {{ $tour}}
                </option>
            @endforeach
        @endif
    </select>
</div>

                                                </div>


                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                    <input type="text" name="daterange" value="" class="form-control form-control-solid" autocomplete="off" id="datepicker" placeholder="Select Date Range">
                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn-gr"><i class="fa fa-search"
                                                                aria-hidden="true"></i></button>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <a href="{{ url('payments') }}" class="btn-gr"><i
                                                                class="fa fa-refresh" aria-hidden="true"></i></a>
                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                    <a href="{{ route('PaymentDetails', ['download' => 1, 'search' => $search,'tour_id' => $tour_id,'daterange' => $date]) }}" class="btn-gr"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
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
                                                        <th>Booking Id</th>
                                                        <th>Transaction Id</th>
                                                        <th>Tour Name</th>
                                                        <th>User Name</th>
                                                        <th>Payment Method</th>
                                                        <th>Tour Type</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                        <th>Date</th>
                                                        <!-- <th>Action</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($payment_details->isEmpty())
                                                        <tr>
                                                            <td colspan="11" class="text-center">
                                                                No record found
                                                            </td>
                                                        </tr>
                                                    @elseif(!$payment_details->isEmpty())
                                                        <?php $s_no = 1; ?> 
                                                        @foreach ($payment_details as $val)
                                                        <tr>
                                                            <td>
                                                                <div class="sno">{{ $s_no }}</div>
                                                            </td>
                                                            <td>{{ $val->booking_id}}</td>
                                                            <td>{{ $val->transaction_id }}</td>
                                                            @if($val->tour_type == 1)
                                                            <td>{{ $val->title }}</td>
                                                            @elseif($val->tour_type == 2)
                                                            <td>{{ $val->virtual_title }}</td>
                                                            @else
                                                            <td>{{ $val->photo_title }}</td>
                                                            @endif
                                                            <td>{{ $val->user_name }}</td>
                                                           
                                                            <td>{{ $val->payment_provider }}</td>
                                                            <td>
                                                                @if ($val->tour_type == 1)
                                                                   Tour
                                                                @elseif ($val->tour_type == 2)
                                                                    Virtual Tour
                                                                @elseif ($val->tour_type == 3)
                                                                    Photo Booth
                                                                @elseif ($val->tour_type == 4)
                                                                    Taxi Booking
                                                                @else
                                                                    Null
                                                                @endif
                                                            </td>
                                                            <td>${{ $val->amount }}</td>
                                                            <td>
                                                                @if ($val->status == 1)
                                                                    Accepted
                                                                @elseif ($val->status == 2)
                                                                    Rejected
                                                                @else
                                                                    Null
                                                                @endif
                                                            </td>
                                                            <td>{{ date('M d, Y', strtotime($val->created_at)) }}</td>
                                                            <!-- <td>
                                                                <div class="action-btn-info">
                                                                    <a class="btn btn-outline-primary" href="#" data-bs-toggle="modal" data-bs-target="#refundConfirmationModal">Refund</a>
                                                                </div>
                                                            </td> -->
                                                        </tr>

                                                            <?php $s_no++; ?>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="refundConfirmationModal" tabindex="-1" aria-labelledby="refundConfirmationModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-danger" id="refundConfirmationModalLabel">Confirm Refund</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-footer modal-footer_2">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="refund_amount" class="form-label text-black">Please Enter the amount you want to refund</label>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <input type="text" class="form-control" id="refund_amount" placeholder="Enter amount">
                                    <input type="hidden" id="capture_id" value="46U66221VF726110C">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-primary" id="submitRefundBtn">Submit</button>
                                </div>
                            </div>
                        </div>


                        </div>
                    </div>
                </div>
                <div class="modal fade" id="refundConfirmationModal" tabindex="-1" aria-labelledby="refundConfirmationModalLabel" aria-hidden="true">
                    <!-- Modal content -->
                </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add this script tag in your HTML file before the script that uses Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
    document.getElementById('submitRefundBtn').addEventListener('click', function() {
        var refundAmount = document.getElementById('refund_amount').value;
        var captureId = document.getElementById('capture_id').value;

        // Send the refund request via AJAX
        axios.post('{{ route('refund.payment') }}', {
            amount: refundAmount,
            captureId: captureId
        })
        .then(function(response) {
            // Handle success response
            console.log(response.data);
            // Show success message using SweetAlert
            swal({
                title: "Success",
                text: "Refund successful",
                icon: "success",
            }).then(function() {
                // Close the payment modal
                $('#refundConfirmationModal').modal('hide');
            });
        })
        .catch(function(error) {
            // Handle error response
            console.error(error);
            // Optionally, display an error message to the user
        });
    });
</script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
    $(function() {
        // Bind the initialization of date range picker to focus event of the input field
        $('input[name="daterange"]').on('focus', function() {
            $(this).daterangepicker({
                showSelector: false,
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                // Update the visible input field with the selected date range
                $('input[name="dateranges"]').val(start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });
        });
    });
</script>
@endsection
