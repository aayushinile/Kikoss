@extends('layouts.admin')
@section('title', 'Kikos - Managege Virtual-tour')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/myprofile.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>My Profile</h4>
    </div>
    <div class="body-main-content">
        <div class="myprofile-section">
            <div class="row">
                <div class="col-md-4">
                    <div class="user-side-profile">
                        <div class="side-profile-item">
                            <div class="side-profile-media">
                                @if (!empty(Auth::user()->user_profile))
                                    <img src="{{ assets('upload/profile/' . Auth::user()->user_profile) }}" alt="user">
                                @else
                                    <img src="{{ assets('assets/admin-images/admin-icon.png') }}" alt="user">
                                @endif
                            </div>
                            <div class="side-profile-text">
                                <h2>{{ $data->fullname ?? '' }}</h2>
                                <p>Administrator</p>
                            </div>
                        </div>

                        <div class="side-profile-overview-info">
                            <div class="row g-1">
                                <div class="col-md-12">
                                    <div class="side-profile-total-order">
                                        <div class="side-profile-total-icon">
                                            <img src="{{ assets('assets/admin-images/sms.svg') }}">
                                        </div>
                                        <div class="side-profile-total-content">
                                            <h2>Email Address</h2>
                                            <p>{{ $data->email ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="side-profile-total-order">
                                        <div class="side-profile-total-icon">
                                            <img src="{{ assets('assets/admin-images/call.svg') }}">
                                        </div>
                                        <div class="side-profile-total-content">
                                            <h2>Phone Number</h2>
                                            <p>{{ $data->mobile ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="myprofile-form-section">
                        <div class="myprofile-form-heading">
                            <h3>Edit Profile Details</h3>
                        </div>
                        <div class="myprofile-form">
                            <form action="{{ route('UpdateProfile') }}" method="POST"
                                enctype="multipart/form-data"id="Saveprofile">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h4>Full Name</h4>
                                            <input type="text" class="form-control" name="fullname"
                                                value="{{ $data->fullname ?? '' }}" placeholder="Enter Name">
                                        </div>
                                        @error('fullname')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h4>Email Address</h4>
                                            <input type="text" class="form-control" name="email"
                                                value="{{ $data->email ?? '' }}" placeholder="Enter Email">
                                        </div>
                                        @error('email')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h4>Phone</h4>
                                            <input type="text" class="form-control phone" name="mobile"
                                                value="{{ $data->mobile ?? '' }}" placeholder="Enter Phone">
                                        </div>
                                        @error('phone')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h4>Upload Profile</h4>
                                            <input type="file" class="file-form-control user_profile"
                                                name="user_profile"accept=".png, .jpg, .jpeg">
                                        </div>
                                        @error('user_profile')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <a class="cancelbtn" href="{{ url('home') }}">cancel</a>
                                            <button class="Savebtn"type="submit">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="myprofile-form-section">
                        <div class="myprofile-form-heading">
                            <h3>Change Password</h3>
                        </div>
                        <div class="myprofile-form">
                            <form action="{{ route('UpdatePassword') }}" method="POST" enctype="multipart/form-data"
                                id="UpdatePasswordID">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h4>Old Password</h4>
                                            <input type="password" class="form-control" name="old_password"
                                                placeholder="Enter old Password">
                                        </div>
                                        @error('old_password')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h4>New Password</h4>
                                            <input type="password" class="form-control" name="new_password"
                                                id="new_password"placeholder="Enter new Password">
                                        </div>
                                        @error('new_password')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h4>Confirm New Password</h4>
                                            <input type="password" class="form-control" name="confirm_new_password"
                                                placeholder="Enter Confirm New Password">
                                        </div>
                                        @error('confirm_new_password')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <a class="cancelbtn"href="{{ url('home') }}">cancel</a>
                                            <button class="Savebtn"type="submit">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <!-------------------- Form Validation -------------------->
    <script>
        $(document).ready(function() {
            $('.phone').mask('(999) 999-9999');
            $.validator.addMethod("phoneUS", function(phoneNumber, element) {
                phoneNumber = phoneNumber.replace(/\s+/g, "");
                return this.optional(element) || phoneNumber.length > 9 &&
                    phoneNumber.match(/^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/);
            }, "Please specify a valid US phone number");
            $('#Saveprofile').validate({
                rules: {
                    fullname: {
                        required: true,
                        minlength: 6,
                        maxlength: 255,
                    },

                    email: {
                        required: true,
                        email: true
                    },

                    mobile: {
                        required: true,
                        phoneUS: true // Use the custom phoneUS rule
                    },
                },
                //errorElement: "small",
                submitHandler: function(form) {
                    // This function will be called when the form is valid and ready to be submitted
                    form.submit();
                },
                errorElement: "span",
                errorPlacement: function(error, element) {
                    error.addClass("invalid-feedback");
                    element.closest(".form-group").append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass("is-invalid");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass("is-invalid");
                },
                submitHandler: function(form) {
                    // Check file size for each uploaded file
                    var isValid = true;
                    var isUploaded = true;
                    $('.user_profile').each(function() {
                        var fileSize = 0;
                        var input = $(this)[0];
                        if (input.files.length > 0) {
                            fileSize = input.files[0].size; // in bytes
                            if (fileSize > 1024 * 1024) { // 35 MB in bytes
                                toastr.error(
                                    'File size must be less than 1 MB for each uploaded file.'
                                );
                                isValid = false;
                                return false; // Break the loop
                            }
                        } else {
                            isUploaded = false;
                        }
                    });
                    if (isValid) {
                        form.submit();
                    } else {
                        return false;
                    }
                }
            })
            $('#UpdatePasswordID').validate({
                rules: {
                    old_password: {
                        required: true,
                    },
                    new_password: {
                        required: true,
                    },

                    confirm_new_password: {
                        required: true,
                        equalTo: "#new_password", // Ensure that confirm_new_password matches new_password
                    },
                },
                //errorElement: "small",
                submitHandler: function(form) {
                    // This function will be called when the form is valid and ready to be submitted
                    form.submit();
                },
                errorElement: "span",
                errorPlacement: function(error, element) {
                    error.addClass("invalid-feedback");
                    element.closest(".form-group").append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass("is-invalid");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass("is-invalid");
                }
            })
        });
    </script>
@endsection
