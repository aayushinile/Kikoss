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
                                    <div class="card-body">
                                        <div class="kik-table">
                                            <table class="table xp-table  " id="customer-table">
                                                <thead>
                                                    <tr class="table-hd">
                                                        <th>Sr No.</th>
                                                        <th>Tour Name</th>
                                                        <th>User Name</th>
                                                        <th>Booking Id</th>
                                                        <th>Transaction Id</th>
                                                        <th>Payment Method</th>
                                                        <th>Type</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                        <th>Date</th>
                                                        <th>Action</th>
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
                                                        <?php $s_no = 1; ?> @foreach ($payment_details as $val)
                                                            <tr>
                                                                <td>
                                                                    <div class="sno">{{ $s_no }}</div>
                                                                </td>
                                                                <td>
                                                                {{ $val->title ?? '' }}  
                                                                </td>
                                                                <td>
                                                                {{ $val->user_name ?? '' }}  
                                                                </td>
                                                                <td>{{ $val->booking_id ?? '' }}</td>
                                                                <td>{{ $val->transaction_id ?? '' }}</td>
                                                                <td>{{ $val->payment_provider ?? '' }}</td>

                                                                <td>
                                                                    @if ($val->tour_type == 1)
                                                                        Normal Tour
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
                                                                <td>${{ $val->amount ?? '-' }}</td>
                                                                <td>
                                                                    @if ($val->status == 1)
                                                                       Accepted
                                                                    @elseif ($val->status == 2)
                                                                        Rejected
                                                                    @else
                                                                        Null
                                                                    @endif
                                                                </td>
                                                                <td>{{ date('d M, Y H:i:s', strtotime($val->created_at)) ?? '' }}</td>
                                                                <td>
                                                                    <div class="action-btn-info">
                                                                        <a class="btn btn-outline-primary" href="#" data-bs-toggle="modal" data-bs-target="#refundConfirmationModal">Refund</a>
                                                                    </div>
                                                                </td>
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

@endsection
