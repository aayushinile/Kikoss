@extends('layouts.admin')
@section('title', 'Kikos - User Detail')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managebooking.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>Tour Callback Request</h4>
    </div>
    <div class="body-main-content">


        <div class="booking-availability-section">
            <div class="row">
                <div class="col-md-12">
                    <div class="kikcard">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div class="mr-auto">
                                    <h4 class="heading-title">Tour Callback Requests</h4>
                                </div>
                                <div class="btn-option-info wd8">
                                    <div class="search-filter">
                                        <div class="row g-1">

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="search-form-group">
                                                        <input type="text" name="Search" id="search"
                                                            class="form-control"
                                                            placeholder="Search User name, Amount & Status">
                                                        <span class="search-icon"><img
                                                                src="{{ assets('assets/admin-images/search-icon.svg') }}"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select class="form-control"id="select-id">
                                                        <option>Select Tour</option>
                                                        <option value="1">West Oahu</option>
                                                        <option value="4">Sunrise Hike</option>
                                                        <option value="3">Foodie & Farm Tour</option>
                                                        <option value="6">7 Am Hike</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="date" name="date" id="date"
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <a href="#" class="btn-gr">Download report</a>
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
                                            <th>Tour Book Date & Time</th>
                                            <th>Special Request Message</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($datas->isEmpty())
                                            <tr>
                                                <td colspan="11" class="text-center">
                                                    No record found
                                                </td>
                                            </tr>
                                        @elseif(!$datas->isEmpty())
                                            <?php $s_no = 1; ?>
                                            @foreach ($datas as $val)
                                                <tr>
                                                    <td>
                                                        <div class="sno">{{ $s_no }}</div>
                                                    </td>
                                                    <td>{{ $val->name }}</td>
                                                    <td>{{ $val->TourName->name }}</td>
                                                    <td>{{ $val->TourName->duration }} Hours</td>
                                                    <td>{{ date('d M, Y, h:i:s a', strtotime($val->preferred_time)) }}
                                                    </td>
                                                    <td>{{ $val->note }}</td>
                                                </tr>
                                                <?php $s_no++; ?>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-left">
                                {{ $datas->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{-- Live Search of callback request --}}
    <script>
        $(document).ready(function() {
            //fetch_callback__request_data;
            function fetch_customer_data(query = '', tour_id = '') {
                let _token = $("input[name='_token']").val();
                $.ajax({
                    url: '{{ url('live_callbacks') }}',
                    method: 'GET',
                    data: {
                        query: query,
                        tour_id: tour_id,
                        _token: _token,
                    },
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        //console.log(data);
                        $('tbody').html(
                            data);
                    }
                });
            }

            $(document).on('keyup', '#search', function() {
                var query = $(this).val();
                fetch_customer_data(query);
            });

            $('#select-id').change(function() {
                var tour_id = this.value;
                var query = '';
                fetch_customer_data(query, tour_id);
            });

            $("#date").on("change", function() {
                var myDate = new Date($(this).val());
                alert(myDate);
            });
        });
    </script>
@endsection
