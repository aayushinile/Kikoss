@extends('layouts.admin')
@section('title', 'Kikos - Add Virtual-tour')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managevertualtour.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <style>
        .image-upload label {
            width: 100%;
            border: 1px dashed #3da1e3;
            border-radius: 5px;
            padding: 10px;
            box-shadow: 0px 4px 30px rgb(95 94 231 / 7%);
            background: #fff;
            color: #3da1e3;
            text-align: center;
            font-size: 12px;
        }

        label {
            display: inline-block;
        }
        .addupload {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }
        .body-main-content{
            overflow-x: hidden;
        }
    </style>
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>Manage Virtual Tour</h4>
        <div class="search-filter">
            <div class="row g-1">
                <div class="col-md-12">
                    <div class="page-breadcrumb-action">
                        <a href="{{ url('manage-virtual-tour') }}" class="wh-btn">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="body-main-content">
        <div class="addVirtualtour-section">
            <div class="addVirtualtour-heading">
                <h3>{{ $data ? 'Edit' : 'Add' }} Virtual Tour</h3>
            </div>
            <div class="addVirtualtour-form">
                <form action="{{ $data ? route('UpdateVirtualTour') : route('SaveVirtualTour') }}" method="POST"
                    enctype="multipart/form-data" id="add_edit_virtual_tour">
                    @csrf
                    <input type="hidden" name="pid" value="{{ $data->id ?? '' }}" id="pid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <h4>Virtual Tour Name</h4>
                                <input type="text" class="form-control" name="name"
                                    value="{{ $data ? $data->name : old('name') }}"
                                    placeholder="Enter Virtual Tour Name Here…">
                            </div>
                            @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <h4>Set Price($)</h4>
                                <div class="People-form-group">
                                    <input type="number" min="0" class="form-control" name="price"
                                        value="{{ $data ? $data->price : old('price') }}"placeholder="$0">
                                    <span>Per purchase</span>
                                </div>

                            </div>
                            @error('price')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <h4>Set Trial Audio Mins</h4>
                                <div class="People-form-group">
                                    <input type="number" min="0" class="form-control" name="minute"
                                        value="{{ $data ? $data->minute : old('minute') }}"placeholder="0">
                                    <span>Mins only!!</span>
                                </div>

                            </div>
                            @error('minute')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <h4>Tour Duration</h4>
                                <div class="People-form-group">
                                    <input type="number" min="0"class="form-control" name="duration"
                                        value="{{ $data ? $data->duration : old('duration') }}"placeholder="0">

                                </div>

                            </div>
                            @error('duration')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="container ">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="Stop-info-card">
                                        @if(!empty($data->stop_details) && count($data->stop_details) > 0)
                                        @foreach($data->stop_details as $index => $stopDetail)
                                        <div class="row stop-detail">
                                            <input type="hidden" name="stop_id[]" value="{{ $stopDetail->id ?? '' }}" id="sid">
                                            <input type="hidden" class="remove-input" name="stop_remove[]" value="">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <h4>Stop Name</h4>
                                                    <input type="text" class="form-control" name="stop[stop_name][]" value="{{ $stopDetail->stop_name }}" placeholder="Enter Name Here…">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <h4>Stop Number</h4>
                                                    <input type="text" class="form-control" name="stop[stop_num][]" value="{{ $stopDetail->stop_number }}" placeholder="Enter Name Here…">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <h4>Image Upload</h4>
                                                    @if($stopDetail->stop_image)
                                                        <label id="imageLabel_{{ $index }}" for="addfiles_{{ $index }}" class="signature-text" style="background-image: url('{{ asset('upload/virtual-stop-images/' . $stopDetail->stop_image) }}'); background-position: center; background-size: cover;">
                                                            <img src="{{ asset('upload/virtual-stop-images/' . $stopDetail->stop_image) }}" alt="Stop Image" class="img-thumbnail" style="visibility: hidden;">
                                                        </label>
                                                    @else
                                                        <div class="image-upload">
                                                            <label id="imageLabel_{{ $index }}" for="addfiles_{{ $index }}" class="signature-text">
                                                                <span><img src="{{ asset('assets/admin-images/upload-icon.svg') }}" height="20"> Upload Image</span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    <input type="file" name="stop[stop_image][{{ $index }}]" id="addfiles_{{ $index }}" class="addupload form-control" style="display: none;" onchange="updateImagePreview(event, {{ $index }})">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <h4>Audio Upload</h4>
                                                    @if($stopDetail->stop_audio)
                                                        <audio controls>
                                                            <source src="{{ asset('upload/virtual-stop-audio/' . $stopDetail->stop_audio) }}" type="audio/mpeg">
                                                            Your browser does not support the audio element.
                                                        </audio>
                                                    @else
                                                        <div class="image-upload">
                                                            <input type="file" name="stop[stop_audio][{{ $index }}]" id="addfileAudio_{{ $index }}" class="addupload form-control" onchange="updateAudioFile(event, {{ $index }})">
                                                            <label for="addfileAudio_{{ $index }}">
                                                                <div class="signature-text"> 
                                                                    <span><img src="{{ asset('assets/admin-images/upload-icon.svg') }}" height="20"> Upload Audio </span>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group" style="padding:13px;margin-top:16px;">
                                                    <a class="Remove-stop btn btn-primary rounded-circle" style="width: 25px; height: 25px; padding: 0; line-height: 25px; text-align: center;"><i class="las la-trash" style="font-size: 17px;font: weight 600px;"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        @else
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="Stop-info-card">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <h4>Stop Name</h4>
                                                            <input type="text" class="form-control" name="stop[stop_name][]" value="" placeholder="Enter Stop Name Here…">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <h4>Stop Number</h4>
                                                            <input type="text" class="form-control" name="stop[stop_num][]" value="" placeholder="Enter Stop Number Here…">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <h4>Image Upload </h4>
                                                            <div class="image-upload">
                                                                <input type="file" name="stop[stop_image][]" id="addfile" class="addupload form-control" onchange="updateImagePreview2(event)">
                                                                <label for="addfile" id="imageLabel">
                                                                    <div class="signature-text"> 
                                                                        <span><img src="{{ assets('assets/admin-images/upload-icon.svg') }}" height="20"> Upload Image</span>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <h4>Audio Upload</h4>
                                                            <div class="image-upload">
                                                                <input type="file" name="" id="addfile" class="addupload">
                                                                <label for="addfile">
                                                                    <div class="signature-text"> 
                                                                        <span><img src="{{ assets('assets/admin-images/upload-icon.svg') }}" height="20"> Upload Audio </span>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group" style="padding:13px;margin-top:16px;">
                                                            <a class="Removebtn btn btn-primary rounded-circle" style="width: 25px; height: 25px; padding: 0; line-height: 25px; text-align: center;"><i class="las la-trash" style="font-size: 17px;font: weight 600px;"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-end add-button">
                                <div class="col-md-3 text-right justify-content-end">
                                    <div class="form-group d-flex">
                                        <a class="Addbtn btn btn-primary rounded-circle" style="width: 25px; height: 25px; padding: 0; line-height: 25px; text-align: center;">
                                            <i class="las la-plus" style="font-size: 17px;font: weight 600px;"></i>
                                        </a>
                                        <p class="text-primary" style="margin-left: 10px; text-decoration:underline">Add more stops</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <h4>Short Description</h4>
                                <textarea type="text" class="form-control" rows="5" cols="60" name="short_description"
                                    placeholder="Short Description…">{{ $data ? $data->short_description : old('short_description') }}</textarea>
                            </div>
                            @error('short_description')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <h4>Description</h4>
                                <textarea type="text" class="form-control" rows="7" cols="80" name="description"
                                    placeholder="Description…">{{ $data ? $data->description : old('description') }}</textarea>
                            </div>
                            @error('description')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <h4>Cancellation Policy</h4>
                                <textarea type="text" class="form-control" rows="7" cols="80" name="cancellation_policy"
                                    placeholder="Enter Cancellation Policy…">{{ $data ? $data->cencellation_policy : old('cancellation_policy') }}</textarea>
                            </div>
                            @error('cancellation_policy')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="col-md-3">
                            <div class="create-review-form-group form-group">
                                <h4>Upload Thumbnail Photos<a class="addmorefile" href="">
                                    </a></h4>
                                <div class="create-review-form-input">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="upload-form-group">
                                                <div class="upload-file">
                                                    <input type="file" name="thumbnail" accept=".jpg,.jpeg,.png"
                                                        id="addfile1" class="uploadDoc addDoc">
                                                    <label for="addfile1"
                                                        @if ($data) style="background-image: url('{{ assets('upload/virtual-thumbnail/' . $data->thumbnail_file) }}');background-position:center;background-size:cover" @endif>
                                                        <div class="upload-file-item"
                                                            @if ($data) style="opacity: 0" @endif>
                                                            <div class="upload-media">
                                                                <img
                                                                    src="{{ assets('assets/admin-images/upload-icon.svg') }}">
                                                            </div>
                                                            <div class="upload-text">
                                                                <span>Browse & Upload File</span>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="create-review-form-group form-group">
                                <h4>Browse & Upload Trial Virtual Audio File<a class="addmorefile" href="">
                                    </a></h4>
                                <div class="create-review-form-input">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="upload-form-group">
                                                <div class="upload-file">
                                                    <input type="file" name="trial_audio_file" accept=".mp3"
                                                        id="addfile2" class="uploadDoc audio addDoc">
                                                    <label for="addfile2" style="overflow: scroll">
                                                        @if ($data && $data->trial_audio_file != '')
                                                            <audio controls>
                                                                <source
                                                                    src="{{ assets('upload/virtual-audio/' . $data->trial_audio_file) }}"
                                                                    type="audio/mpeg"> Your
                                                                browser does
                                                                not support the audio
                                                                element.
                                                            </audio>
                                                        @else
                                                            <div class="upload-file-item">
                                                                <div class="upload-media">
                                                                    <img
                                                                        src="{{ assets('assets/admin-images/upload-icon.svg') }}">
                                                                </div>
                                                                <div class="upload-text">
                                                                    <span>Browse & Upload File</span>
                                                                </div>
                                                            </div>
                                                        @endif
                        

                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="create-review-form-group form-group">
                                <h4>Browse & Upload Virtual Audio File<a class="addmorefile" href="">
                                    </a></h4>
                                <div class="create-review-form-input">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="upload-form-group">
                                                <div class="upload-file">
                                                    <input type="file" name="audio" accept=".mp3"id="addfile3"
                                                        class="uploadDoc audio addDoc">
                                                    <label for="addfile3" style="overflow: scroll">
                                                        @if ($data && $data->audio_file != '')
                                                            <audio controls>
                                                                <source
                                                                    src="{{ assets('upload/virtual-audio/' . $data->audio_file) }}"
                                                                    type="audio/mpeg"> Your
                                                                browser does
                                                                not support the audio
                                                                element.
                                                            </audio>
                                                        @else
                                                            <div class="upload-file-item">
                                                                <div class="upload-media">
                                                                    <img
                                                                        src="{{ assets('assets/admin-images/upload-icon.svg') }}">
                                                                </div>
                                                                <div class="upload-text">
                                                                    <span>Browse & Upload File</span>
                                                                </div>
                                                            </div>
                                                        @endif

                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-md-3">
                            <div class="create-review-form-group form-group">
                                <h4>Browse & Upload Virtual Audio File<a class="addmorefile" href="">

                                    </a></h4>
                                <div class="create-review-form-input">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="upload-form-group">
                                                <div class="upload-file">
                                                    <input type="file" name="audio" accept=".mp3" id="addfile2"
                                                        class="uploadDoc addDoc">
                                                    <label for="addfile1">
                                                        <div class="upload-file-item">
                                                            <div class="upload-media">
                                                                <img
                                                                    src="{{ assets('assets/admin-images/upload-icon.svg') }}">
                                                            </div>
                                                            <div class="upload-text">
                                                                <span>Browse & Upload File</span>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                              </div> --}}


                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <h4>Browse & Upload Trial Virtual Audio File</h4>
                                <input type="file" class="file-form-control" name="trial_audio_file" accept=".mp3">
                            </div>
                            @error('trial_audio_file')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            @if ($data ? $data->trial_audio_file : '')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <audio controls>
                                            <source src="{{ assets('upload/virtual-audio/' . $data->trial_audio_file) }}"
                                                type="audio/mpeg"> Your
                                            browser does
                                            not support the audio
                                            element.
                                        </audio>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <h4>Browse & Upload Virtual Audio File</h4>
                                <input type="file" class="file-form-control" name="audio" accept=".mp3">
                            </div>
                            @error('audio')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            @if ($data ? $data->audio_file : '')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <audio controls>
                                            <source src="horse.ogg" type="audio/ogg">
                                            <source src="{{ assets('upload/virtual-audio/' . $data->audio_file) }}"
                                                type="audio/mpeg"> Your
                                            browser does
                                            not support the audio
                                            element.
                                        </audio>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <h4>Upload Thumbnail Photos</h4>
                                <input type="file" class="file-form-control" name="thumbnail"
                                    accept=".png, .jpg, .jpeg, .svg">
                            </div>
                            @error('thumbnail')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            @if ($data ? $data->thumbnail_file : '')
                                <div class="col-md-4">
                                    <div class="uploaded-media-card">
                                        <div class="uploaded-media">
                                            <img src="{{ assets('upload/virtual-thumbnail/' . $data->thumbnail_file) }}">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div> --}}

                        


                        <div class="col-md-12">
                            <div class="form-group">
                                {{-- <a href="#" class="wh-btn"data-bs-toggle="modal" data-bs-target="#deletepopup"
                                    onclick='GetData("{{ $data->id }}","{{ $data->title }}")'>Delete</a> --}}
                                {{-- <button class="cancelbtn" style="background-color: red" type="button"
                                    data-bs-toggle="modal" data-bs-target="#deletepopup"
                                    onclick='GetData("{{ $data->id }}","{{ $data->title }}")'>
                                    Delete</button> --}}
                                <a class="cancelbtn"href="{{ url('manage-virtual-tour') }}">
                                    cancel</a>
                                <button
                                    class="Savebtn" type="submit">{{ $data ? 'Update' : 'Save & Create Virtual Tour' }}</button>
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
                                    <button class="yesbtn"type="submit">Yes Confirm Delete</button>
                                    <button class="Cancelbtn" type="button"data-bs-dismiss="modal"
                                        aria-label="Close"onClick="window.location.reload();">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-------------------- Append delete Popup Jquery -------------------->
    <script>
        function GetData(IDS, Name) {
            document.getElementById("Name").innerText =
                Name;
            document.getElementById("tour_id").value = IDS;
        }
    </script>

    <!-------------------- Append Image Jquery -------------------->
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Select all elements with the class "add"
            let elementsWithClass = document.querySelectorAll('.uploadDoc');
            // Add an event listener to each element
            elementsWithClass.forEach(function(element) {
                element.addEventListener('change', function(event) {
                    // Your event handling code goes here
                    const file = event.target.files[0];

                    const imgURL = URL.createObjectURL(file);

                    let label = document.querySelector(
                        `[for="${element.getAttribute("id")}"]`);
                    if (element.classList.contains("audio")) {
                        label.innerHTML = ` <audio controls>
                            <source
                                src="${imgURL}" type="audio/mpeg"> Your browser does
                                                                not support the audio
                                                                element.
                                                            </audio>>`;

                        // var op = label.querySelector(".upload-file-item");
                        // op.style.display = "none";
                    } else {
                        label.style.backgroundImage = `url("${imgURL}")`;
                        label.style.backgroundPosition = 'center';
                        label.style.backgroundSize = 'cover';

                        var op = label.querySelector(".upload-file-item");
                        op.style.opacity = 0;
                    }

                });
            });
        });
    </script>

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    var stopDetailsCount = {{ !empty($data->stop_details) ? count($data->stop_details) : 0 }};
</script>
<script>
    
    $(document).ready(function() {
        
        var uploadIconSrc = "{{ asset('assets/admin-images/upload-icon.svg') }}";

        $(".Addbtn").click(function() {
        // Calculate the index for the new row
        var rowIndex = stopDetailsCount + $(".Stop-info-card").length;
        var newDiv = '<div class="col-md-12">' +
                    '<div class="Stop-info-card">' +
                        '<div class="row">' +
                            '<div class="col-md-3">' +
                                '<div class="form-group">' +
                                    '<h4>Stop Name</h4>' +
                                    '<input type="text" class="form-control" name="stop[stop_name][' + rowIndex + ']" value="" placeholder="Enter Stop Name Here…">' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-3">' +
                                '<div class="form-group">' +
                                    '<h4>Stop Number</h4>' +
                                    '<input type="text" class="form-control" name="stop[stop_num][' + rowIndex + ']" value="" placeholder="Enter Stop Number Here…">' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-2">' +
                                '<div class="form-group">' +
                                    '<h4>Image Upload</h4>' +
                                    '<div class="image-upload">' +
                                        '<label id="imageLabel_' + rowIndex + '" for="addfileImage_' + rowIndex + '" class="signature-text">' +
                                            '<span><img src="' + uploadIconSrc + '" height="20"> Upload Image</span>' +
                                        '</label>' +
                                        '<input type="file" name="stop[stop_image][' + rowIndex + ']" id="addfileImage_' + rowIndex + '" class="addupload form-control" style="display: none;" onchange="updateImagePreview(event, ' + rowIndex + ')">' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-2">' +
                                '<div class="form-group">' +
                                    '<h4>Audio Upload</h4>' +
                                    '<div class="image-upload">' +
                                        '<input type="file" name="stop[stop_audio][' + rowIndex + ']" id="addfileAudio_' + rowIndex + '" class="addupload form-control">' +
                                        '<label for="addfileAudio_' + rowIndex + '">'+
                                            '<div class="signature-text">'+
                                                '<span><img src="' + uploadIconSrc + '" height="20"> Upload Audio </span>'+
                                            '</div>'+
                                        '</label>'+
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-2">' +
                                '<div class="form-group" style="padding:13px;margin-top:16px;">' +
                                    '<a class="Removebtn btn btn-primary rounded-circle" style="width: 25px; height: 25px; padding: 0; line-height: 25px; text-align: center;"><i class="las la-trash" style="font-size: 17px;font: weight 600px;"></i></a>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>';
                $(".row.add-button").before(newDiv); // Append the new div before the "Add more stops" button
        });


        $(document).on("click", ".Removebtn", function() {
            $(this).closest('.col-md-12').remove(); // Remove the closest .col-md-12 div when the "Remove" button is clicked
        });
    });




    $(document).ready(function() {
        $('.Remove-stop').click(function() {
            var row = $(this).closest('.stop-detail');
            var removeInput = row.find('.remove-input');
            removeInput.val('1'); // Mark the input field for removal
            row.hide(); // Optionally hide the row immediately
        });
    });


    function updateImagePreview(event, index) {
        const input = event.target;
        const label = document.getElementById(`imageLabel_${index}`);
        const file = input.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imageURL = e.target.result;
                const imgElement = label.querySelector('img');

                if (imgElement) {
                    imgElement.src = imageURL;
                    imgElement.style.visibility = 'visible'; // Make sure the image is visible
                } else {
                    // If there's no img element, create one and append it to the label
                    const newImgElement = document.createElement('img');
                    newImgElement.src = imageURL;
                    label.appendChild(newImgElement);
                }

                // Optionally, you can hide the background image of the label
                label.style.backgroundImage = 'none';
            };
            reader.readAsDataURL(file);
        }
    }

    function updateImagePreview2(event) {
        const input = event.target;
        const label = input.nextElementSibling; // Get the label next to the input
        const file = input.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imageURL = e.target.result;
                const imgElement = label.querySelector('img');

                if (imgElement) {
                    imgElement.src = imageURL;
                    imgElement.style.visibility = 'visible'; // Make sure the image is visible
                } else {
                    // If there's no img element, create one and append it to the label
                    const newImgElement = document.createElement('img');
                    newImgElement.src = imageURL;
                    label.appendChild(newImgElement);
                }

                // Optionally, you can hide the background image of the label
                label.style.backgroundImage = 'none';
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
