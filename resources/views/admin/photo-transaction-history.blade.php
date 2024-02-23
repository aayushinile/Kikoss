@extends('layouts.admin')
@section('title', 'Kikos -Photo Transaction History')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managebooking.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>Manage Photo Booth</h4>
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
                                                        <span>5689</span>
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
                                            <th>Amount Paid</th>
                                            <th>Amount Recieved On</th>
                                            <th>Media Purchase</th>
                                            <th>Amount Recieved On</th>
                                            <th>Transaction ID</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="sno">1</div>
                                            </td>
                                            <td>John</td>
                                            <td>7 Am Hike</td>
                                            <td>$559.00 <a class="infoprice" data-bs-toggle="modal" href="#infoprice"
                                                    role="button"><i class="las la-info-circle"></i></a></td>
                                            <td>03 Sep, 2023, 09:33:12 am</td>
                                            <td>
                                                <div class="media-card">
                                                    <div class="photos-text"><img
                                                            src="{{ assets('assets/admin-images/gallery.svg') }}"> 432
                                                        Photos
                                                    </div>
                                                    <div class="videos-text"><img
                                                            src="{{ assets('assets/admin-images/video-play.svg') }}"> 98
                                                        Videos
                                                    </div>
                                                </div>
                                            </td>
                                            <td>PayPal</td>
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
    <!-- price Info -->
    <div class="modal kik-modal fade" id="infoprice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="iot-modal-form">
                        <h3>Amount Info </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="kik-request-item-card">
                            <div class="kik-request-item-card-head">
                                <div class="request-id-text">Transaction ID:<span>76375873874</span></div>
                            </div>
                            <div class="kik-request-item-card-body">
                                <div class="request-point-section">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="request-point-item">
                                                <h3>People Ages 11+ <span>2</span></h3>
                                                <h4>$149</h4>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="request-point-item">
                                                <h3>Senior Ages 60+ <span>1</span></h3>
                                                <h4>$109</h4>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="request-point-item">
                                                <h3>Children Ages 10 & Under <span>1</span></h3>
                                                <h4>$109</h4>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="request-point-item">
                                                <h3>Tax <span>2%</span></h3>
                                                <h4>$43</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kik-request-item-card-foot">
                                <div class="request-price-text">Total Cost<span>$410</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
