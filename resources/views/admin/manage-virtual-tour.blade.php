@extends('layouts.admin')
@section('title', 'Kikos - Manage Virtual-tour')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-plugins/OwlCarousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managevertualtour.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/OwlCarousel/owl.carousel.min.js') }}" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
        <h4>Manage Virtual Tour</h4>
        <div class="page-breadcrumb-action wd4">
            <div class="row g-2">
                <div class="col-md-6">
                    <a href="{{ url('virtual-transaction-history') }}" class="wh-btn">View Transaction History</a>
                </div>
                <div class="col-md-6">
                    <a href="{{ url('add-edit-virtual-tour') }}" class="wh-btn">Add New Virtual Tour</a>
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

            <div class="booking-tour-section">
                <div id="managevertualtour" class="owl-carousel owl-theme">
                    @if ($tours->isEmpty())
                        <tr>
                            <td colspan="11" class="text-center">
                                No record found
                            </td>
                        </tr>
                    @elseif(!$tours->isEmpty())
                        @foreach ($tours as $val)
                            <div class="item">
                                <div class="managevertualtour-card">
                                    <div class="managevertualtour-card-head">
                                        <h3>{{ $val->name ?? '' }}</h3>
                                    </div>
                                    <div class="VirtualImage-item">
                                        <img src="{{ assets('upload/virtual-thumbnail/' . $val->thumbnail_file) }}">
                                    </div>

                                    <div class="managevertualtour-card-audio">
                                        <div class="managevertualtour-item-audio">
                                            <h3> Trial Virtual Audio File</h3>
                                            <audio width="100%" controls>
                                                <source src="{{ assets('upload/virtual-audio/' . $val->audio_file) }}"
                                                    type="audio/mpeg">
                                                Your browser does not support the audio tag.
                                            </audio>
                                        </div>
                                        <div class="managevertualtour-item-audio">
                                            <h3> Virtual Audio File</h3>

                                            <audio width="100%" controls>
                                                <source
                                                    src="{{ assets('upload/virtual-audio/' . $val->trial_audio_file) }}"
                                                    type="audio/mpeg">
                                                Your browser does not support the audio tag.
                                            </audio>
                                        </div>
                                    </div>
                                    <div class="managevertualtour-card-content">
                                        <div class="managevertualtour-card-text">
                                            {{-- <h3>{{ $val->name ?? '' }}</h3> --}}
                                            <p>{{ substr($val->description, 0, 130) ?? '' }}...</p>
                                            <div class="price-text">Price: ${{ $val->price ?? '' }}</div>
                                        </div>
                                        <div class="managevertualtour-card-action">
                                            <a class="delete-btn" href=""data-bs-toggle="modal"
                                                data-bs-target="#deletepopup"
                                                onclick='GetData("{{ $val->id }}","{{ $val->name }}")'>Delete</a>
                                            <a class="edit-btn"
                                                href="{{ url('edit-virtual-tour/' . encrypt_decrypt('encrypt', $val->id)) }}">Edit
                                                Tour</a>
                                            <a class="delete-btn" href=""data-bs-toggle="modal"
                                                data-bs-target="#Archivepopup"
                                                onclick='GetDataArchive("{{ $val->id }}","{{ $val->name }}")'>Archive</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- <div class="booking-availability-section">
                <div class="row">
                    <div class="col-md-12">
                        <div class="kikcard">
                            <div class="card-header">
                                <div class="d-flex">
                                    <div class="btn-option-info w-100">
                                        <div class="search-filter">
                                            <form action="{{ route('ManageVirtualTour') }}" method="POST">
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
                                                            <select class="form-control"name="tour_id" id="tour_id">
                                                                <option value="">Select Virtual Tour</option>
                                                                @if (!$virtual_tours->isEmpty())
                                                               
                                                                    @foreach ($virtual_tours as $tour)
                                                                        <option
                                                                            value="{{ $tour->id }}"@if ($tour->id == $tour_id) selected='selected' @else @endif>
                                                                            {{ $tour->name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <input type="text" name="daterange" value="" class="form-control form-control-solid" autocomplete="off"  placeholder="Select Date Range">
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
                                                            <a href="{{ url('manage-virtual-tour') }}" class="btn-gr"><i
                                                                    class="fa fa-refresh" aria-hidden="true"></i></a>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                        <a href="{{ route('ManageVirtualTour', ['download' => 1, 'search' => $search,'tour_id' => $tour->id,'daterange' => $date]) }}" class="btn-gr"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
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
                                                <th>Booking ID</th>
                                                <th>Transaction ID</th>
                                                <th>Name</th>
                                                <th>Virtual Tour Name</th>
                                                <th>Amount Paid</th>
                                                <th>Booking Date</th>
                                                <th>Payment Made Via</th>
                                                
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
                                                        <td>{{ $val->booking_id ?? '' }}</td>
                                                        <td>{{ $val->transaction_id ?? '' }}</td>
                                                        <td>{{ $val->Users->fullname ?? '' }}</td>
                                                        <td>{{ $val->VirtualTour->name ?? '' }}</td>
                                                        
                                                        <td>${{ $val->total_amount ?? '' }} <a class="infoprice"
                                                                data-bs-toggle="modal" href="#infoprice"
                                                                role="button"><i class="las la-info-circle"></i></a>
                                                        </td>
                                                        <td>{{ date('M d, Y', strtotime($val->booking_date)) ?? '' }}
                                                        </td>
                                                        <td>PayPal</td>
                                                        
                                                    </tr>
                                                    <?php $s_no++; ?>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                {{-- <div class="kik-table-pagination">
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
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
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
                                                <h4 id="total_amount">$23.00</h4>
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
                                <div class="request-price-text">Total Cost<span id="total_amount"></span></div>
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
                                <form action="{{ route('DeleteVirtualTour') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="" name="id" id="photo_booth_id">
                                    <button class="yesbtn"type="submit">Yes Confirm Delete</button>
                                    <button class="Cancelbtn" type="button"data-bs-dismiss="modal"
                                        aria-label="Close"onClick="window.location.reload();">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Archive popup -->
    <div class="modal kik-modal fade" id="Archivepopup" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="iot-modal-delete-form">
                        <div class="kik-modal-delete-card">
                            <div class="kik-modal-delete-icon">
                                <img src="{{ assets('assets/admin-images/archive.svg') }}">
                            </div>
                            <h3>Are you sure you want to archive?</h3>
                            <h4 id="NameArchive"></h4>
                            <div class="kik-modal-action">
                                <form action="{{ route('ArchiveVirtualTour') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="" name="id" id="virtualId">
                                    <button class="yesbtn"type="submit">Yes Confirm Archive</button>
                                    <button class="Cancelbtn" type="button"data-bs-dismiss="modal"
                                        aria-label="Close"onClick="window.location.reload();">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Select2 CSS -->
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/select2_one.min.css') }}">
    <!-- Include Select2 JS -->
    <script src="{{ assets('assets/admin-js/select2_one.min.js') }}" type="text/javascript"></script>

    <!-- Initialize Select2 -->
    <script>
        $(document).ready(function() {
            $('#tour_id').select2({
                placeholder: "Search By Virtual Tour Name",
                allowClear: true // Optional, adds a clear button
            });
        });
    </script>

    <!-------------------- Append delete Popup Jquery -------------------->
    <script>
        function GetData(IDS, Name) {
            document.getElementById("Name").innerText =
                Name;
            document.getElementById("photo_booth_id").value = IDS;
        }

        function GetDataArchive(IDS, Name) {
            document.getElementById("NameArchive").innerText =
                Name;
            document.getElementById("virtualId").value = IDS;
        }

        function GetDataPrice(price) {
            document.getElementById("Name").innerText =
                Name;
            document.getElementById("photo_booth_id").value = IDS;
        }
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