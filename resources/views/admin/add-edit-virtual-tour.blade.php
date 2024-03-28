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
    height: auto;
    line-height: normal;
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



        a.Remove-stop,
        a.Removebtn {
    background: var(--red);
    border: 1px solid var(--red);
    display: inline-block;
    font-size: 22px;
    color: var(--white);
    border-radius: 5px;
    text-transform: uppercase;
    font-weight: bold;
    text-align: center;
    width: 40px;
    height: 40px;
    line-height: 40px;
    cursor: pointer;
    
}

.stop-detail-head {
    background: #e8ebf5;
    padding: 15px;
    border-radius: 5px 5px 0px 0px;
}
.stop-detail-item,
.Stop-info-card1 {
    padding: 10px;
    border: 1px solid #eee;
    margin: 2px;
    background: #fdfdfd;
    border-radius: 5px;
}
.stop-detail-item .form-group,
.Stop-info-card1  .form-group {
    margin-bottom: 0;
}.stop-detail-head .form-group {
    margin-bottom: 0;
}

.stop-detail-foot {
    padding: 10px;
    margin: 2px;
    border-radius: 5px;
    text-align: right;
}a.Addbtn {
    background: var(--blue);
    border: 1px solid var(--blue);
    padding: 5px 20px;
    display: inline-block;
    font-size: 14px;
    color: var(--white);
    border-radius: 5px;
    text-transform: uppercase;
    font-weight: bold;
    text-align: center;
    cursor:pointer;
}
        .marker {
            background-color: green;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .origin {
            background-color: #00FF00;
        }

        .destination {
            background-color: #FFA500;
            
        }
        .map.modal-lg, .modal-xl {
    --bs-modal-width: 1099px;
}
.popup-style {
        color: black;
    }
    /* Add this CSS to your stylesheet */
#legend {
    margin-top: 10px;
}

.legend-item {
    margin-bottom: 5px;
}

.markers {
    display: inline-block;
    width: 13px;
    height: 12px;
    margin-right: 5px;
    border-radius: 50%;
}

.legend-origin {
    background-color: #43d60d; /* Green color for origin */
}

.legend-destination {
    background-color: #FFA500; /* Orange color for destination */
}

