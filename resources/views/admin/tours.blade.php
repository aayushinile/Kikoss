@extends('layouts.admin')
@section('title', 'Kikos - Tours')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/tour.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>Manage Tour</h4>
        <div class="search-filter wd6">
            <div class="row g-1">
                <div class="col-md-3">
                    <div class="page-breadcrumb-action">
                        <a href="{{ url('add-tour') }}" class="wh-btn">Add New Tour</a>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="form-group">
                        <div class="search-form-group">
                            <input type="text" name="search" id="search" class="form-control"
                                placeholder="Search by Name">
                            <span class="search-icon"><img src="{{ assets('assets/admin-images/search-icon.svg') }}"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="body-main-content">
        <div class="manage-tour-section">
            <div class="manage-tour-content">
                <div class="row">
                    @if ($datas->isEmpty())
                        <tr>
                            <td colspan="11" class="text-center">
                                No record found
                            </td>
                        </tr>
                    @elseif(!$datas->isEmpty())
                        @foreach ($datas as $val)
                            <div class="col-md-4">
                                <div class="manage-tour-card">
                                    <div class="manage-tour-card-media">
                                        @php
                                            $FirstImage = \App\Models\TourAttribute::where('tour_id', $val->id)->first();
                                        @endphp
                                        <img src="{{ assets('/upload/tour-thumbnail/' . $FirstImage->attribute_name) }}">
                                    </div>
                                    <div class="manage-tour-card-content">
                                        <div class="manage-tour-card-text">
                                            <h3>{{ $val->title ?? '' }}</h3>
                                            <p>{{ $val->name ?? '' }} • {{ $val->duration }} Hours</p>
                                            <div class="price-text">US${{ $val->under_10_age_price }} –
                                                US${{ $val->age_11_price }}</div>
                                        </div>
                                        <div class="manage-tour-card-action">
                                            <a
                                                href="{{ url('tour-detail/' . encrypt_decrypt('encrypt', $val->id)) }}">View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="d-flex justify-content-left">
                    {{ $datas->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
    {{-- Live Search of tours --}}
    <script>
        $(document).ready(function() {

            //fetch_customer_data();
            function fetch_customer_data(query = '') {

                let _token = $("input[name='_token']").val();

                $.ajax({
                    url: '{{ url('live_tours') }}',
                    method: 'GET',
                    data: {
                        query: query,
                        _token: _token,
                    },
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        console.log(data);
                        $('.body-main-content .manage-tour-section .manage-tour-content .row').html(
                            data);
                    }
                });
            }

            $(document).on('keyup', '#search', function() {
                var query = $(this).val();
                fetch_customer_data(query);
            });
        });
    </script>
@endsection
