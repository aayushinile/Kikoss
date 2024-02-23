@extends('layouts.admin')
@section('title', 'Kikos - Tour')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/tour.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>Manage Tour</h4>
        <div class="search-filter">
            <div class="row g-1">
                <div class="col-md-12">
                    <div class="page-breadcrumb-action">
                        <a href="{{ url('tours') }}" class="wh-btn">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="body-main-content">
        <div class="addtour-section">
            <div class="create-addtour-heading">
                <h3>{{ $data ? 'Edit' : 'Add' }} Tour</h3>
            </div>
            <div class="addtour-form">
                <form action="{{ $data ? route('UpdateTour') : route('SaveTour') }}" method="POST"
                    enctype="multipart/form-data" id="add_edit_tour">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="pid" value="{{ $data->id ?? '' }}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <h4>Tour Title</h4>
                                <input type="text" class="form-control" name="title"
                                    placeholder="Enter Tour Title Here…" value="{{ $data ? $data->title : old('title') }}">
                            </div>
                            @error('title')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <h4>Tour Name</h4>
                                <input type="text" class="form-control" name="name"
                                    placeholder="Enter Tour Name Here…" value="{{ $data ? $data->name : old('name') }}">
                            </div>
                            @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                @if ($data)
                                    <input type="hidden" name="check_value" id="check_value"
                                        @if ($data->same_for_all != '') value="same_for_all" @endif>
                                @endif

                                <input type="hidden" name="same_for_all_check_data" id="same_for_all_check_data"
                                    value="{{ $data ? $data->same_for_all : old('same_for_all') }}">
                                <div class="col-md-3">

                                    <div class="form-group">
                                        <div class="kikcheckbox1">
                                            @if ($data)
                                                <input type="checkbox" name="same_for_all_check"
                                                    id="Same_For_All_Check_Data"
                                                    @if ($data->same_for_all != '') @checked(true) @endif
                                                    required>
                                                <label for="Same_For_All_Check_Data">Same for all</label>
                                            @else
                                                <input type="checkbox" name="same_for_all_check" id="same_for_all_check"
                                                    value="same_for_all" required>
                                                <label for="same_for_all_check">Same for all</label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="kikcheckbox1">
                                            @if ($data)
                                                <input type="checkbox" name="same_for_all_check" id="Individual_Check_Data"
                                                    @if ($data->same_for_all == '') @checked(true) @endif
                                                    required>
                                                <label for="Individual_Check_Data">Individual</label>
                                            @else
                                                <input type="checkbox" name="same_for_all_check" id="Individual_Check"
                                                    value="individual_check" required>
                                                <label for="Individual_Check">Individual</label>
                                            @endif
                                        </div>
                                    </div>
                                    @error('same_for_all')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        @if ($data)
                                            <div class="Individual_Field_data">
                                                <h4 for="People_Ages_11">People Ages 11+</h4>
                                                <div class="form-group-input">
                                                    <input type="text" class="form-control" name="age_11_price"
                                                        placeholder="Enter Price/Person(in $)"
                                                        value="{{ $data ? $data->age_11_price : old('age_11_price') }}">
                                                </div>
                                            </div>
                                        @else
                                            <div class="Individual_Field">
                                                <h4 for="People_Ages_11">People Ages 11+</h4>
                                                <div class="form-group-input">
                                                    <input type="text" class="form-control" name="age_11_price"
                                                        placeholder="Enter Price/Person(in $)" id="age_11_price"
                                                        value="{{ $data ? $data->age_11_price : old('age_11_price') }}">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    @error('age_11_price')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        @if ($data)
                                            <div class="Individual_Field_data">
                                                <h4 for="Senior Ages 60+">Senior Ages 60+
                                                </h4>
                                                <div class="form-group-input">
                                                    <input type="text" class="form-control" name="age_60_price"
                                                        placeholder="Enter Price/Person(in $)"
                                                        value="{{ $data ? $data->age_60_price : old('age_60_price') }}">
                                                </div>
                                            </div>
                                        @else
                                            <div class="Individual_Field">
                                                <h4 for="Senior Ages 60+">Senior Ages 60+
                                                </h4>
                                                <div class="form-group-input">
                                                    <input type="text" class="form-control" name="age_60_price"
                                                        placeholder="Enter Price/Person(in $)"
                                                        value="{{ old('age_60_price') }}" id="age_60_price">
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                    @error('age_60_price')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        @if ($data)
                                            <div class="Individual_Field_data">
                                                <h4 for="Children Ages 10 & Under">Children
                                                    Ages 10 & Under</h4>
                                                <div class="form-group-input">
                                                    <input type="text" class="form-control" name="under_10_age_price"
                                                        placeholder="Enter Price/Person(in $)"
                                                        value="{{ $data ? $data->under_10_age_price : old('under_10_age_price') }}">
                                                </div>
                                            </div>
                                        @else
                                            <div class="Individual_Field">
                                                <h4 for="Children Ages 10 & Under">Children
                                                    Ages 10 & Under</h4>
                                                <div class="form-group-input">
                                                    <input type="text" class="form-control" name="under_10_age_price"
                                                        placeholder="Enter Price/Person(in $)"
                                                        value="{{ old('under_10_age_price') }}" id="under_10_age_price">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    @error('under_10_age_price')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        @if ($data)
                                            <div class="form-group-input edit_data">
                                                <h4 for="Same for all">Same for all</h4>
                                                <input type="text" class="form-control" id="same_for_all"
                                                    name="same_for_all" placeholder="Enter Price/Person(in $)"
                                                    value="{{ $data ? $data->same_for_all : old('same_for_all') }}">

                                            </div>
                                        @else
                                            <div class="All">
                                                <div class="form-group-input">
                                                    <h4 for="SameForAll">Same for all</h4>
                                                    <input type="text" class="form-control" name="same_for_all"
                                                        placeholder="Enter Price/Person(in $)"
                                                        value="{{ old('same_for_all') }}" id="SameForAll">

                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    @error('same_for_all')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4>Tour Duration</h4>
                                        <div class="People-form-group">
                                            <input type="text" min="0" class="form-control" name="duration"
                                                placeholder="0 Hours"
                                                value="{{ $data ? $data->duration : old('duration') }}">
                                            <span>Hours</span>
                                        </div>
                                    </div>
                                    @error('duration')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4>Total People Occupancy</h4>
                                        <div class="People-form-group">
                                            <input type="text" min="0" class="form-control"
                                                name="total_people" placeholder="0"
                                                value="{{ $data ? $data->total_people : old('total_people') }}">
                                            <span>Person</span>
                                        </div>

                                    </div>
                                    @error('total_people')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>


                                {{-- <div class="col-md-4">
                                    <div class="form-group">
                                        <h4>Start Date</h4>
                                        <input type="date" class="form-control" name="start_date">
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4>End Date</h4>
                                        <input type="date" class="form-control" name="end_date">
                                    </div>
                                </div> --}}


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4>What To Bring</h4>
                                        <input type="text" class="form-control" name="what_to_bring"
                                            placeholder="What To Bring"
                                            value="{{ $data ? $data->what_to_bring : old('what_to_bring') }}">
                                    </div>
                                    @error('duration')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <h4>Short Description</h4>
                                <textarea type="text" rows="2" cols="30" class="form-control" name="short_description"
                                    placeholder="Short Description…">{{ $data ? $data->short_description : old('short_description') }}</textarea>
                            </div>
                            @error('short_description')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <h4>Description</h4>
                                <textarea type="text" rows="7" cols="80" class="form-control" name="description"
                                    placeholder="Description…">{{ $data ? $data->description : old('description') }}</textarea>
                            </div>
                            @error('description')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <h4>Cancellation Policy</h4>
                                <textarea type="text" rows="7" cols="80" class="form-control" name="cancellation_policy"
                                    placeholder="Enter Cancellation Policy…">{{ $data ? $data->cancellation_policy : old('cancellation_policy') }}</textarea>
                            </div>
                            @error('cancellation_policy')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <div class="create-review-form-group form-group">
                                <h4>Browse & Upload Tour Photos <a class="addmorefile" onclick="addImageBox()">
                                        <img src="{{ asset('assets/admin-images/add-file.svg') }}">
                                    </a>
                                </h4>
                                <div class="create-review-form-input">
                                    <div class="row" id="images_container">
                                        @if ($data)
                                            @foreach ($images as $val)
                                                <div class="col-md-3">
                                                    <div class="upload-form-group">
                                                        <div class="upload-file">
                                                            <label for="addfile1">
                                                                <div class="uploaded-media-card">
                                                                    <div class="uploaded-media">
                                                                        <img
                                                                            src="{{ assets('upload/tour-thumbnail/' . $val->attribute_name) }}">
                                                                    </div>
                                                                    <div class="uploaded-action">
                                                                        <a
                                                                            href="{{ url('delete-tour-image/' . encrypt_decrypt('encrypt', $val->id)) }}"><i
                                                                                class="las la-trash"></i></a>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-md-3">
                                                <div class="upload-form-group">
                                                    <div class="upload-file">
                                                        <input type="file" name="thumbnail[]" accept=".jpg,.jpeg,.png"
                                                            id="addfile1" class="uploadDoc addDoc" required>
                                                        <label for="addfile1">
                                                            <div class="upload-file-item">
                                                                <div class="upload-media">
                                                                    <img id="image_addfile1"
                                                                        src="{{ asset('assets/admin-images/upload-icon.svg') }}">
                                                                </div>
                                                                <div class="upload-text">
                                                                    <span>Browse & Upload File</span>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                {{-- <button class="cancelbtn" style="background-color: red" type="button"
                                    data-bs-toggle="modal" data-bs-target="#deletepopup"
                                    onclick='GetData("{{ $data->id }}","{{ $data->title }}")'>
                            Delete</button> --}}
                                <a class="cancelbtn" href="{{ url('tours') }}">cancel</a>
                                <button class="Savebtn" type="submit">{{ $data ? 'Update' : 'Save & Create Tour' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- delete popup -->
    <div class="modal kik-modal fade" id="deletepopup" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="iot-modal-delete-form">
                        <div class="kik-modal-delete-card">
                            <div class="kik-modal-delete-icon">
                                <img src="{{ assets('assets/admin-images/delete-icon.svg') }}">
                            </div>
                            <h3>Are You sure you want to delete?</h3>
                            <h4 id="Name"></h4>
                            <div class="kik-modal-action">
                                <form action="{{ route('DeleteTour') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="" name="id" id="tour_id">
                                    <button class="yesbtn" type="submit">Yes Confirm Delete</button>
                                    <button class="Cancelbtn" type="button" data-bs-dismiss="modal" aria-label="Close"
                                        onClick="window.location.reload();">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <!-------------------- Append delete Popup Jquery -------------------->
    <script>
        function GetData(IDS, Name) {
            document.getElementById("Name").innerText =
                Name;
            document.getElementById("tour_id").value = IDS;
        }
    </script>

    {{-- Jquery for Hide and Show Price condition --}}
    <script>
        $(document).ready(function() {
            val = $("#same_for_all_check_data").val();
            if (val != '') {
                $(".edit_data").show();
                $(".Individual_Field_data").hide();
                $("[name='same_for_all']").attr("required", true); // Append required field
            } else {
                $(".edit_data").hide();
                $("[name='under_10_age_price']").attr("required", true); // Append required field
                $("[name='age_60_price']").attr("required", true); // Append required field
                $("[name='age_11_price']").attr("required", true); // Append required field
            }

            $(".All").hide();
            $(".Individual_Field").hide();

            //For Save 
            $("#same_for_all_check").click(function() {
                $(".All").show();
                $(".Individual_Field").hide();
                $("#Individual_Check").prop("checked", false);
                $("#same_for_all_check").prop("checked", true);
                $("[name='under_10_age_price']").attr("required", false); // Append required field
                $("[name='age_60_price']").attr("required", false); // Append required field
                $("[name='age_11_price']").attr("required", false); // Append required field
                $("[name='same_for_all']").attr("required", true); // Append required field
                $('#age_11_price-error').hide(); // Hide the error message
                $('#age_60_price-error').hide(); // Hide the error message
                $('#under_10_age_price-error').hide(); // Hide the error message
            });

            $("#Individual_Check").click(function() {
                $(".All").hide();
                $(".Individual_Field").show();
                $("#same_for_all_check").prop("checked", false);
                $("#Individual_Check").prop("checked", true);
                $("[name='under_10_age_price']").attr("required", true); // Append required field
                $("[name='age_60_price']").attr("required", true); // Append required field
                $("[name='age_11_price']").attr("required", true); // Append required field
                $("[name='same_for_all']").attr("required", false); // Append required field
                $('#SameForAll-error').hide(); // Hide the error message
            });

            //For Edit 
            $("#Individual_Check_Data").click(function() {
                $('.error-message').hide();
                $(".edit_data").hide();
                $(".Individual_Field_data").show();
                $("#Same_For_All_Check_Data").prop("checked", false);
                $("#Individual_Check_Data").prop("checked", true);
                $("#check_value").val("Individual");
                $("[name='under_10_age_price']").attr("required", true); // Append required field
                $("[name='age_60_price']").attr("required", true); // Append required field
                $("[name='age_11_price']").attr("required", true); // Append required field
                $("[name='same_for_all']").attr("required", false); // Append required field
                $('#same_for_all-error').hide(); // Hide the error message

            });


            $("#Same_For_All_Check_Data").click(function() {
                $('.error-message').hide();
                $(".Individual_Field_data").hide();
                $(".edit_data").show();
                $("#Individual_Check_Data").prop("checked", false);
                $("#Same_For_All_Check_Data").prop("checked", true);
                $("#check_value").val("same_for_all");
                $("[name='under_10_age_price']").attr("required", false); // Append required field
                $("[name='age_60_price']").attr("required", false); // Append required field
                $("[name='age_11_price']").attr("required", false); // Append required field
                $("[name='same_for_all']").attr("required", true); // Append required field
                $('#age_11_price-error').hide(); // Hide the error message
                $('#age_60_price-error').hide(); // Hide the error message
                $('#under_10_age_price-error').hide(); // Hide the error message
            });
        });
    </script>

    {{-- Code for appending Image box --}}
    <script>
        var imgCount = 1;

        function addImageBox() {
            imgCount += 1;

            // Create a new div element and set its innerHTML
            var newImageBox = document.createElement('div');
            newImageBox.className = 'col-md-3';
            newImageBox.innerHTML = `<div class="upload-form-group">
                        <div class="upload-file">
                            <input type="file" name="thumbnail[]" accept=".jpg,.jpeg,.png"
                                id="addfile${imgCount}" class="uploadDoc addDoc">
                            <label for="addfile${imgCount}">
                                <div class="upload-file-item">
                                    <div class="upload-media">
                                        <img id="image_addfile${imgCount}"
                                            src="{{ asset('assets/admin-images/upload-icon.svg') }}">
                                    </div>
                                    <div class="upload-text">
                                        <span>Browse & Upload File</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>`;

            // Append the new div element to the #images_container
            $("#images_container").append(newImageBox);
        }
    </script>

    {{-- form validation --}}
    <script>
        $(document).ready(function() {

            $('#add_edit_tour').validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 6,
                        maxlength: 255,
                    },
                    same_for_all_check: {
                        required: true,
                    },
                    name: {
                        required: true,
                        minlength: 6,
                        maxlength: 255,
                    },
                    total_people: {
                        required: true,
                        digits: true,
                    },

                    under_10_age_price: {
                        digits: true,
                    },

                    age_11_price: {
                        digits: true,
                    },

                    age_60_price: {
                        digits: true,
                    },

                    same_for_all: {
                        digits: true,
                    },

                    what_to_bring: {
                        required: true,
                        maxlength: 255,
                    },

                    duration: {
                        required: true,
                        digits: true,
                    },

                    short_description: {
                        required: true,
                    },

                    description: {
                        required: true,
                    },

                    cancellation_policy: {
                        required: true,
                    },

                    thumbnail: {
                        required: true,
                        accept: "image/*",
                        filesize: 1024 * 1024, // 1 MB

                    },
                },
                messages: {
                    thumbnail: {
                        required: "Please select an image.",
                        accept: "Only image files are allowed.",
                        filesize: "Maximum file size is 1 MB.",
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
                    element.closest(".upload-form-group").append(error);

                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass("is-invalid");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass("is-invalid");
                },
                submitHandler: function(form) {
                    // Check file size before submitting
                    var fileSize = 0;
                    var input = $('#addfile1')[0];
                    if (input.files.length > 0) {
                        fileSize = input.files[0].size; // in bytes
                    }

                    if (fileSize > 1024 * 1024) { // 1 MB in bytes
                        // Proceed with form submission
                        toastr.error('File size must be less than 1 MB');
                        return false;

                    } else {

                        form.submit();
                        // Display an error message or perform other actions
                        // Prevent form submission
                    }
                }

            });
        });
    </script>

    {{-- Code for Changing Image box --}}
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Use event delegation to handle change events on dynamically added elements
            $("#images_container").on('change', '.uploadDoc', function(event) {
                // Your event handling code goes here
                const file = event.target.files[0];
                const imgURL = URL.createObjectURL(file);

                let label = $(event.target).closest('.upload-file').find('label');
                label.css('backgroundImage', `url("${imgURL}")`);
                label.css('backgroundPosition', 'center');
                label.css('backgroundSize', 'cover');

                var op = label.find(".upload-file-item");
                op.css('opacity', 0);
            });
        });
    </script>
@endpush
