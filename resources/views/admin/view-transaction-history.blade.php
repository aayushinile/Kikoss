@extends('layouts.admin')
@section('title', 'Kikos - Manage Booking')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managebooking.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
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
        <h4>Manage Booking</h4>
    </div>
    <div class="body-main-content">


        <div class="booking-availability-section">
            <div class="row">
                <div class="col-md-12">
                    <div class="kikcard">
                        <div class="card-header">
                            <div class="d-flex">
                                    <div class="search-filter">
                                    <form action="{{ route('ViewTransactionHistory') }}" method="POST">
                                    @csrf
                                        <div class="row g-1" style="width: 106%;">

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="TotalRequestoverview">Total Request Received:
                                                        <span>{{$count ?? ''}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="search-form-group">
                                                        <input type="text" name="search" class="form-control"
                                                            placeholder="Search User name, Amount & Status,Booking Id"  value="{{ $search ? $search : '' }}" >
                                                        <span class="search-icon"><img
                                                                src="{{ assets('assets/admin-images/search-icon.svg') }}"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select class="form-control" name="tour_id" id="tour_id">
                                                    <option value="">Select Tour</option>
                                                    @if (!$tours->isEmpty())
                                                        @foreach ($tours as $tour)
                                                            <option
                                                                value="{{ $tour->id }}"@if ($tour->id == $tour_id) selected='selected'@endif>
                                                                {{ $tour->name }}</option>
                                                        @endforeach
                                                    @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                <input type="text" name="daterange" value="" class="form-control form-control-solid" autocomplete="off" id="datepicker" placeholder="Select Date">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                            <div class="form-group">
                                                        <a href="{{ url('/view-transaction-history') }}" class="btn-gr"><i
                                                                class="fa fa-refresh" aria-hidden="true"></i></a>
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
                                                    <a href="{{ route('ViewTransactionHistory', ['download' => 1, 'search' => $search,'tour_id' => $tour_id,'daterange' => $date]) }}" class="btn-gr"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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
                                            <th>Transaction ID</th>
                                            <th>Name</th>
                                            <th>Tour Name</th>
                                            <th>Duration</th>
                                            <th>Amount</th>
                                            <th>Tour Book Date</th>
                                            <th>Person</th>
                                            <th>Payment Mode</th>
                                            <th>Status</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if ($datas->isEmpty())
                                    <tr>
                                        <td colspan="11" class="text-center">
                                            No record found
                                        </td>
                                    </tr>
                                    @else
                                    <?php $s_no = 1; ?>
                                    @foreach ($datas as $val)
                                        <tr>
                                            <td>
                                                <div class="sno">{{ $s_no }}</div>
                                            </td>
                                            <td>{{$val->booking_id ?? ''}}</td>
                                            <td>{{$val->transaction_id ?? ''}}</td>
                                            <td>{{$val->user_name ?? ''}}</td>
                                            <td>{{$val->Tour['name'] ?? 'Null'}}</td>
                                            <td>{{$val->Tour['duration'] ?? ''}} Hours</td>
                                            <td>${{$val->total_amount ?? ''}}</td>
                                            <td>{{ date('M d, Y',strtotime($val->booking_date))  ?? ''}}</td>
                                            <td> {{$val->Tour['total_people'] ?? 0}} </td>
                                            
                                            <td> PayPal </td>
                                            <td> @if ($val->status == 1)
                                                    Accepted
                                                @elseif ($val->status == 2)
                                                    Rejected
                                                @else
                                                    Null
                                                @endif
                                            </td>
                                            
                                        </tr>
                                        <?php $s_no++; ?>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="kik-table-pagination">
                            {{ $datas->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
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
