@extends('layouts.admin')
@section('title', 'Kikos - Tour Details')
@push('css')
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
            <a href="#" class="wh-btn">Delete</a>
            <a href="addnewtour.html" class="wh-btn">Edit Tour</a>
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
                                    <img src="{{ assets('assets/admin-images/IMG_9838.jpg') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="view-tour-content">
                            <div class="view-tour-card-text">
                                <h3>West Oahu</h3>
                                <p>For all ages! • Great for families! • 8 Hours</p>
                                <div class="price-text">US$109 – /US$149</div>
                            </div>

                            <div class="view-tour-card-text">
                                <h3>About</h3>
                                <p>KIKOS Circle Island tour takes your group on a private 120 mile trip around
                                    the scenic and beautiful island of Oahu, Hawaii. Diamond Head, Hanauma Bay,
                                    Koko Head, stop for Leonard’s malasadas, on to Halona Blowhole, Sandy’s
                                    Surfing Beach and Makapu`u Point. Then, we drive the Windward coastline to
                                    beautiful North Shore, and explore Kualoa Ranch, Pipeline Beach, Shrimp
                                    Trucks, Macnut Farms, and the classic surf town, Hale’iwa. We make several
                                    stops, and cater tours to your desires. Whales, monk seals and turtles in
                                    season! Private, comfortable van seats up to 6 passengers. Back at your
                                    hotel by 4:00pm.</p>
                            </div>

                            <div class="view-tour-card-text">
                                <h3>What to bring</h3>
                                <p>Do not forget your sunscreen or camera!</p>
                            </div>

                            <div class="view-tour-card-text">
                                <h3>Cancellation Policy</h3>
                                <p>Customers will receive a full refund or credit with 24 hours notice of
                                    cancellation. Customers will also receive a full refund or credit in case of
                                    operator cancellation due to weather or other unforeseen circumstances.
                                    Contact us by phone to cancel or inquire about a cancellation. No-shows will
                                    be charged the full price.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
