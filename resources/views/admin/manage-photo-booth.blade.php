@extends('layouts.admin')
@section('title', 'Kikos - Manage Photo-Booth')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managebooking.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-plugins/OwlCarousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-plugins/fancybox/jquery.fancybox.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managphoto.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="{{ assets('assets/admin-plugins/OwlCarousel/owl.carousel.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/fancybox/jquery.fancybox.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#managevertualtour').owlCarousel({
                loop: false,
                margin: 10,
                nav: false,
                dots: false,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    1000: {
                        items: 3
                    }
                }
            })
        });
    </script>
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>Manage Photo Booth</h4>
        <div class="page-breadcrumb-action wd4">
            <div class="row g-2">
                <div class="col-md-6">
                    <a href="{{ url('photo-transaction-history') }}" class="wh-btn">View Transaction History</a>
                </div>
                <div class="col-md-6">
                    <a href="{{ url('add-photo-booth') }}" class="wh-btn">Upload new tour Photos/Videos</a>
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
                                        <p>Total Amount Received</p>
                                        <h2>$0</h2>
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

            @if ($PhotoBooths->isEmpty())
                <tr>
                    <td colspan="11" class="text-center">
                        No record found
                    </td>
                </tr>
            @elseif(!$PhotoBooths->isEmpty())
                @foreach ($PhotoBooths as $val)
                    <div class="booking-tour-section">
                        <div id="managevertualtour" class="owl-carousel owl-theme">
                            <div class="item">
                                <div class="kik-request-item-card">
                                    <div class="kik-request-item-card-body">
                                        <div class="request-package-card mb-0">
                                            <div class="request-package-card-media">
                                                <img src="{{ assets('assets/admin-images/IMG_9838.jpg') }}">
                                            </div>
                                            <div class="request-package-card-text">
                                                <h2>{{ $val->TourNameBooth->name }}</h2>
                                                <div class="gallery-text"><img
                                                        src="{{ assets('assets/admin-images/gallery.svg') }}">
                                                    {{ PhotoCount($val->id) }}
                                                    Photos</div>
                                                <div class="gallery-text"><img
                                                        src="{{ assets('assets/admin-images/video-play.svg') }}">
                                                    {{ VideoCount($val->id) }} Videos</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kik-request-item-card-foot">
                                        <div class="request-price-text">Price:<span>${{ $val->price }}</span></div>
                                        <div class="request-cancellation-btn">
                                            <a href=""data-bs-toggle="modal" data-bs-target="#deletepopup"
                                                class="rejectbtn"onclick='GetData("{{ $val->id }}","{{ $val->TourNameBooth->name }}")'>Delete</a>
                                            <a href="{{ url('edit-photo-booth/' . encrypt_decrypt('encrypt', $val->id)) }}"
                                                class="acceptbtn">Edit</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <div class="booking-availability-section">
                <div class="row">
                    <div class="col-md-12">
                        <div class="kikcard">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <div class="mr-auto">
                                        <h4 class="heading-title">Photo Booth Booking</h4>
                                    </div>
                                    <div class="btn-option-info wd7">
                                        <div class="search-filter">
                                            <form action="{{ route('ManageVirtualTour') }}" method="POST">
                                                @csrf
                                                <div class="row g-1">
                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <a href="{{ url('manage-virtual-tour') }}" class="btn-gr"><i
                                                                    class="fa fa-refresh" aria-hidden="true"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="search-form-group">
                                                                <input type="text" name="" class="form-control"
                                                                    placeholder="Search User name, Amount & virtual tour name..">
                                                                <span class="search-icon"><img
                                                                        src="{{ assets('assets/admin-images/search-icon.svg') }}"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <select class="form-control">
                                                                <option>Select Tour</option>
                                                                @if (!$tours->isEmpty())
                                                                    @foreach ($tours as $tour)
                                                                        <option value="{{ $tour->id }}">
                                                                            {{ $tour->name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <input type="date" name="date" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn-gr"><i class="fa fa-search"
                                                                    aria-hidden="true"></i></button>
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
                                                <th>Name</th>
                                                <th>Tour Name</th>
                                                <th>Amount Paid</th>
                                                <th>Amount Recieved On</th>
                                                <th>Payment Made Via</th>
                                                <th>Media Purchase</th>
                                                <th>Transaction ID</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($bookings->isEmpty())
                                                <tr>
                                                    <td colspan="11" class="text-center">
                                                        No record found
                                                    </td>
                                                </tr>
                                            @elseif(!$bookings->isEmpty())
                                                <?php $s_no = 1; ?>
                                                @foreach ($bookings as $val)
                                                    <tr>
                                                        <td>
                                                            <div class="sno">{{ $s_no }}</div>
                                                        </td>
                                                        <td>{{ $val->Users->fullname ?? '' }}</td>
                                                        <td>{{ $val->booth->title ?? '' }}</td>
                                                        <td>${{ $val->total_amount ?? '' }} <a class="infoprice"
                                                                data-bs-toggle="modal" href="#infoprice"
                                                                role="button"><i class="las la-info-circle"></i></a></td>
                                                        <td>{{ date('d M, Y, h:i:s a', strtotime($val->booking_date)) ?? '' }}
                                                        </td>
                                                        <td>
                                                            <div class="media-card">
                                                                <div class="photos-text"><img
                                                                        src="{{ assets('assets/admin-images/gallery.svg') }}">
                                                                    432
                                                                    Photos
                                                                </div>
                                                                <div class="videos-text"><img
                                                                        src="{{ assets('assets/admin-images/video-play.svg') }}">
                                                                    98
                                                                    Videos</div>
                                                                <div class="videos-action">
                                                                    <a href="{{ assets('assets/admin-images/IMG_9838.jpg') }}"
                                                                        data-fancybox="images" class="viewbtn">View</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>PayPal</td>
                                                        <td>76375873874</td>
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
        </div>
    </div>
    <!-- price Info -->
    <div class="modal kik-modal fade" id="infoprice" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
                                                <h3>Purchase at</h3>
                                                <h4>$23.00</h4>
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
                                <div class="request-price-text">Total Cost<span>$66.00</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- delete popup -->
    <div class="modal kik-modal fade" id="deletepopup" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="iot-modal-delete-form">
                        <div class="kik-modal-delete-card">
                            <div class="kik-modal-delete-icon">
                                <img src="{{ assets('assets/admin-images/delete-icon.svg') }}">
                            </div>
                            <h3>Are you sure you want to delete?</h3>
                            <h4 id="Name"></h4>
                            <div class="kik-modal-action">
                                <form action="{{ route('DeletePhotoBooth') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="" name="photo_booth_id" id="photo_booth_id">
                                    <button class="yesbtn"type="submit">Yes Confirm Delete</button>
                                    <button class="Cancelbtn" data-bs-dismiss="modal"
                                        type="button"aria-label="Close">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-------------------- Appenf Popup -Jquery -------------------->
    <script>
        function GetData(IDS, Name) {
            document.getElementById("Name").innerText =
                Name;
            document.getElementById("photo_booth_id").value = IDS;
        }
    </script>
@endsection
