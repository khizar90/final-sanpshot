@extends('layouts.base')
@section('title', 'Edit task')
@section('main', 'Accounts Management')
@section('content')
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Users List Table -->
            <div class="card">

                <div class="row text-center p-3">
                    <label class="fw-bold">
                        Add Task
                    </label>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3"></div>
                    <div class="col-md-6 mb-5">
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                {{ session()->get('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session()->has('edit'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                {{ session()->get('edit') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session()->has('delete'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                {{ session()->get('delete') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ Request::url() }}" id="addForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label class="form-label">Title</label>
                                        <input type="text" id="nameWithTitle" name="title" value="{{ $task->title }}"
                                            class="form-control" placeholder="Title" required />
                                        <span class="text-danger mt-1">
                                            @error('title')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>


                                    <div class="col">
                                        <label class="form-label">Reward Point</label>
                                        <input type="number" id="nameWithTitle" name="point" class="form-control"
                                            placeholder="400" value="{{ $task->point }}" required />
                                        <span class="text-danger mt-1">
                                            @error('point')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>

                                </div>

                                <div class="row m-1">
                                    <div class="col-md-4 col-12 mb-3 image-upload upload">
                                        <label for="file-upload" class="upload-button">
                                            <i class="fas fa-cloud-upload-alt"></i> Upload Image
                                        </label>
                                        <input id="file-upload" name="image" type="file" accept="image/*"
                                            style="display: none;">
                                    </div>
                                    <div class="col-md-8 col-12 imageContainer" id="image-column">
                                        <div class="preview-container">
                                            <img id="image-preview" src="{{ asset('Admin/Task/image/' . $task->image) }}"
                                                alt="Uploaded Image">
                                            <button type="button" id="remove-button" class="remove-button removeBtn"><i
                                                    class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mx-2">
                                    <div class="col-md-4 mb-3 col-12 video-upload upload">
                                        <label for="video-upload" class="upload-button">
                                            <i class="fas fa-cloud-upload-alt"></i> Upload Video
                                        </label>
                                        <input id="video-upload" name="video" type="file" accept="video/*"
                                            style="display: none;">
                                    </div>
                                    <div class="col-md-8 col-12 videoContainer" id="video-column">
                                        <div class="preview-container">
                                            <video id="video-preview" class="previewVedio"
                                                src="{{ asset('Admin/Task/video/' . $task->video) }}" controls></video>
                                            <button type="button" id="button-remove" class="remove-button removeBtn"><i
                                                    class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea id="" name="description" class="form-control" rows="5" placeholder="Description" required>{{ $task->description }}</textarea>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col mb-3">
                                        <label class="form-label">Guidelines</label>
                                        <textarea id="" name="guideline" class="form-control" rows="5" placeholder="Guidelines" required>{{ $task->guideline }}</textarea>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col mb-3">
                                        <label class="form-label">Video Format</label>
                                        <textarea id="" name="video_format" class="form-control" rows="5" placeholder="Video Format"
                                            required>{{ $task->format }}</textarea>
                                    </div>

                                </div>
                                <div class="col mb-3">
                                    <label class="form-label">DeadLines</label>
                                    <input type="date" id="nameWithTitle" value="{{ $task->deadline }}"
                                        name="deadline" class="form-control" placeholder="Date" required />
                                    <span class="text-danger mt-1">
                                        @error('date')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>


                                <div class="row ">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Select Question</label>
                                        <!-- Dashboards -->
                                        @php
                                            $taskQuestionIds = explode(',', $task->question);
                                        @endphp
                                        @foreach ($questions as $question)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="question[]"
                                                    value="{{ $question->id }}" {{ in_array($question->id, $taskQuestionIds) ? 'checked' : '' }} id="question-{{ $question->id }}" />
                                                <label class="form-check-label" for="question-{{ $question->id }}">
                                                    {{ $question->question }}
                                                </label>
                                            </div>
                                        @endforeach


                                    </div>

                                </div>
                                <div class="modal-footer p-0">

                                    <button type="submit" class="btn btn-primary">Upload video</button>
                                </div>
                        </form>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>
        </div>
    @endsection

    @section('script')
        <script>
            const uploadColumn = document.querySelector(".image-upload");
            const imageColumn = document.getElementById("image-column");
            const fileUpload = document.getElementById("file-upload");
            const imagePreview = document.getElementById("image-preview");
            const removeButton = document.getElementById("remove-button");

            fileUpload.addEventListener("change", function(event) {
                const selectedFile = event.target.files[0];
                if (selectedFile) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = "block";
                        removeButton.style.display = "block";
                        imageColumn.style.display = "block"; // Show the image column
                    };
                    reader.readAsDataURL(selectedFile);
                }
            });

            removeButton.addEventListener("click", function() {
                imagePreview.src = "#";
                imagePreview.style.display = "none";
                removeButton.style.display = "none";
                fileUpload.value = ""; // Reset the input
                imageColumn.style.display = "none"; // Hide the image column
            });
        </script>

        <script>
            const videoUpload = document.getElementById("video-upload");
            const videoPreview = document.getElementById("video-preview");
            const videoColumn = document.getElementById("video-column");


            videoUpload.addEventListener("change", function(event) {
                const selectedVideo = event.target.files[0];
                if (selectedVideo) {

                    console.log(selectedVideo)
                    const removeButton = document.getElementById("button-remove");


                    videoPreview.src = URL.createObjectURL(selectedVideo); // Set the src to the selected video URL
                    videoPreview.style.display = "block"; // Display the video element
                    videoColumn.style.display = "block"; // Show the video column
                    removeButton.style.display = "block"; // Show the remove button

                    removeButton.addEventListener("click", function() {
                        videoPreview.src = ""; // Clear the video source
                        videoPreview.style.display = "none";
                        videoColumn.style.display = "none";
                        removeButton.style.display = "none";
                        videoUpload.value = ""; // Reset the input
                    });
                }
            });
        </script>
    @endsection
