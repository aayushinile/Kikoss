@extends('layouts.admin')
@section('title', 'Kikos - User Detail')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managebooking.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>Free Callback Request</h4>
    </div>
    <div class="body-main-content">


        <div class="booking-availability-section">
            <div class="row">
                <div class="col-md-12">
                    <div class="kikcard">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div class="mr-auto">
                                    <h4 class="heading-title">Free Callback Requests</h4>
                                </div>
                                <div class="btn-option-info wd8">
                                    <div class="search-filter">
                                        <form action="{{ route('CallbackRequest') }}" method="POST">
                                            @csrf
                                            <div class="row g-1">
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <a href="{{ url('tour-callback-request') }}" class="btn-gr"><i
                                                                class="fa fa-refresh" aria-hidden="true"></i></a>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <div class="search-form-group">
                                                            <input type="text" name="search"
                                                                value="{{ $search ? $search : '' }}" class="form-control"
                                                                placeholder="Search User name, Contact number">
                                                            <span class="search-icon"><img
                                                                    src="{{ assets('assets/admin-images/search-icon.svg') }}"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <select class="form-control" name="tour_id" id="tour_id">
                                                            <option value="">Select By Tour Name</option>
                                                            @if (!$tours->isEmpty())
                                                                @foreach ($tours as $tour)
                                                                    <option value="{{ $tour->id }}"
                                                                        @if ($tour->id == $tour_id) selected='selected' @endif>
                                                                        {{ $tour->name }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="date" name="date"
                                                            value="{{ $date ? $date : '' }}" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn-gr"><i class="fa fa-search"
                                                                aria-hidden="true"></i></button>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <a id="xport" onclick="exportToCSV(this)"
                                                            data-id="callback-request-table" class="btn-gr">Download
                                                            Excel</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body callback-request-table">
                            <div class="kik-table">
                                <table class="table xp-table" id="callback-request-table">
                                    <thead>
                                        <tr class="table-hd">
                                            <th>Sr No.</th>
                                            <th>Name</th>
                                            <th>Tour Name</th>
                                            <th>Contact number</th>
                                            <th>Duration</th>
                                            <th>Tour Book Date & Time</th>
                                            <th>Read Status</th>
                                            {{-- <th>Request Message</th> --}}
                                            <th>Action</th>
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
                                                    <td>{{ $val->TourName->name ?? '' }}</td>
                                                    <td>+1 {{ $val->mobile }}</td>
                                                    <td>{{ $val->TourName->duration ?? '' }} Hours</td>
                                                    <td>{{ date('d M, Y, h:i:s a', strtotime($val->preferred_time)) }}
                                                    </td>
                                                    <td>
                                                        <div class="switch-toggle">
                                                            <div class="">
                                                                <label class="toggle"
                                                                    for="myToggleClass_{{ $s_no }}">
                                                                    <input class="toggle__input myToggleClass_"
                                                                        @if ($val->status == 1) checked @endif
                                                                        name="status" data-id="{{ $val->id }}"
                                                                        type="checkbox"
                                                                        id="myToggleClass_{{ $s_no }}">
                                                                    <div class="toggle__fill"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    {{-- <td>{{ substr($val->note, 0, 30) }}<a class="infoRequestMessage"
                                                            data-bs-toggle="modal"
                                                            href="#infoRequestMessage"onclick='GetData("{{ $val->note }}")'
                                                            role="button"><i class="las la-info-circle"></i></a></td> --}}
                                                            
                                                    <td>{{ substr($val->note, 0, 30) }}<a class="infoRequestMessage"
                                                            data-bs-toggle="modal"
                                                            href="#infoRequestMessage"onclick='GetData("{{ $val->note }}")'
                                                            role="button"><i class="las la-eye"></i></a></td>
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
    <!-- Message Info -->
    <div class="modal kik-modal fade" id="infoRequestMessage" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="iot-modal-form">
                        <h3>Request Message</h3>
                        <p id="message" class="text-dark" style="font-size:17px;"></p>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                placeholder: "Search By Tour Name",
                allowClear: true // Optional, adds a clear button
            });
        });
    </script>


    <!-------------------- Append Free Callback Request -------------------->
    <script>
        function GetData(message) {
            document.getElementById("message").innerText =
                message;
        }
    </script>

    {{-- Live Search of callback request --}}
    <script>
        $(document).ready(function() {
            //fetch_callback__request_data;
            function fetch_customer_data(query = '', tour_id = '', Date = '') {
                let _token = $("input[name='_token']").val();
                $.ajax({
                    url: '{{ route('live_callbacks') }}',
                    method: 'GET',
                    data: {
                        query: query,
                        tour_id: tour_id,
                        Date: Date,
                        _token: _token,
                    },
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(data) {
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
                var Date = $(this).val();
                var query = '';
                var tour_id = '';
                fetch_customer_data(query, tour_id, Date);
            });

            $('.myToggleClass_').on('change', function() {
                var newStatus = this.checked ? '1' : '0';
                //Get Data of Read Status
                var request_id = $(this).attr("data-id"); //Get Data of Request ID
                $.ajax({
                    url: '{{ url('toggleRequestStatus') }}',
                    type: 'POST',
                    data: {
                        request_id: request_id,
                        status: newStatus,
                        _token: '{{ csrf_token() }}'
                    }, // Add CSRF token
                    success: function(response) {
                        // Update the UI or perform other actions based on the response
                        if (response.success) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message)
                        }
                    },
                    error: function(error) {
                        console.error('Error toggling user status:', error);
                    }
                });
            });
        });

        function exportToCSV(ele) {
            // Get table data
            var table = document.getElementById(ele.getAttribute("data-id"));
            var rows = table.querySelectorAll('tbody tr');

            // Create CSV content
            var csvContent = "data:text/csv;charset=utf-8,";
            csvContent += headersToCSV(table);
            csvContent += rowsToCSV(rows);

            // Create and trigger a download link
            var encodedUri = encodeURI(csvContent);
            var link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", ele.getAttribute("data-id") + '.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function headersToCSV(table) {
            var headers = [];
            var headerCols = table.querySelectorAll('thead th');
            for (var i = 0; i < headerCols.length; i++) {
                headers.push(headerCols[i].innerText);
            }
            return headers.join(',') + '\n';
        }

        function rowsToCSV(rows) {
            var csv = [];
            for (var i = 0; i < rows.length; i++) {
                var row = [];
                var cols = rows[i].querySelectorAll('td');
                for (var j = 0; j < cols.length; j++) {
                    row.push(cols[j].innerText);
                }
                csv.push(row.join(','));
            }
            return csv.join('\n');
        }
    </script>
@endsection