.map-legend {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: #75cff0;
    padding: 10px;
    border-radius: 5px;
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
                                    placeholder="Enter Virtual Tour Name Here…" maxlength="65">
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h4>Start Location</h4>
                                    <input type="hidden" class="form-control" name="start_location_lat" value="{{$data->origin_lat ?? ''}}">
                                    <input type="hidden" class="form-control" name="start_location_long" value="{{$data->origin_long ?? ''}}">
                                    <input type="text" class="form-control location-input" id="start_location_input" name="start_location" value="{{$data->origin ?? ''}}" placeholder="Enter Start Location Here…">
                                    <div class="suggestions-dropdown" style="display: none;">
                                        <ul class="suggestions-list dropdown-menu" style="position: absolute; z-index: 1000;display:block;cursor:pointer;">
                                            <!-- Suggestions will be dynamically added here -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h4>End Location</h4>
                                    <input type="hidden" class="form-control" name="end_location_lat" value="{{$data->dest_lat ?? ''}}">
                                    <input type="hidden" class="form-control" name="end_location_long" value="{{$data->dest_long ?? ''}}">
                                    <input type="text" class="form-control location-input" id="end_location_input" name="end_location" value="{{$data->destination ?? ''}}" placeholder="Enter End Location Here…">
                                    <div class="suggestions-dropdown" style="display: none;">
                                        <ul class="suggestions-list dropdown-menu" style="position: absolute; z-index: 1000;display:block;cursor:pointer;">
                                            <!-- Suggestions will be dynamically added here -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
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
                                                        id="addfile1" class="uploadDoc addDoc" onchange="checkFileSize(this)">
                                                    <label for="addfile1"
                                                        @if ($data) style="background-image: url('{{ assets('upload/virtual-thumbnail/' . $data->thumbnail_file) }}');background-position:center;background-size:cover;cursor:pointer" @else style="cursor:pointer" @endif >
                                                        <div class="upload-file-item"
                                                            @if ($data) style="opacity: 0" @endif>
                                                            <div class="upload-media">
                                                                <img
                                                                    src="{{ assets('assets/admin-images/upload-icon.svg') }}">
                                                            </div>
                                                            <div class="upload-text">
                                                                <span>Upload File</span>
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
                                <h4>Upload Trial Virtual Audio File<a class="addmorefile" href="">
                                    </a></h4>
                                <div class="create-review-form-input">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="upload-form-group">
                                                <div class="upload-file">
                                                    <input type="file" name="trial_audio_file" accept=".mp3"
                                                        id="addfile2" class="uploadDoc audio addDoc">
                                                    <label for="addfile2" style="overflow: scroll;cursor:pointer">
                                                        @if ($data && $data->trial_audio_file != '')
                                                            <audio controls>
                                                                <source
                                                                    src="{{ assets('upload/virtual-audio/' . $data->trial_audio_file) }}"
                                                                    type="audio/mpeg"> Your
                                                                
                                                                    r does
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
                                                                    <span>Upload File</span>
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
                                <h4>Upload Virtual Audio File<a class="addmorefile" href="">
                                    </a></h4>
                                <div class="create-review-form-input">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="upload-form-group">
                                                <div class="upload-file">
                                                    <input type="file" name="audio" accept=".mp3"id="addfile3"
                                                        class="uploadDoc audio addDoc">
                                                    <label for="addfile3" style="overflow: scroll;cursor:pointer">
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
                                                                    <span>Upload File</span>
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
                                <h4>Upload Virtual Audio File<a class="addmorefile" href="">

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
                                                                <span>Upload File</span>
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
                                <h4>Upload Trial Virtual Audio File</h4>
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
                                <h4>Upload Virtual Audio File</h4>
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
                        <div class="container ">
                            <div class="row">
                            <div class="form-group mt-2 mb-2">
                                <h4>Add Virtual Tour Stop Details</h4>
                                <hr class="text-dark">
                            </div>
                            <div class="col-md-12">
                            <div class="stop-detail-head">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h4>Stop Name</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h4>Stop Number</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <h4>Image Upload</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h4>Audio Upload</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <h4>Action</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                    <div class="Stop-info-card mt-2">
                                        @if(!empty($data->stop_details) && count($data->stop_details) > 0)
                                        @foreach($data->stop_details as $index => $stopDetail)
                                        <div class="row stop-detail" style="border: 1px solid #eee;padding: 10px;margin: 2px;background: #fdfdfd;border-radius: 5px">
                                            <input type="hidden" name="stop_id[]" value="{{ $stopDetail->id ?? '' }}" id="sid">
                                            <input type="hidden" class="remove-input" name="stop_remove[]" value="">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="text" class="form-control custom-stop-name-input" name="stop[stop_name][]" value="{{ $stopDetail->stop_name }}" placeholder="Enter Name Here…" style="margin-left:-13px;">
                                                    <input type="hidden" class="form-control stop-lat" name="stop[lat][]" value="{{ $stopDetail->lat ?? '' }}">
                                                    <input type="hidden" class="form-control stop-long" name="stop[long][]" value="{{ $stopDetail->long ?? '' }}">
                                                    <div class="suggestions-dropdown" style="display: none;">
                                                        <ul class="suggestions-list dropdown-menu" style="position: absolute; z-index: 1000; display: block; cursor: pointer;">
                                                            <!-- Suggestions will be dynamically added here -->
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input type="number" class="form-control" name="stop[stop_num][]" value="{{ $stopDetail->stop_number }}" placeholder="Enter Name Here…" min="1">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    @if($stopDetail->stop_image)
                                                        <label id="imageLabel_{{ $index }}" for="addfiles_{{ $index }}" class="signature-text" style="background-image: url('{{ asset('public/upload/virtual-stop-images/' . $stopDetail->stop_image) }}'); background-position: center; background-size: cover;cursor:pointer;height:100px;width:160px;">
                                                            <img src="{{ asset('upload/virtual-stop-images/' . $stopDetail->stop_image) }}" alt="Stop Image" class="img-thumbnail" style="visibility: hidden;">
                                                        </label>
                                                    @else
                                                        <div class="image-upload">
                                                            <label id="imageLabel_{{ $index }}" for="addfiles_{{ $index }}" class="signature-text" style="cursor: pointer;">
                                                                <span><img src="{{ asset('public/assets/admin-images/upload-icon.svg') }}" height="20"> Upload Image</span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                    <input type="file" name="stop[stop_image][{{ $index }}]" accept=".jpg,.jpeg,.png" id="addfiles_{{ $index }}" class="addupload form-control" style="display: none;" onchange="updateImagePreview(event, {{ $index }})">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    @if($stopDetail->stop_audio)
                                                        <audio controls style="width:184px !important;">
                                                            <source src="{{ asset('public/upload/virtual-stop-audio/' . $stopDetail->stop_audio) }}" type="audio/mpeg">
                                                            Your browser does not support the audio element.
                                                        </audio>
                                                    @else
                                                        <div class="image-upload">
                                                            <input type="file" name="stop[stop_aud][{{ $index }}]" id="addfileAudio_{{ $index }}" class="addupload form-control" accept=".mp3" onchange="updateAudioFile(event, {{ $index }})">
                                                            <label for="addfileAudio_{{ $index }}" style="cursor: pointer;">
                                                                <div class="signature-text"> 
                                                                    <span><img src="{{ asset('public/assets/admin-images/upload-icon.svg') }}" height="20"> Upload Audio </span>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group" style="padding:0;margin-top:0px;margin-left:87px;">
                                                    <a class="Remove-stop btn btn-primary " style="padding: 5px; line-height: 25px; text-align: center;"><i class="las la-trash" style="font-size: 17px;font: weight 600px;"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        @else
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="Stop-info-card" style="border: 1px solid #eee;padding: 10px;margin: 2px;background: #fdfdfd;border-radius: 5px">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="stop[stop_name][]" value="" placeholder="Enter Stop Name Here…" id="stop_name_input">
                                                        <input type="hidden" class="form-control" name="stop[lat][]" value="">
                                                        <input type="hidden" class="form-control" name="stop[long][]" value="">
                                                        <div id="suggestions-dropdown" class="dropdown" style="position: relative; display: none;">
                                                            <ul id="suggestions-list" class="dropdown-menu" style="position: absolute; z-index: 1000;display:block; cursor: pointer;">
                                                                <!-- Suggestions will be dynamically added here -->
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <input type="number" class="form-control" name="stop[stop_num][]" value="1" placeholder="Enter Stop Number Here…" min="1" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <div class="image-upload">
                                                                <input type="file" accept=".jpg,.jpeg,.png" name="stop[stop_image][]" id="addfile" class="addupload form-control" onchange="updateImagePreview2(event); checkFileSize(this)">
                                                                <label for="addfile" id="imageLabel" style="cursor: pointer;">
                                                                    <div class="signature-text"> 
                                                                        <span><img src="{{ assets('assets/admin-images/upload-icon.svg') }}" height="20"> Upload Image</span>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <div class="image-upload">
                                                                <input type="file" name="stop[stop_aud][]" id="addfile4" class="addupload" accept=".mp3">
                                                                <label for="addfile4" style="cursor: pointer;">
                                                                    <div class="signature-text"> 
                                                                        <span><img src="{{ assets('assets/admin-images/upload-icon.svg') }}" height="20"> Upload Audio </span>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                <div class="form-group" style="padding:0;margin-top:0px;margin-left:87px;">
                                                    <a class="Remove-stop btn btn-primary " style="padding: 5px; line-height: 25px; text-align: center;"><i class="las la-trash" style="font-size: 17px;font: weight 600px;"></i></a>
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
                            <div class="stop-detail-add-row">
                                <div class="row justify-content-end add-button">
                                </div>
                                <div class="stop-detail-foot">
                                    <div class="row">
                                        <div class="col-md-12 text-end">
                                            <a class="Addbtn">
                                                <i class="las la-plus" ></i>
                                                <span style="margin-left: 5px;">Add more stops</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        


                        <div class="col-md-12 d-flex">
                            <div class="form-group col-12">
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
                                    @if(!empty($data) && $data->stop_details->isNotEmpty())
                                        <a href="javascript:void(0)" id="viewMapButton" class="btn btn-primary" data-toggle="modal" data-target="#mapModal" style="font-size:15px;background: var(--blue);border:none!important;padding:8px">View Map</a>
                                    @endif
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
     <!-- Modal -->
     <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true" style="padding-right: 17px;">
    <div class="modal-dialog modal-lg map" role="document">
        <div class="modal-content">
            <div class="modal-body p-0" style="background-color: #75cff0;height:460px">
                <button type="button" class="close mapRouteCloseBtn" data-dismiss="modal" aria-label="Close" style="position: relative; top: 1px; left: 98%; background: white; border: none; outline: none;padding:7px">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="mapContainer" style="position: relative; width: 100%;">
                    <div id="map" style="width: 100%; height: 420px;"></div>
                    <div id="legend" class="map-legend">
                        <div class="legend-item">
                            <div class="markers legend-origin"></div>
                            <span>Origin</span>
                        </div>
                        <div class="legend-item">
                            <div class="markers legend-destination"></div>
                            <span>Destination</span>
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



        function checkFileSize(input) {	
			var isValid = true;
			var isUploaded = true;	
			var maxSize = 1024 * 1024; // 1MB
			var files = input.files;    
			for (var i = 0; i < files.length; i++) {
				var fileSize = files[i].size;
				var fileName = files[i].name;        
				if (fileSize > maxSize){
					toastr.error('File "' + fileName + '" exceeds the maximum allowed size of 1 MB.');
					// Clear the file input
					input.value = '';
					isValid = false;
					return false; // Prevent form submission
				}else {
					isUploaded = false;
				}
			}
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
        
        var uploadIconSrc = "{{ asset('public/assets/admin-images/upload-icon.svg') }}";
        var stopCount = $(".Stop-info-card").length;
        $(".Addbtn").click(function() {
        // Calculate the index for the new row
        var rowIndex = stopDetailsCount + $(".Stop-info-card").length;
        var stopNum = stopDetailsCount + stopCount;
        var newDiv = '<div class="col-md-12">' +
            '<div class="Stop-info-card1">' +
            '<div class="row">' +
            '<div class="col-md-3">' +
            '<div class="form-group">' +
            '<input type="text" class="form-control stop-name-input" name="stop[stop_name][]" value="" placeholder="Enter Stop Name Here…">' +
            '<input type="hidden" class="form-control stop-lat" name="stop[lat][]" value="">' +
            '<input type="hidden" class="form-control stop-long" name="stop[long][]" value="">' +
            '<div id="suggestions-dropdown" class="dropdown" style="position: relative; display: none;">' +
            '<ul id="suggestions-list" class="dropdown-menu" style="position: absolute; z-index: 1000;display:block; cursor: pointer;">' +
            '</ul>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-3">' +
            '<div class="form-group">' +
            '<input type="number" class="form-control" name="stop[stop_num][]" value="'+stopNum+'" placeholder="Enter Stop Number Here…" min="1" readonly>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-2">' +
            '<div class="form-group">' +
            '<div class="image-upload">' +
            '<label id="imageLabel_' + rowIndex + '" for="addfileImage_' + rowIndex + '" class="signature-text" style="cursor: pointer;">' +
            '<span><img src="' + uploadIconSrc + '" height="20" style="border-radius: 5px;box-shadow: 0px 4px 30px rgb(95 94 231 / 7%);"> Upload Image</span>' +
            '</label>' +
            '<input type="file" name="stop[stop_image][]" id="addfileImage_' + rowIndex + '" class="addupload form-control" style="display: none;" onchange="updateImagePreview(event, ' + rowIndex + ')">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-2">' +
            '<div class="form-group">' +
            '<div class="image-upload">' +
            '<input type="file" name="stop[stop_aud][]" id="addfileAudio_' + rowIndex + '" class="addupload form-control" accept=".mp3">' +
            '<label for="addfileAudio_' + rowIndex + '" style="cursor: pointer;">' +
            '<div class="signature-text">' +
            '<span><img src="' + uploadIconSrc + '" height="20"> Upload Audio </span>' +
            '</div>' +
            '</label>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-1">' +
            '<div class="form-group" style="margin-left:87px;">' +
            '<a class="Removebtn"><i class="las la-trash" ></i></a>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';

        $(".row.add-button").before(newDiv); // Append the new div before the "Add more stops" button
        stopCount++;
    });


        $(document).on("click", ".Removebtn", function() {
            $(this).closest('.col-md-12').remove();
            $(".Stop-info-card1").each(function(index) {
                    $(this).find('input[name="stop[stop_num][]"]').val(stopNum - 1);
                });

                stopNum = stopDetailsCount + stopCount;
                stopCount--;
        });
    });

    $(document).on('input', '.stop-name-input', function() {
        var inputText = $(this).val();
        var suggestionsDropdown = $(this).closest('.form-group').find('#suggestions-dropdown');

        // Make AJAX request to fetch suggestions only if input text is not empty
        if (inputText.trim() !== '') {
            $.ajax({
                url: '{{ route("place-suggestions") }}', // Using Laravel route helper
                method: 'GET',
                data: { query: inputText },
                success: function(response) {
                    // Handle response and display suggestions
                    console.log(response);
                    displaySuggestions(response.suggestions, suggestionsDropdown);
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(error);
                }
            });
        } else {
            suggestionsDropdown.hide();
        }
    });

    // Function to display suggestions in the dropdown
    function displaySuggestions(suggestions, dropdown) {
        console.log("Displaying suggestions:", suggestions); // Debugging statement
        var suggestionsList = dropdown.find('#suggestions-list');
        suggestionsList.empty(); // Clear previous suggestions

        // Populate dropdown with new suggestions
        $.each(suggestions, function(index, suggestion) {
            var listItem = $('<li>').addClass('dropdown-item').text(suggestion.place_name); // Only show place name
            listItem.on('click', function() {
                // Update input field with selected suggestion place name
                var formGroup = dropdown.closest('.form-group');
                formGroup.find('.stop-name-input').val(suggestion.place_name);
                formGroup.find('.stop-lat').val(suggestion.latitude); // Set latitude value
                formGroup.find('.stop-long').val(suggestion.longitude); // Set longitude value
                dropdown.hide();
            });
            suggestionsList.append(listItem);
        });

        // Show the dropdown
        dropdown.show();
    }

    // Hide the dropdown when clicking outside of it
    $(document).on('click', function(event) {
        if (!$(event.target).closest('#suggestions-dropdown').length && !$(event.target).is('.stop-name-input')) {
            $('#suggestions-dropdown').hide();
        }
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
<script>
    $(document).ready(function() {
        $('#stop_name_input').on('input', function() {
            var inputText = $(this).val();

            // Make AJAX request to fetch suggestions only if input text is not empty
            if (inputText.trim() !== '') {
                $.ajax({
                    url: '{{ route("place-suggestions") }}', // Using Laravel route helper
                    method: 'GET',
                    data: { query: inputText },
                    success: function(response) {
                        // Handle response and display suggestions
                        console.log(response);
                        displaySuggestions(response.suggestions);
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(error);
                    }
                });
            } else {
                $('#suggestions-dropdown').hide();
            }
        });

        function displaySuggestions(suggestions) {
            console.log("Displaying suggestions:", suggestions); // Debugging statement
            var suggestionsList = $('#suggestions-list');
            suggestionsList.empty(); // Clear previous suggestions

            // Populate dropdown with new suggestions
            $.each(suggestions, function(index, suggestion) {
                var listItem = $('<li>').addClass('dropdown-item').text(suggestion.place_name); // Only show place name
                listItem.on('click', function() {
                    // Update input field with selected suggestion place name
                    $('#stop_name_input').val(suggestion.place_name);
                    // Update latitude and longitude input fields with selected suggestion values
                    $('[name="stop[lat][]"]').val(suggestion.latitude);
                    $('[name="stop[long][]"]').val(suggestion.longitude);
                    $('#suggestions-dropdown').hide();
                });
                suggestionsList.append(listItem);
            });

            // Show the dropdown
            $('#suggestions-dropdown').show();
        }

        // Hide the dropdown when clicking outside of it
        $(document).on('click', function(event) {
            if (!$(event.target).closest('#suggestions-dropdown').length && !$(event.target).is('#stop_name_input')) {
                $('#suggestions-dropdown').hide();
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.location-input').on('input', function() {
            var inputText = $(this).val();
            var dropdown = $(this).closest('.form-group').find('.suggestions-dropdown');

            // Make AJAX request to fetch suggestions only if input text is not empty
            if (inputText.trim() !== '') {
                $.ajax({
                    url: '{{ route("place-suggestions") }}',
                    method: 'GET',
                    data: { query: inputText },
                    success: function(response) {
                        displaySuggestions(response.suggestions, dropdown);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                dropdown.hide();
            }
        });

        function displaySuggestions(suggestions, dropdown) {
            var suggestionsList = dropdown.find('.suggestions-list');
            suggestionsList.empty();

            $.each(suggestions, function(index, suggestion) {
                var listItem = $('<li>').addClass('dropdown-item').text(suggestion.place_name);
                listItem.on('click', function() {
                    // Update input field with selected suggestion
                    var inputField = dropdown.closest('.form-group').find('.location-input');
                    inputField.val(suggestion.place_name);
                    // Update latitude and longitude
                    dropdown.closest('.form-group').find('[name$="_lat"]').val(suggestion.latitude);
                    dropdown.closest('.form-group').find('[name$="_long"]').val(suggestion.longitude);
                    dropdown.hide();
                });
                suggestionsList.append(listItem);
            });

            dropdown.show();
        }
    });
</script>
<script>
    $(document).ready(function() {
        $('.custom-stop-name-input').on('input', function() {
            var inputText = $(this).val();
            var dropdown = $(this).closest('.form-group').find('.suggestions-dropdown');

            // Make AJAX request to fetch suggestions only if input text is not empty
            if (inputText.trim() !== '') {
                $.ajax({
                    url: '{{ route("place-suggestions") }}',
                    method: 'GET',
                    data: { query: inputText },
                    success: function(response) {
                        displaySuggestions(response.suggestions, dropdown);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                dropdown.hide();
            }
        });

        function displaySuggestions(suggestions, dropdown) {
            var suggestionsList = dropdown.find('.suggestions-list');
            suggestionsList.empty();

            $.each(suggestions, function(index, suggestion) {
                var listItem = $('<li>').addClass('dropdown-item').text(suggestion.place_name);
                listItem.on('click', function() {
                    // Update input field with selected suggestion
                    var inputField = dropdown.closest('.form-group').find('.custom-stop-name-input');
                    inputField.val(suggestion.place_name);
                    // Update latitude and longitude
                    dropdown.closest('.form-group').find('.stop-lat').val(suggestion.latitude);
                    dropdown.closest('.form-group').find('.stop-long').val(suggestion.longitude);
                    dropdown.hide();
                });
                suggestionsList.append(listItem);
            });

            dropdown.show();
        }
    });

</script>

<script src="https://cdn.jsdelivr.net/npm/@turf/turf@6.5.0/turf.min.js"></script>
<script src="https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.js"></script>
<link href="https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.css" rel="stylesheet" />

<?php if (!empty($data->stop_details)) { ?>
    <script>
    $('#mapModal').on('shown.bs.modal', function () {
        try {
            var data = <?php echo json_encode($data); ?>;
            mapboxgl.accessToken = 'pk.eyJ1IjoidXNlcnMxIiwiYSI6ImNsdGgxdnpsajAwYWcya25yamlvMHBkcGEifQ.qUy8qSuM_7LYMSgWQk215w';
            var center = calculateCenter(data.stop_details);
            var map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v11',
                center: center,
                zoom: 8.77
            });

            map.on('load', function () {
                // Add origin marker
                addMarker({
                    coordinates: {lat: <?php echo isset($data->origin_lat) ? $data->origin_lat : 0; ?>, lng: <?php echo isset($data->origin_long) ? $data->origin_long : 0; ?>},
                    color: '#43d60d'
                }, 'origin');

                // Add destination marker if provided
                <?php if (isset($data->dest_lat) && isset($data->dest_long)) : ?>
                    addMarker({
                        coordinates: {lat: <?php echo $data->dest_lat; ?>, lng: <?php echo $data->dest_long; ?>},
                        color: 'orange'
                    }, 'destination');
                <?php endif; ?>

                // Iterate through stop details and add markers
                data.stop_details.forEach(function (stop, index) {
                    addMarker({
                        coordinates: {lat: parseFloat(stop.lat), lng: parseFloat(stop.long)},
                        stop_number: index + 1,
                        stop_name: stop.stop_name // Adding stop name to the marker data
                    }, 'stop');
                });

                // Draw lines between stops
                for (var i = 0; i < data.stop_details.length - 1; i++) {
                    var from = { coordinates: { lat: parseFloat(data.stop_details[i].lat), lng: parseFloat(data.stop_details[i].long) } };
                    var to = { coordinates: { lat: parseFloat(data.stop_details[i + 1].lat), lng: parseFloat(data.stop_details[i + 1].long) } };
                    drawLine(from, to);
                }

                // Connect the last stop to the origin if destination is provided
                <?php if (isset($data->dest_lat) && isset($data->dest_long)) : ?>
                    var lastStop = data.stop_details[data.stop_details.length - 1];
                    var lastStopCoordinates = { coordinates: { lat: parseFloat(lastStop.lat), lng: parseFloat(lastStop.long) } };
                    var destinationCoordinates = { coordinates: { lat: <?php echo $data->dest_lat; ?>, lng: <?php echo $data->dest_long; ?> } };
                    drawLine(lastStopCoordinates, destinationCoordinates);
                <?php endif; ?>
            });

            function addMarker(point, type) {
                var el = document.createElement('div');
                el.className = 'marker ' + type;
                if (type === 'stop') {
                    el.textContent = point.stop_number;
                }
                el.style.backgroundColor = point.color; // Set marker color
                
                // Create a popup for stop markers
                if (type === 'stop') {
                    var popup = new mapboxgl.Popup({ offset: 25, className: 'popup-style' }).setText(point.stop_name);
                    new mapboxgl.Marker(el)
                        .setLngLat([point.coordinates.lng, point.coordinates.lat])
                        .setPopup(popup) // Add popup to the marker
                        .addTo(map);
                } else {
                    new mapboxgl.Marker(el)
                        .setLngLat([point.coordinates.lng, point.coordinates.lat])
                        .addTo(map);
                }
            }

            function drawLine(from, to) {
                var geojson = {
                    type: 'Feature',
                    properties: {},
                    geometry: {
                        type: 'LineString',
                        coordinates: [ [from.coordinates.lng, from.coordinates.lat], [to.coordinates.lng, to.coordinates.lat] ]
                    }
                };
                map.addLayer({
                    id: 'line-' + from.coordinates.lat + '-' + from.coordinates.lng + '-' + to.coordinates.lat + '-' + to.coordinates.lng,
                    type: 'line',
                    source: {
                        type: 'geojson',
                        data: geojson
                    },
                    layout: {
                        'line-cap': 'round',
                        'line-join': 'round'
                    },
                    paint: {
                        'line-color': '#051b9c',
                        'line-width': 3
                    }
                });
            }

            
            function calculateCenter(locations) {
                if (locations.length === 0) {
                    return [-158.00, 21.43]; // Default center if no locations provided
                }

                var totalLat = 0;
                var totalLng = 0;
                locations.forEach(function(location) {
                    totalLat += parseFloat(location.lat);
                    totalLng += parseFloat(location.long);
                });

                var avgLat = totalLat / locations.length;
                var avgLng = totalLng / locations.length;

                return [avgLng, avgLat];
            }
        } catch (error) {
            console.error('An error occurred:', error);
        }
    });
</script>


<?php } ?>
@endsection
