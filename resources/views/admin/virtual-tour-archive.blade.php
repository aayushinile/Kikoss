@extends('layouts.admin')
@section('title', 'Kikos - Virtual Tour Archive')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/user.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>Virtual Tour Archive</h4>
        <div class="search-filter wd4">
            <form action="{{ route('VirtualTourArchive') }}" method="POST">
                @csrf
                <div class="row g-1">
                    <div class="col-md-11">
                        <div class="form-group">
                            <div class="search-form-group">
                                <input type="text" name="search" class="form-control"
                                    value="{{ $search ? $search : '' }}" placeholder="Search by Name">
                                <span class="search-icon"><img
                                        src="{{ assets('assets/admin-images/search-icon.svg') }}"></span>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <button type="submit" class="btn-gr"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
            </form>
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
                                            <th>Price</th>
                                            <th>Duration</th>
                                            <th>Created Date</th>
                                            <th>Restore</th>
                                            {{-- <th>Action</th> --}}
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
                                                        {{ $val->name ?? '' }}
                                                    </td>

                                                    <td>
                                                        US${{ $val->price ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ $val->duration ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ date('M d, Y, h:i:s a', strtotime($val->created_at)) }}
                                                    </td>
                                                    <td>
                                                        <label class="toggle" for="myToggle">
                                                            <input class="toggle__input myToggleClass_"
                                                                @if ($val->status == 1) checked @endif
                                                                name="" type="checkbox" id="myToggle"
                                                                data-id="{{ $val->id }}">
                                                            <div class="toggle__fill"></div>
                                                        </label>
                                                    </td>

                                                    {{-- <td>
                                                        <div class="action-btn-info">
                                                            <a class="dropdown-item view-btn"
                                                                href="{{ url('user-details/' . encrypt_decrypt('encrypt', $val->id)) }}"><i
                                                                    class="las la-eye"></i> View</a>
                                                        </div>
                                                    </td> --}}
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
    {{-- Change status of virtual tour(Archive to Active) --}}
    <script>
        $(document).ready(function() {
            //Change Status for User
            $('.myToggleClass_').on('change', function() {

                var newStatus = this.checked ? '1' : '4';

                //Get Data of Read Status
                var request_id = $(this).attr("data-id"); //Get Data of Request ID
                //alert(request_id);
                $.ajax({
                    url: '{{ url('toggleVirtualTourStatus') }}',
                    type: 'POST',
                    data: {
                        tour_id: request_id,
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
                        console.error('Error toggling tour status:', error);
                    }
                });
            });
        });
    </script>
@endsection
