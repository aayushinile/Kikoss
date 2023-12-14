@extends('layouts.admin')
@section('title', 'Kikos - Manage Booking')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managebooking.css') }}">
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>Manage Booking</h4>
        <div class="page-breadcrumb-action wd5">
            <div class="row g-2">
                <div class="col-md-4">
                    <a href="{{ url('tour-inquiry-request') }}" class="wh-btn">Tour Inquiry Requests</a>
                </div>
                <div class="col-md-4">
                    <a href="{{ url('view-transaction-history') }}" class="wh-btn">View Transaction History</a>
                </div>
                <div class="col-md-4">
                    <a href="{{ url('add-tour') }}" class="wh-btn">Add New Tour</a>
                </div>
            </div>
        </div>
    </div>
    <div class="body-main-content">
        <div class="User-Management-section">


            <div class="overview-section">
                <div class="row row-cols-xl-5 row-cols-xl-3 row-cols-md-2 g-2">
                    <div class="col flex-fill">
                        <div class="overview-card">
                            <div class="overview-card-body">
                                <div class="overview-content">
                                    <div class="overview-content-text">
                                        <p>Total Amount Received:</p>
                                        <h2>$ 2589.99</h2>
                                    </div>
                                    <div class="overview-content-icon">
                                        <img src="{{ assets('assets/admin-images/dollar-circle.svg') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="booking-tour-section">
                <div class="row">
                    <div class="col-md-4">
                        <div class="booking-tour-card">
                            <div class="booking-tour-card-media"
                                style="background: url({{ assets('assets/admin-images/IMG_9838.jpg') }});width: 100%;height: 100%;position: absolute;background-size: 100%;background-repeat: no-repeat;background-position: right;">
                            </div>
                            <div class="booking-tour-card-content">
                                <div class="manage-tour-card-text">
                                    <h3>North Shore <span>Total 120</span></h3>
                                    <p>23 People Occupancy Left</p>
                                </div>
                                <div class="booking-tour-card-action">
                                    <a class="delete-btn" href="#">Delete</a>
                                    <a class="edit-btn" href="#">Edit Tour</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="booking-tour-card">
                            <div class="booking-tour-card-media"
                                style="background: url({{ assets('assets/admin-images/IMG_0062.jpg') }});width: 100%;height: 100%;position: absolute;background-size: 100%;background-repeat: no-repeat;background-position: right;">
                            </div>
                            <div class="booking-tour-card-content">
                                <div class="manage-tour-card-text">
                                    <h3>West Oahu <span>Total 120</span></h3>
                                    <p>12 People Occupancy Left</p>
                                </div>
                                <div class="booking-tour-card-action">
                                    <a class="delete-btn" href="#">Delete</a>
                                    <a class="edit-btn" href="#">Edit Tour</a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="booking-tour-card">
                            <div class="booking-tour-card-media"
                                style="background: url({{ assets('assets/admin-images/IMG_8495.jpg') }});width: 100%;height: 100%;position: absolute;background-size: 100%;background-repeat: no-repeat;background-position: right;">
                            </div>
                            <div class="booking-tour-card-content">
                                <div class="manage-tour-card-text">
                                    <h3>North Shore <span>Total 120</span></h3>
                                    <p>23 People Occupancy Left</p>
                                </div>
                                <div class="booking-tour-card-action">
                                    <a class="delete-btn" href="#">Delete</a>
                                    <a class="edit-btn" href="#">Edit Tour</a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="booking-tour-card">
                            <div class="booking-tour-card-media"
                                style="background: url({{ assets('assets/admin-images/IMG_7293.jpg') }});width: 100%;height: 100%;position: absolute;background-size: 100%;background-repeat: no-repeat;background-position: right;">
                            </div>
                            <div class="booking-tour-card-content">
                                <div class="manage-tour-card-text">
                                    <h3>Foodie & Farm tour <span>Total 120</span></h3>
                                    <p>05 People Occupancy Left</p>
                                </div>
                                <div class="booking-tour-card-action">
                                    <a class="delete-btn" href="#">Delete</a>
                                    <a class="edit-btn" href="#">Edit Tour</a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="booking-tour-card">
                            <div class="booking-tour-card-media"
                                style="background: url({{ assets('assets/admin-images/IMG_6034.jpg') }});width: 100%;height: 100%;position: absolute;background-size: 100%;background-repeat: no-repeat;background-position: right;">
                            </div>
                            <div class="booking-tour-card-content">
                                <div class="manage-tour-card-text">
                                    <h3>Sunrise Hike<span>Total 120</span></h3>
                                    <p>05 People Occupancy Left</p>
                                </div>
                                <div class="booking-tour-card-action">
                                    <a class="delete-btn" href="#">Delete</a>
                                    <a class="edit-btn" href="#">Edit Tour</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="booking-availability-section">
                <div class="row">
                    <div class="col-md-8">
                        <div class="kikcard">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <div class="mr-auto">
                                        <h4 class="heading-title">Tour Booking Requests</h4>
                                    </div>
                                    <div class="btn-option-info wd7">
                                        <div class="search-filter">
                                            <div class="row g-1">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="search-form-group">
                                                            <input type="text" name="" class="form-control"
                                                                placeholder="Search User name, Amount & Status">
                                                            <span class="search-icon"><img
                                                                    src="{{ assets('assets/admin-images/search-icon.svg') }}"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
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

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="date" name="" class="form-control">
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
                                                <th>Tour Book Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="sno">1</div>
                                                </td>
                                                <td>Jane Doe</td>
                                                <td>North Shore</td>
                                                <td>8 Hours</td>
                                                <td>16/02/2023</td>
                                                <td>
                                                    <div class="status-text Pending-status"><i
                                                            class="las la-hourglass-start"></i> Pending for Approval</div>
                                                </td>
                                                <td>
                                                    <div class="action-btn-info">
                                                        <a class="action-btn dropdown-toggle" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="las la-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item view-btn" data-bs-toggle="modal"
                                                                href="#BookingRequest" role="button"><i
                                                                    class="las la-eye"></i> View</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <div class="sno">2</div>
                                                </td>
                                                <td>Jane Doe</td>
                                                <td>North Shore</td>
                                                <td>8 Hours</td>
                                                <td>16/02/2023</td>
                                                <td>
                                                    <div class="status-text Pending-status"><i
                                                            class="las la-hourglass-start"></i> Pending for Approval</div>
                                                </td>
                                                <td>
                                                    <div class="action-btn-info">
                                                        <a class="action-btn dropdown-toggle" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="las la-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item view-btn" data-bs-toggle="modal"
                                                                href="#BookingRequest" role="button"><i
                                                                    class="las la-eye"></i> View</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>
                                                    <div class="sno">3</div>
                                                </td>
                                                <td>Jane Doe</td>
                                                <td>North Shore</td>
                                                <td>8 Hours</td>
                                                <td>16/02/2023</td>
                                                <td>
                                                    <div class="status-text Pending-status"><i
                                                            class="las la-hourglass-start"></i> Pending for Approval</div>
                                                </td>
                                                <td>
                                                    <div class="action-btn-info">
                                                        <a class="action-btn dropdown-toggle" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="las la-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item view-btn" data-bs-toggle="modal"
                                                                href="#BookingRequest" role="button"><i
                                                                    class="las la-eye"></i> View</a>
                                                        </div>
                                                    </div>
                                                </td>
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

                    <div class="col-md-4">
                        <div class="kikcard">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <div class="mr-auto">
                                        <h4 class="heading-title">Tours Availability Calendar</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
