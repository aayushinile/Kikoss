@extends('layouts.admin')
@section('title', 'Kikos -Photo Transaction History')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managebooking.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-plugins/OwlCarousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-plugins/fancybox/jquery.fancybox.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managphoto.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="{{ assets('assets/admin-plugins/OwlCarousel/owl.carousel.min.js') }}" type="text/javascript"></script>
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
        .select2-selection__clear span {
            display: none;
        }
    </style>
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
                                    <div class="btn-option-info w-100">
                                        <div class="search-filter">
                                            <form action="{{ route('PhotoTransactionHistory') }}" method="POST">
                                                @csrf
                                                <div class="row g-1">
                                                    
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="search-form-group">
                                                                <input type="text" name="search"
                                                                    value="{{ $search ? $search : '' }}"
                                                                    class="form-control"
                                                                    placeholder="Search">
                                                                <span class="search-icon"><img
                                                                        src="{{ assets('assets/admin-images/search-icon.svg') }}"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <select class="form-control"name="booth_id" id="booth_id">
                                                                <option>Select By Photo Booth Name</option>
                                                                @if (!$PhotoBooths->isEmpty())
                                                                    @foreach ($PhotoBooths as $tour)
                                                                        <option
                                                                            value="{{ $tour->id }}"@if ($tour->id == $booth_id) selected='selected' @else @endif>
                                                                            {{ $tour->title }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                        <input type="text" name="daterange" value="" class="form-control form-control-solid" autocomplete="off" id="datepicker" placeholder="Select Date">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <a href="{{ url('photo-transaction-history') }}" class="btn-gr"><i
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
                                                        <a href="{{ route('PhotoTransactionHistory', ['download' => 1, 'search' => $search,'booth_id' => $booth_id ?? '','daterange' => $date]) }}" class="btn-gr"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
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
                                                <th>Booking Id</th>
                                                <th>Amount Paid</th>
                                                <th>Amount Recieved On</th>
                                                <th>Media Purchase</th>
                                                <th>Payment Made Via</th>
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
                                                    <td>
                                                        <div class="sno">{{ $s_no }}</div>
                                                    </td>
                                                    <td>{{ $val->Users->fullname ?? '' }}</td>
                                                    <td>{{ $val->booth->title ?? '' }}</td>
                                                    <td>{{ $val->booking_id ?? '' }}</td>
                                                    <td>${{ $val->total_amount ?? '' }} <a class="infoprice"
                                                            data-bs-toggle="modal" href="#infoprice" role="button"
                                                            onclick='GetDataPrice("{{ $val->total_amount }}","{{ $val->amount }}","{{ $val->tax_percent }}","{{ $val->tax }}")'><i
                                                                class="las la-info-circle"></i></a></td>
                                                    <td>{{ date('M d, Y', strtotime($val->booking_date)) ?? '' }}
                                                    </td>
                                                    <td>
                                                        <div class="media-card">
                                                            <div class="photos-text"><img
                                                                    src="{{ assets('assets/admin-images/gallery.svg') }}">
                                                                {{ $val->image_count }}
                                                                Photos
                                                            </div>
                                                            
                                                            <div class="videos-text"><img
                                                                    src="{{ assets('assets/admin-images/video-play.svg') }}">
                                                                {{ $val->video_count }}
                                                                Videos</div>
                                                                <div class="videos-action">
                                                                    @foreach ($val->images as $image)
                                                                        <a href="{{ assets('upload/photo-booth/' . $image->media) }}" data-fancybox="gallery{{ $val->id }}" class="viewbtn" style="display: none;"></a>
                                                                    @endforeach
                                                                    <a href="#" class="viewbtn" onclick="showGallery('{{ $val->id }}')">View</a>
                                                                </div>
                                                        </div>
                                                    </td>
                                                    
                                                    <td>PayPal</td>
                                                    <td>{{$val->transaction_id ?? ''}}</td>
                                                    </tr>
                                                    <?php $s_no++; ?>
                                                @endforeach
                                            @endif
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
