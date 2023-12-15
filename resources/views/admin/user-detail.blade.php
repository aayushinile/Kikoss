@extends('layouts.admin')
@section('title', 'Kikos - User Detail')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/user.css') }}">
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>User Management</h4>
        <div class="search-filter">
            <div class="row g-2">
                <div class="col-md-12">
                    <div class="search-form-group">
                        <input type="text" name="" class="form-control" placeholder="Search">
                        <span class="search-icon"><img src="{{ assets('assets/admin-images/search-icon.svg') }}"></span>
                    </div>
                </div>
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
                                                    <input class="toggle__input" name="" type="checkbox"
                                                        id="myToggle">
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
                                        <h2>$0</h2>
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
                                        <p>Virtual Tour Purchased</p>
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
                                        <p>Total purchased</p>
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
                                        <p>Taxi Booking History</p>
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
                            <div class="kik-table">
                                <table class="table xp-table  " id="customer-table">
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
                                        <tr>
                                            <td>
                                                <div class="sno">1</div>
                                            </td>
                                            <td>North Shore</td>
                                            <td>8 Hours</td>
                                            <td>16/02/2023</td>
                                            <td>$559.00</td>
                                            <td> 4 People </td>
                                            <td> 03 Sep, 2023, 09:33:12 am </td>
                                            <td> PayPal </td>
                                            <td>
                                                <div class="status-text confirmed-status"><i
                                                        class="las la-check-circle"></i> Confirmed</div>
                                            </td>
                                            <td> 76375873874 </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="sno">2</div>
                                            </td>
                                            <td>North Shore</td>
                                            <td>8 Hours</td>
                                            <td>16/02/2023</td>
                                            <td>$559.00</td>
                                            <td> 4 People </td>
                                            <td> 03 Sep, 2023, 09:33:12 am </td>
                                            <td> PayPal </td>
                                            <td>
                                                <div class="status-text rejected-status"><i
                                                        class="las la-times-circle"></i> Rejected (Refund Initiated)</div>
                                            </td>
                                            <td> 76375873874 </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="sno">3</div>
                                            </td>
                                            <td>North Shore</td>
                                            <td>8 Hours</td>
                                            <td>16/02/2023</td>
                                            <td>$559.00</td>
                                            <td> 4 People </td>
                                            <td> 03 Sep, 2023, 09:33:12 am </td>
                                            <td> PayPal </td>
                                            <td>
                                                <div class="status-text confirmed-status"><i
                                                        class="las la-check-circle"></i> Confirmed</div>
                                            </td>
                                            <td> 76375873874 </td>
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
