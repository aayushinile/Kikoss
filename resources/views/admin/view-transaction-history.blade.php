@extends('layouts.admin')
@section('title', 'Kikos - Manage Booking')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managebooking.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
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
                            <div class="d-flex align-items-center">
                                <div class="mr-auto">
                                    <h4 class="heading-title">View Transaction History</h4>
                                </div>
                                <div class="btn-option-info wd8">
                                    <div class="search-filter">
                                        <div class="row g-1">

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="TotalRequestoverview">Total Request Received:
                                                        <span>{{$count ?? ''}}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="search-form-group">
                                                        <input type="text" name="" class="form-control"
                                                            placeholder="Search User name, Amount & Status">
                                                        <span class="search-icon"><img
                                                                src="{{ assets('assets/admin-images/search-icon.svg') }}"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <select class="form-control">
                                                        <option>Select Tour</option>
                                                        <option>West Oahu</option>
                                                        <option>Sunrise Hike</option>
                                                        <option>Foodie & Farm Tour</option>
                                                        <option>7 Am Hike</option>
                                                    </select>
                                                </div>
                                            </div>

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
                                            <th>Tour Name</th>
                                            <th>Duration</th>
                                            <th>Amount Paid</th>
                                            <th>Tour Book Date</th>
                                            <th>Person</th>
                                            <th>Booking Id</th>
                                            <th>Payment Made Via</th>
                                            <th>Status</th>
                                            <th>Transaction ID</th>
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
                                            <td>{{$val->user_name ?? ''}}</td>
                                            <td>{{$val->Tour['title'] ?? 'Null'}}</td>
                                            <td>{{$val->Tour['duration'] ?? ''}} Hours</td>
                                            <td>${{$val->total_amount ?? ''}}</td>
                                            <td>{{$val->booking_date ?? ''}}</td>
                                            <td> {{$val->Tour['total_people'] ?? 0}} </td>
                                            <td>{{$val->booking_id ?? ''}}</td>
                                            <td> PayPal </td>
                                            <td> {{$val->status ?? ''}}</td>
                                            <td>{{$val->transaction_id ?? ''}}</td>
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
@endsection
