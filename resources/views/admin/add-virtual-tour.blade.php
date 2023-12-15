@extends('layouts.admin')
@section('title', 'Kikos - Add Virtual-tour')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managevertualtour.css') }}">
@endpush
@section('content')
    <div class="page-breadcrumb-title-section">
        <h4>Manage Virtual Tour</h4>
    </div>
    <div class="body-main-content">
        <div class="addVirtualtour-section">
            <div class="addVirtualtour-heading">
                <h3>Add New Virtual Tour</h3>
            </div>
            <div class="addVirtualtour-form">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <h4>Virtual Tour Name</h4>
                            <input type="text" class="form-control" name=""
                                placeholder="Enter Virtual Tour Name Here…">
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group">
                            <h4>Set Price</h4>
                            <div class="People-form-group">
                                <input type="text" class="form-control" name="" placeholder="0">
                                <span>Per purchase</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <h4>Set Trial Audio Mins</h4>
                            <div class="People-form-group">
                                <input type="text" class="form-control" name="" placeholder="0">
                                <span>Mins only!!</span>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <h4>Description</h4>
                            <textarea type="text" class="form-control" name="" placeholder="Description…"></textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <h4>Cancellation Policy</h4>
                            <textarea type="text" class="form-control" name="" placeholder="Enter Cancellation Policy…"></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <h4>Browse & Upload Virtual Audio File</h4>
                            <input type="file" class="file-form-control" name="">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <h4>Upload Thumbnail Photos</h4>
                            <input type="file" class="file-form-control" name="">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <video width="320" height="240" controls>
                                <source src="movie.mp4" type="video/mp4">
                                <source src="movie.ogg" type="video/ogg"> Your browser does not support the video tag.
                            </video>
                            <audio controls>
                                <source src="horse.ogg" type="audio/ogg">
                                <source src="horse.mp3" type="audio/mpeg"> Your browser does not support the audio element.
                            </audio>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <button class="cancelbtn">cancel</button>
                            <button class="Savebtn">Save & Create Virtual Tour</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
