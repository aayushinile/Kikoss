@extends('layouts.admin')
@section('title', 'Kikos - Users')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/user.css') }}">
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>User Management</h4>
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
                                                        <div class="action-btn-info">
                                                            <a class="action-btn dropdown-toggle" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="las la-ellipsis-v"></i>
                                                            </a>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item view-btn" href="#"><i
                                                                        class="las la-eye"></i> Restrict</a>
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
@endsection
