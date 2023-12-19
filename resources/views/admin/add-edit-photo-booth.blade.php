@extends('layouts.admin')
@section('title', 'Kikos - Manage Photo-Booth')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/managephoto.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
@endpush
@section('content') <div class="page-breadcrumb-title-section">
        <h4>Add Photo Booth</h4>
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
                                            <option
                                                value="{{ $tour->id }}" @if ($data ? $tour->id == $data->tour_id) selected='selected' @else @endif>
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
                                <h4>Set Price</h4>
                                <div class="People-form-group">
                                    <input type="number" class="form-control" name="price"
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
                                            $users = explode(',', $data->users_id);
                                            
                                            ?>
                                            @foreach ($users as $item)
                                                <li class="tag">{{ UserNameBooth($item) }}<span class="cross"
                                                        data-index="0"></span></li>
                                            @endforeach

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
                                <input type="file" class="file-form-control" name="image[]" accept=".png, .jpg, .jpeg"
                                    multiple>
                            </div>
                            @error('image')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <h4>Browse & Upload Videos</h4>
                                <input type="file" class="file-form-control" name="video[]" accept=".mp4" multiple>
                            </div>
                            @error('video')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <button class="cancelbtn">cancel</button>
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
            placeholder: 'Select tags',
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
    </script>
@endsection
