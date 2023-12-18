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
        <div class="page-breadcrumb-action">
            <a href="{{ url('add-tour') }}" class="wh-btn">Add New Tour</a>
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
                                            <a href="#">View</a>
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
@endsection
