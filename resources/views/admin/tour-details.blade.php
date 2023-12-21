@extends('layouts.admin')
@section('title', 'Kikos - Tour Details')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-plugins/OwlCarousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/tour.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/OwlCarousel/owl.carousel.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#viewtours').owlCarousel({
                loop: false,
                margin: 10,
                nav: true,
                stopOnHover: false,
                dots: false,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 1
                    },
                    1000: {
                        items: 1
                    }
                }
            })
        });
    </script>
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>Manage Tour</h4>
        <div class="page-breadcrumb-action">
            <a href="#" class="wh-btn"data-bs-toggle="modal" data-bs-target="#deletepopup"
                onclick='GetData("{{ $data->id }}","{{ $data->title }}")'>Delete</a>
            <a href="{{ url('edit-tour/' . encrypt_decrypt('encrypt', $data->id)) }}" class="wh-btn">Edit Tour</a>
        </div>
    </div>
    <div class="body-main-content">
        <div class="view-tour-section">
            <div class="view-tour-content">
                <div class="row">
                    <div class="col-md-5">
                        <div id="viewtours" class="owl-carousel owl-theme">
                            <div class="item">
                                <div class="viewtours-card-media">
                                    <?php
                                    $item = \App\Models\TourAttribute::where('tour_id', $data->id)->first();
                                    ?>
                                    <img src="{{ assets('upload/tour-thumbnail/' . $item->attribute_name) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="view-tour-content">
                            <div class="view-tour-card-text">
                                <h3>{{ $data->title ?? '' }}</h3>
                                <p>{{ $data->name ?? '' }} • 8 {{ $data->duration ?? '' }}</p>
                                <div class="price-text">US${{ $data->under_10_age_price ?? '' }} –
                                    /US${{ $data->age_11_price ?? '' }}</div>
                            </div>

                            <div class="view-tour-card-text">
                                <h3>About</h3>
                                <p>{{ $data->description ?? '' }}</p>
                            </div>

                            <div class="view-tour-card-text">
                                <h3>What to bring</h3>
                                <p>Do not forget your sunscreen or camera!</p>
                            </div>

                            <div class="view-tour-card-text">
                                <h3>Cancellation Policy</h3>
                                <p>{{ $data->cancellation_policy ?? '' }}</p>
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
                                <form action="{{ route('DeleteTour') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="" name="id" id="tour_id">
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
            document.getElementById("tour_id").value = IDS;
        }
    </script>
@endsection
