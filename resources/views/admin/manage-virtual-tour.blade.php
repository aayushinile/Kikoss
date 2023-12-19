@extends('layouts.admin')
@section('title', 'Kikos - Managege Virtual-tour')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-plugins/OwlCarousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managevertualtour.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/OwlCarousel/owl.carousel.min.js') }}" type="text/javascript"></script>
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
                                    <div class="managevertualtour-card-media">
                                        <img src="{{ assets('upload/virtual-thumbnail/' . $val->thumbnail_file) }}"
                                            width="100%" height="200">
                                    </div>
                                    <div class="managevertualtour-card-audio">
                                        <audio width="100%" controls>
                                            <source src="{{ assets('upload/virtual-audio/' . $val->audio_file) }}"
                                                type="audio/mpeg">
                                            Your browser does not support the audio tag.
                                        </audio>
                                    </div>
                                    <div class="managevertualtour-card-content">
                                        <div class="managevertualtour-card-text">
                                            <h3>{{ $val->name ?? '' }}</h3>
                                            <p>{{ $val->description ?? '' }}</p>
                                            <div class="price-text">Price: ${{ $val->price ?? '' }}</div>
                                        </div>
                                        <div class="managevertualtour-card-action">
                                            <a class="delete-btn" href=""data-bs-toggle="modal"
                                                data-bs-target="#deletepopup"
                                                onclick='GetData("{{ $val->id }}","{{ $val->name }}")'>Delete</a>
                                            <a class="edit-btn"
                                                href="{{ url('edit-virtual-tour/' . encrypt_decrypt('encrypt', $val->id)) }}">Edit
                                                Tour</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="booking-availability-section">
                <div class="row">
                    <div class="col-md-12">
                        <div class="kikcard">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <div class="mr-auto">
                                        <h4 class="heading-title">Manage Virtual Tour</h4>
                                    </div>
                                    <div class="btn-option-info wd7">
                                        <div class="search-filter">
                                            <div class="row g-1">

                                                <div class="col-md-6">
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
                                                <th>Virtual Tour Name</th>
                                                <th>Amount Paid</th>
                                                <th>Amount Recieved On</th>
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
                                                    <tr>
                                                        <td>
                                                            <div class="sno">{{ $s_no }}</div>
                                                        </td>
                                                        <td>{{ $val->Users->fullname ?? '' }}</td>
                                                        <td>{{ $val->Tour->title ?? '' }}</td>
                                                        <td>${{ $val->total_amount ?? '' }} <a class="infoprice"
                                                                data-bs-toggle="modal" href="#infoprice" role="button"><i
                                                                    class="las la-info-circle"></i></a></td>
                                                        <td>{{ date('Y-m-d', strtotime($val->booking_date)) ?? '' }}</td>
                                                        <td>PayPal</td>
                                                        <td>76375873874</td>
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
                            <h3>Are you sure? want to delete</h3>
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
    <!-------------------- Append delete Popup Jquery -------------------->
    <script>
        function GetData(IDS, Name) {
            document.getElementById("Name").innerText =
                Name;
            document.getElementById("photo_booth_id").value = IDS;
        }
    </script>
@endsection
