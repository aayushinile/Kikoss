@extends('layouts.admin')
@section('title', 'Kikos - Manage Photo-Booth')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managephoto.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managevertualtour.css') }}">


    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <style>
        .select2-selection--multiple:before {
            content: "";
            position: absolute;
            right: 7px;
            top: 42%;
            border-top: 5px solid #888;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
        }
    </style>
@endpush
@section('content') <div class="page-breadcrumb-title-section">
        <h4>{{ $data ? 'Edit' : 'Add' }} Photo Booth</h4>
        <div class="page-breadcrumb-action">
            <div class="row g-1">
                <div class="col-md-12">
                    <a href="{{ url('manage-photo-booth') }}" class="wh-btn">Back</a>
                </div>

            </div>
        </div>
    </div>
    <div class="body-main-content">
        <div class="addVirtualtour-section">
            <div class="addVirtualtour-heading">
                <h3>Upload new tour Photos/Videos</h3>
            </div>

            <div class="addVirtualtour-form">
                <form action="{{ $data ? route('UpdatePhotoBooth') : route('SavePhotoBooth') }}" method="POST"
                    enctype="multipart/form-data" id="add_photobooth">
                    @csrf
                    <input type="hidden" name="pid" value="{{ $data->id ?? '' }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <h4>Select Tour</h4>
                                <select class="form-control" name="tour_id" required>
                                    <option value="">Select Tour</option>
                                    @if (!$tours->isEmpty())
                                        @foreach ($tours as $tour)
                                            <option value="{{ $tour->id }}"
                                                @if ($data ? $tour->id == $data->tour_id : '') selected='selected' @else @endif>
                                                {{ $tour->name }}</option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                            @error('tour_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <h4>Title Name</h4>
                                <input type="text" class="form-control" name="title"
                                    value="{{ $data ? $data->title : old('title') }}" placeholder="Enter Title">
                            </div>
                            @error('title')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <h4>Delete Photo/video(in days)</h4>
                                <div class="People-form-group">
                                    <input type="number" class="form-control" name="delete_days" min="0"
                                        value="{{ $data ? $data->delete_days : old('delete_days') }}" placeholder="0">
                                </div>
                                @error('price')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <h4>Set Price($)</h4>
                                <div class="People-form-group">
                                    <input type="number" class="form-control" name="price" min="0"
                                        value="{{ $data ? $data->price : old('price') }}" placeholder="$0">
                                    <span>Per purchase</span>
                                </div>
                                @error('price')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <h4>Please select multiple users if the uploaded photos/videos belong to those users.</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <select class="form-control livesearch p-3" name="users[]"
                                            multiple="multiple"></select>
                                    </div>
                                    <div class="col-md-8">
                                        <ul class="tag-area">
                                            <?php
                                            if (!empty($data)) {
                                                $users = explode(',', $data->users_id);
                                            }
                                            
                                            ?>
                                            @if (!empty($data))
                                                @foreach ($users as $item)
                                                    <li class="tag">{{ UserNameBooth($item) }}<span class="cross"
                                                            data-index="0"></span></li>
                                                @endforeach
                                            @endif

                                        </ul>
                                    </div>
                                </div>
                                @error('users')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group">
                                <h4>Description</h4>
                                <textarea type="text" class="form-control" rows="7" cols="80" name="description"
                                    placeholder="Description">{{ $data ? $data->description : old('description') }}</textarea>
                            </div>
                            @error('description')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <h4>Cancellation Policy</h4>
                                <textarea type="text" class="form-control" name="cancellation_policy" rows="7" cols="80"
                                    placeholder="Enter Cancellation Policy…">{{ $data ? $data->cancellation_policy : old('cancellation_policy') }}</textarea>
                            </div>
                            @error('cancellation_policy')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- <div class="col-md-6 ">
                            <div class="form-group">
                                <h4>Browse & Upload Photos</h4>
                                <input type="file" class="file-form-control" id="imageInput" name="image[]"
                                    accept=".png, .jpg, .jpeg" multiple>
                            </div>
                            @error('image')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="uploaded-section">
                                <div class="row" id="photo_container">
                                    @foreach ($images as $val)
                                        <div class="col-md-4">
                                            <div class="uploaded-media-card">
                                                <div class="uploaded-media">
                                                    <img src="{{ assets('upload/photo-booth/' . $val->media) }}">
                                                </div>
                                                <div class="uploaded-action">
                                                    <a
                                                        href="{{ url('delete-booth-video-image/' . encrypt_decrypt('encrypt', $val->id)) }}"><i
                                                            class="las la-trash"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <h4>Browse & Upload Videos</h4>
                                <input type="file" class="file-form-control" name="video[]" accept=".mp4"
                                    id="videoInput" multiple>
                            </div>
                            @error('video')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="uploaded-section">
                                <div class="row" id="video_container">
                                    @foreach ($videos as $value)
                                        <div class="col-md-4">
                                            <div class="uploaded-media-card">
                                                <div class="uploaded-media">
                                                    <video controls width="100%" height="110px">
                                                        <source src="{{ assets('upload/video-booth/' . $value->media) }}"
                                                            type="video/mp4" />
                                                    </video>
                                                </div>
                                                <div class="uploaded-action">
                                                    <a
                                                        href="{{ url('delete-booth-video-image/' . encrypt_decrypt('encrypt', $value->id)) }}"><i
                                                            class="las la-trash"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-md-12">
                            <div class="create-review-form-group form-group">
                                <h4>Browse & Upload photos <a class="addmorefile" onclick="addImageBox()"><img
                                            src="{{ asset('assets/admin-images/add-file.svg') }}"></a>
                                </h4>
                                <div class="create-review-form-input">
                                    <div class="row" id="images_container">
                                        @if ($data)
                                            @foreach ($images as $val)
                                                <div class="col-md-3 p-2">
                                                    <div class="upload-form-group">
                                                        <div class="upload-file">
                                                            <input type="file" name="image[]" accept=".jpg,.jpeg,.png"
                                                                id="addfile1" class="uploadDoc addDoc">
                                                            <label for="addfile1">
                                                                <div class="uploaded-media-card">
                                                                    <div class="uploaded-media">
                                                                        <img
                                                                            src="{{ assets('upload/photo-booth/' . $val->media) }}">
                                                                    </div>
                                                                    <div class="uploaded-action">
                                                                        <a
                                                                            href="{{ url('delete-booth-video-image/' . encrypt_decrypt('encrypt', $val->id)) }}"><i
                                                                                class="las la-trash"></i></a>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-md-3 p-2">
                                                <div class="upload-form-group">
                                                    <div class="upload-file">
                                                        <input type="file" name="image[]" accept=".jpg,.jpeg,.png"
                                                            id="addfile" class="uploadDoc addDoc Image"required>
                                                        <label for="addfile">
                                                            <div class="upload-file-item">
                                                                <div class="upload-media">
                                                                    <img id="image_addfile"
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

                            <div class="col-md-12">
                                <div class="create-review-form-group form-group">
                                    <h4>Browse & Upload Videos <a class="addmorefile" onclick="addVideoBox()"><img
                                                src="{{ asset('assets/admin-images/add-file.svg') }}"></a>
                                    </h4>
                                    <div class="create-review-form-input">
                                        <div class="row" id="videos_container">
                                            @if ($data)
                                                @foreach ($videos as $val)
                                                    <div class="col-md-3 p-2">
                                                        <div class="upload-form-group">
                                                            <div class="upload-file">
                                                                <input type="file" name="video[]"
                                                                    accept=".mp4,.clv,.wav" id="addvideo1"
                                                                    class="uploadDoc video  addDoc">
                                                                <label for="addvideo1">

                                                                    <div class="uploaded-media-card">
                                                                        <div class="uploaded-media">
                                                                            <video controls width="100%" height="110px">
                                                                                <source
                                                                                    src="{{ asset('upload/video-booth/' . $val->media) }}"
                                                                                    type="video/mp4" />
                                                                            </video>
                                                                        </div>
                                                                        <div class="uploaded-action">
                                                                            <a
                                                                                href="{{ url('delete-booth-video-image/' . encrypt_decrypt('encrypt', $val->id)) }}"><i
                                                                                    class="las la-trash"></i></a>
                                                                        </div>
                                                                    </div>

                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="col-md-3 p-2">
                                                    <div class="upload-form-group">
                                                        <div class="upload-file">
                                                            <input type="file" name="video[]" accept=".mp4,.clv,.wav"
                                                                id="addvideo1" class="uploadDoc video  addDoc"required>
                                                            <label for="addvideo1">
                                                                <div class="upload-file-item">
                                                                    <div class="upload-media">
                                                                        <img id="video_addvideo"
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
                                    <a class="cancelbtn"href="{{ url('manage-photo-booth') }}">cancel</a>
                                    <button class="Savebtn"
                                        type="submit">{{ $data ? 'Update' : 'Save & Create Photo Booth' }}</button>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>

    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/select2.min.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <!-------------------- Form Validation -------------------->
    <script>
        $(document).ready(function() {
            $('#add_photobooth').validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 6,
                        maxlength: 255,
                    },

                    delete_days: {
                        required: true,
                        digits: true
                    },

                    users_id: {
                        required: true,
                    },

                    price: {
                        required: true,
                        digits: true,
                    },
                    tour_id: {
                        required: true,
                    },

                    description: {
                        required: true,
                    },

                    cancellation_policy: {
                        required: true,
                    },

                    image: {
                        required: true,
                    },
                    video: {
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
                    $('.Image').each(function() {
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
                    $('.video').each(function() {
                        var fileSize = 0;
                        var input = $(this)[0];
                        if (input.files.length > 0) {
                            fileSize = input.files[0].size; // in bytes
                            if (fileSize > 51200 * 51200) { // 50 MB in bytes
                                toastr.error(
                                    'File size must be less than 50 MB for each uploaded file.'
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
    <!-- Append File and listing of active user in dropdown name -->
    <script>
        $(document).ready(function() {
            $(".select2-container .selection .select2-selection .select2-search__field").addClass('form-control');
        });
        $('.livesearch').select2({
            placeholder: 'Select Users',
            ajax: {
                url: "{{ route('load-sectors') }}",
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.fullname,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });


        document.addEventListener('DOMContentLoaded', function() {
            // Use event delegation to handle change events on dynamically added elements
            $("#images_container").on('change', '.Image', function(event) {
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
            $("#videos_container").on('change', '.video', function(event) {
                const file = event.target.files[0];
                const videoURL = URL.createObjectURL(file);

                let label = $(event.target).closest('.upload-file').find('label');
                label.empty(); // Clear any existing content

                // Create a video element and set its attributes
                const videoElement = $('<video controls width="100%" height="110px"></video>');
                videoElement.append(`<source src="${videoURL}" type="video/mp4" />`);

                // Append the video element to the label
                label.append(videoElement);

                var op = label.find(".upload-file-item");
                op.css('opacity', 0);
            });

        });

        var imgCount = 1;

        function addImageBox() {
            imgCount += 1;
            $("#images_container").html($("#images_container").html() +
                `<div class="col-md-3 p-2">
                    <div class="upload-form-group">
                        <div class="upload-file">
                            <input type="file" name="image[]" accept=".jpg,.jpeg,.png" id="addfile${imgCount}"
                                class="uploadDoc addDoc Image">
                            <label for="addfile${imgCount}">
                                <div class="upload-file-item">
                                    <div class="upload-media">
                                        <img id="image_addfile${imgCount}" src="{{ asset('assets/admin-images/upload-icon.svg') }}">
                                    </div>
                                    <div class="upload-text">
                                        <span>Browse & Upload File</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>`
            );
        }
        var viCount = 1;

        function addVideoBox() {
            viCount += 1;
            $("#videos_container").html($("#videos_container").html() +
                `<div class="col-md-3 p-2">
                    <div class="upload-form-group">
                        <div class="upload-file">
                            <input type="file" name="video[]" accept=".mp4,.clv,.wav" id="addvideo${viCount}"
                                class="uploadDoc video  addDoc">
                            <label for="addvideo${viCount}">
                                <div class="upload-file-item">
                                    <div class="upload-media">
                                        <img id="video_addvideo${viCount}" src="{{ asset('assets/admin-images/upload-icon.svg') }}">
                                    </div>
                                    <div class="upload-text">
                                        <span>Browse & Upload File</span>
                                    </div>
                                </div>

                            </label>
                        </div>
                    </div>
                </div>`
            );
        }
    </script>

@endsection
