@extends('layouts.admin')
@section('title', 'Kikos - Manage Photo-Booth')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managephoto.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managevertualtour.css') }}">


    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
@endpush
@section('content') <div class="page-breadcrumb-title-section">
        <h4>{{ $data ? 'Edit' : 'Add' }} Photo Booth</h4>
    </div>
    <div class="body-main-content">
        <div class="addVirtualtour-section">
            <div class="addVirtualtour-heading">
                <h3>Upload new tour Photos/Videos</h3>
            </div>
            <div class="addVirtualtour-form">
                <form action="{{ $data ? route('UpdatePhotoBooth') : route('SavePhotoBooth') }}" method="POST"
                    enctype="multipart/form-data" id="">
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

                        <div class="col-md-4">
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

                        <div class="col-md-6">
                            <div class="form-group">
                                <h4>Browse & Upload Photos</h4>
                                <input type="file" class="file-form-control" id="imageInput" name="image[]"
                                    accept=".png, .jpg, .jpeg" multiple @if (empty($data)) required @endif>
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
                                    id="videoInput" @if (empty($data)) required @endif multiple>
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
                        </div>
                        <div class="col-md-12">
                            <div class="create-review-form-group form-group">
                                <h4>Browse & Upload photos <a class="addmorefile" onclick="addImageBox()"><img
                                            src="{{ asset('assets/admin-images/add-file.svg') }}"></a>
                                </h4>
                                <div class="create-review-form-input">
                                    <div class="row" id="images_container">
                                        <div class="col-md-3 p-2">
                                            <div class="upload-form-group">
                                                <div class="upload-file">
                                                    <input type="file" name="image[]" accept=".jpg,.jpeg,.png"
                                                        id="addfile1" class="uploadDoc addDoc">
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


                                    </div>
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
                                        <div class="col-md-3 p-2">
                                            <div class="upload-form-group">
                                                <div class="upload-file">
                                                    <input type="file" name="video[]" accept=".mp4,.clv,.wav"
                                                        id="addvideo1" class="uploadDoc video  addDoc">
                                                    <label for="addvideo1">
                                                        <div class="upload-file-item">
                                                            <div class="upload-media">
                                                                <img id="video_addvideo1"
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


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button class="cancelbtn"type="button" onclick="window.location.reload();">cancel</button>
                                <button class="Savebtn"
                                    type="submit">{{ $data ? 'Update' : 'Save & Create Photo Booth' }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <!-- Append File name -->
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

        document.getElementById('imageInput').addEventListener('change', function(event) {
            const fileInput = event.target;

            if (fileInput.files.length > 0) {

                for (let i = 0; i < fileInput.files.length; i++) {
                    const file = fileInput.files[i];
                    const reader = new FileReader();

                    reader.onload = function(e) {

                        var data = `<div class="col-md-4">
                                            <div class="uploaded-media-card">
                                                <div class="uploaded-media">
                                                    <img src="${e.target.result}">
                                                </div>
                                                <div class="uploaded-action">
                                                    <a
                                                    onclick="$(this).closest('.col-md-4').hide()"><i
                                                            class="las la-trash"></i></a>
                                                </div>
                                            </div>
                                        </div>`;
                        $("#photo_container").html($("#photo_container").html() + data);
                    };

                    // Read the file as a data URL
                    reader.readAsDataURL(file);
                }

            }
            // renderImages()
        });
        document.getElementById('videoInput').addEventListener('change', function(event) {
            const fileInput = event.target;


            if (fileInput.files.length > 0) {

                for (let i = 0; i < fileInput.files.length; i++) {
                    const file = fileInput.files[i];
                    const reader = new FileReader();

                    reader.onload = function(e) {

                        var data = ` <div class="col-md-4 ">
                                            <div class="uploaded-media-card">
                                                <div class="uploaded-media">
                                                    <video controls width="100%" height="110px">
                                                        <source src="${e.target.result}"
                                                            type="video/mp4" />
                                                    </video>
                                                </div>
                                                <div class="uploaded-action">
                                                    <a
                                                        onclick="$(this).closest('.col-md-4').hide()"><i
                                                            class="las la-trash"></i></a>
                                                </div>
                                            </div>
                                        </div>`;
                        $("#video_container").html($("#video_container").html() + data);
                    };

                    // Read the file as a data URL
                    reader.readAsDataURL(file);
                }

            }
            // renderImages()
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Select all elements with the class "add"
            let elementsWithClass = document.querySelectorAll('.uploadDoc');

            // Add an event listener to each element
            elementsWithClass.forEach(function(element) {
                element.addEventListener('change', function(event) {
                    // Your event handling code goes here
                    const file = event.target.files[0];

                    const imgURL = URL.createObjectURL(file);

                    let label = document.querySelector(`[for="${element.getAttribute("id")}"]`);

                    if (element.classList.contains("video")) {
                        label.innerHTML = `<div class="uploaded-media-card">
                                                            <div class="uploaded-media">
                                                                <video controls width="100%" height="110px">
                                                                    <source
                                                                        src="${imgURL}"
                                                                        type="video/mp4" />
                                                                </video>
                                                            </div>
                                                            <div class="uploaded-action">
                                                                <a href="#"><i class="las la-trash"></i></a>
                                                            </div>
                                                        </div>`;

                        var op = label.querySelector(".upload-file-item");
                        op.style.display = "none";
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


        var imgCount = 1;

        function addImageBox() {
            imgCount += 1;
            $("#images_container").html($("#images_container").html() +
                `<div class="col-md-3 p-2">
                    <div class="upload-form-group">
                        <div class="upload-file">
                            <input type="file" name="image[]" accept=".jpg,.jpeg,.png"
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
                                                    <input type="file" name="video[]" accept=".mp4,.clv,.wav"
                                                        id="addvideo${viCount}" class="uploadDoc video  addDoc">
                                                    <label for="addvideo${viCount}">
                                                        <div class="upload-file-item">
                                                            <div class="upload-media">
                                                                <img id="video_addvideo${viCount}"
                                                                    src="{{ asset('assets/admin-images/upload-icon.svg') }}">
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
