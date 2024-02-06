@extends('layouts.admin')
@section('title', 'Kikos - Add Virtual-tour')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managevertualtour.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>Manage Master</h4>
        <div class="search-filter">
            <div class="row g-1">
                <div class="col-md-12">
                    <div class="page-breadcrumb-action">
                        <a href="{{ url('add-edit-master') }}" class="wh-btn">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="body-main-content">
        <div class="addVirtualtour-section">
            <div class="addVirtualtour-heading">
                <h3>Manage Master</h3>
            </div>
            <div class="addVirtualtour-form">
                <form action="{{ route('UpdateMaster') }}" method="POST" enctype="multipart/form-data"
                    id="add_edit_master">
                    @csrf
                    <input type="hidden" name="pid" value="1" id="pid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <h4>Device Token</h4>
                                <input type="text" class="form-control" name="device_token"
                                    value="{{ $data ? $data->device_token : old('device_token') }}"
                                    placeholder="Enter Device Token">
                            </div>
                            @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <h4>Tax(%)</h4>
                                <div class="People-form-group">
                                    <input type="number" min="0" class="form-control" name="tax"
                                        value="{{ $data ? $data->price : old('tax') }}"placeholder="Enter Tax">
                                </div>

                            </div>
                            @error('price')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-12">
                            <div class="form-group">
                                <a class="cancelbtn"href="{{ url('add-edit-master') }}">
                                    cancel</a>
                                <button
                                    class="Savebtn"type="submit">{{ $data ? 'Update' : 'Save & Create Virtual Tour' }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-------------------- Form Validation -------------------->
    <script>
        $(document).ready(function() {
            val = $("#pid").val();
            if (val == '') {
                $("[name='audio']").attr("required", true); // Append required field
                $("[name='trial_audio_file']").attr("required", true); // Append required field
                $("[name='thumbnail']").attr("required", true); // Append required field
            } else {
                $("[name='audio']").attr("required", false); // Append required field
                $("[name='trial_audio_file']").attr("required", false); // Append required field
                $("[name='thumbnail']").attr("required", false); // Append required field

            }
            $('#add_edit_virtual_tour').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 6,
                        maxlength: 255,
                    },

                    price: {
                        required: true,
                        digits: true
                    },

                    minute: {
                        required: true,
                        digits: true,
                    },

                    duration: {
                        required: true,
                        digits: true,
                    },

                    description: {
                        required: true,
                    },

                    short_description: {
                        required: true,
                    },

                    cancellation_policy: {
                        required: true,
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
                    $('.uploadDoc').each(function() {
                        var fileSize = 0;
                        var input = $(this)[0];
                        if (input.files.length > 0) {
                            fileSize = input.files[0].size; // in bytes
                            if (fileSize > 1024 * 1024) { // 1 MB in bytes
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


                    // If all files are valid, proceed with form submission
                    // if (isUploaded == false) {
                    //     toastr.error(
                    //         'Please select a file before uploading.'
                    //     );
                    //     return false;
                    // }
                    if (isValid) {
                        form.submit();

                    } else {


                        return false;

                    }


                }
            })
        });
    </script>
@endsection
