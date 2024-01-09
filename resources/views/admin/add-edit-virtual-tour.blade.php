@extends('layouts.admin')
@section('title', 'Kikos - Add Virtual-tour')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managevertualtour.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>Manage Virtual Tour</h4>
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
                    <input type="hidden" name="pid" value="{{ $data->id ?? '' }}">
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
                                <button class="cancelbtn" style="background-color: red" type="button"
                                    data-bs-toggle="modal" data-bs-target="#deletepopup"
                                    onclick='GetData("{{ $data->id }}","{{ $data->title }}")'>
                                    Delete</button>
                                <button class="cancelbtn"type=" button" onclick="window.location.reload();">
                                    cancel</button>
                                <button
                                    class="Savebtn"type="submit">{{ $data ? 'Update' : 'Save & Create Virtual Tour' }}</button>
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
                                                                    src="${imgURL}"
                                                                    type="audio/mpeg"> Your
                                                                browser does
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
@endsection
