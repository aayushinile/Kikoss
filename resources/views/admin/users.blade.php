@extends('layouts.admin')
@section('title', 'Kikos - Users')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/user.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>User Management</h4>
        <div class="search-filter wd4">
            <div class="row g-1">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="search-form-group">
                            <input type="text" name="Search" id="search" class="form-control"
                                placeholder="Search by Name">
                            <span class="search-icon"><img src="{{ assets('assets/admin-images/search-icon.svg') }}"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="body-main-content">
        <div class="booking-availability-section">
            <div class="row">
                <div class="col-md-12">
                    <div class="kikcard">
                        <div class="card-body">
                            <div class="kik-table">
                                <table class="table xp-table  " id="customer-table">
                                    <thead>
                                        <tr class="table-hd">
                                            <th>S.No.</th>
                                            <th>Name</th>
                                            <th>Email ID</th>
                                            <th>Contact number</th>
                                            <th>Created Date</th>
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
                                                        {{ $s_no }}
                                                    </td>
                                                    <td>
                                                        {{ $val->fullname ?? '' }}
                                                    </td>

                                                    <td>
                                                        {{ $val->email ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ $val->mobile ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ date('d M, Y, h:i:s a', strtotime($val->created_at)) }}
                                                    </td>
                                                    <td>
                                                        <div class="action-btn-info">
                                                            <a class="action-btn dropdown-toggle" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="las la-ellipsis-v"></i>
                                                            </a>
                                                            <div class="dropdown-menu">
                                                                {{-- <a class="dropdown-item view-btn" href="#"><i
                                                                        class="las la-eye"></i> Restrict</a> --}}
                                                                <a class="dropdown-item view-btn"
                                                                    href="{{ url('user-details/' . encrypt_decrypt('encrypt', $val->id)) }}"><i
                                                                        class="las la-eye"></i> View</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php $s_no++; ?>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-left">
                                    {{ $datas->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Live Search of users --}}
    <script>
        $(document).ready(function() {

            //fetch_customer_data();
            function fetch_customer_data(query = '') {

                let _token = $("input[name='_token']").val();

                $.ajax({
                    url: '{{ url('live_users') }}',
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
        });
    </script>
@endsection
