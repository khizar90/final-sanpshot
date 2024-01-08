@extends('layouts.base')
@section('title', 'Videos')
@section('main', 'Accounts Management')
@section('link')
    <link rel="stylesheet" href="/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Users List Table -->
            <div class="card">
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                {{ session()->get('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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

                        <div class="row mt-4 mb-2">
                            <div class="col-md-2">
                                <div class="me-3">

                                    <div class="dataTables_length" id="DataTables_Table_0_length"><label class="fw-bold">
                                            Videos
                                            {{-- <select name="entries" id="entries" aria-controls="" class="form-select">
                                                <option value="20" {{ $perPage == 20 ? ' selected' : '' }}>20
                                                </option>
                                                <option value="50" {{ $perPage == 50 ? ' selected' : '' }}>50
                                                </option>
                                                <option value="100" {{ $perPage == 100 ? ' selected' : '' }}>
                                                    100</option>
                                                <option value="500" {{ $perPage == 500 ? ' selected' : '' }}>
                                                    500</option>
                                                <option value="1000" {{ $perPage == 1000 ? ' selected' : '' }}>
                                                    1000</option>
                                                <option value="3000" {{ $perPage == 3000 ? ' selected' : '' }}>
                                                    All</option>
                                            </select> --}}

                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div
                                    class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
                                    <div id="DataTables_Table_0_filter" class="dataTables_filter searchinput">
                                        <label class="user_search input-group input-group-merge">
                                         
                                        </label>
                                    </div>
                                    <div class="dt-buttons btn-group">
                                        <button class="btn btn-secondary add-new btn-primary mx-3" tabindex="0"
                                            aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal"
                                            data-bs-target="#addNewBus"><span><i
                                                    class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                                                    class="d-none d-sm-inline-block">Add Video</span></span></button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row mt-3">
                            <!-- Monthly Campaign State -->
                            @foreach ($videos as $video)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card vedioCard">
                                        <div class="videocover">
                                            <div class="coverimage">
                                                <img class="card-img-top" src="{{ asset('Admin/cover/' . $video->cover) }}"
                                                alt="Card image cap" />
                                            </div>
                                            <a href="{{ asset('Admin/video/' . $video->video) }}" target="_blank"
                                                class="playIcon">
                                                <i class="ti ti-player-play ti-lg"></i>

                                            </a>
                                        </div>
                                        <div
                                            class="card-body d-flex align-items-center justify-content-center justify-content-between ">
                                            <h5 class="card-title mt-3">{{ $video->title }}</h5>
                                            <div class="d-flex align-items-center">
                                                <a href="#" class="text-body edit-record" data-bs-toggle="modal"
                                                    data-bs-target="#addEditVideoModal" data-id="{{ $video->id }}"
                                                    data-title="{{ $video->title }}"
                                                    data-cover="{{ asset('Admin/cover/' . $video->cover) }}"
                                                    data-video="{{ asset('Admin/video/' . $video->video) }}">
                                                    <i class="ti ti-edit ti-sm me-2"></i>
                                                </a>
                                                <a href="javascript:;" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $video->id }}"
                                                    class="text-body delete-record"><i class="ti ti-trash ti-sm mx-2"></i>
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" data-bs-backdrop='static' id="deleteModal{{ $video->id }}"
                                    tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                        <div class="modal-content deleteModal verifymodal">
                                            <div class="modal-header">
                                                <div class="modal-title" id="modalCenterTitle">Are you sure you
                                                    want to delete
                                                    this Video?
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="body">After deleting this Video you cannot use
                                                    this Video</div>
                                            </div>
                                            <hr class="hr">

                                            <div class="container">
                                                <div class="row">
                                                    <div class="first">
                                                        <a href="" class="btn" data-bs-dismiss="modal"
                                                            style="color: #a8aaae ">Cancel</a>
                                                    </div>
                                                    <div class="second">
                                                        <a class="btn text-center"
                                                            href="{{ route('dashboard-help-delete', $video->id) }}">Delete</a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach



                        </div>


                        <div class="modal fade" data-bs-backdrop='static' id="addEditVideoModal" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="">Edit Video</h5>
                                    </div>

                                    <form action="" method="POST" id="editForm" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <label for="nameWithTitle" class="form-label">Title</label>
                                                    <input type="text" id="nameWithTitle" name="title"
                                                        class="form-control" placeholder="Title" required
                                                        value="" />
                                                    <span class="text-danger mt-1">
                                                        @error('title')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>

                                            </div>
                                            <div class="row m-1">
                                                <div class="col-md-4 col-12 mb-3 image-upload-edit upload">
                                                    <label for="file-upload-edit" class="upload-button">
                                                        <i class="fas fa-cloud-upload-alt"></i> Upload
                                                        Cover
                                                    </label>
                                                    <input id="file-upload-edit" class="file-upload-edit" name="image"
                                                        type="file" accept="image/*" style="display: none;">
                                                </div>
                                                <div class="col-md-8 col-12 imageContainer image-column-edit"
                                                    id="image-column-edit">
                                                    <div class="preview-container">
                                                        <img class="image-preview-edit" id="image-preview-edit"
                                                            src="#" alt="Uploaded Image">
                                                        <button type="button" style="display: none"
                                                            class="remove-button  removeBtn" id="remove-button-edit"><i
                                                                class="fas fa-times"></i></button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mx-2">
                                                <div class="col-md-4 mb-3 col-12 video-upload-edit upload">
                                                    <label for="video-upload-edit" class="upload-button">
                                                        <i class="fas fa-cloud-upload-alt"></i> Upload
                                                        Video
                                                    </label>
                                                    <input id="video-upload-edit" class="file-upload-edit" name="video"
                                                        type="file" accept="video/*" style="display: none;">
                                                </div>
                                                <div class="col-md-8 col-12 videoContainer" id="video-column-edit">
                                                    <div class="preview-container">
                                                        <video id="video-preview-edit" class="previewVedio"
                                                            src="#" controls></video>
                                                        <button type="button" style="display: none"
                                                            class="remove-button removeBtn" id="button-remove-edit1">
                                                            <i class="fas fa-times"></i></button>
                                                    </div>
                                                </div>
                                            </div>



                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-label-secondary"
                                                data-bs-dismiss="modal" id="closeButtonedit">
                                                Close
                                            </button>
                                            <button type="submit" class="btn btn-primary">Upload
                                                video</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>




                        <div class="modal fade" data-bs-backdrop='static' id="addNewBus" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalCenterTitle">Add Help Video</h5>

                                    </div>
                                    <form action="{{ route('dashboard-help-upload') }}" id="addForm" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <label for="nameWithTitle" class="form-label">Title</label>
                                                    <input type="text" id="nameWithTitle" name="title"
                                                        class="form-control" placeholder="Title" required />
                                                    <span class="text-danger mt-1">
                                                        @error('title')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>

                                            </div>
                                            <div class="row m-1">
                                                <div class="col-md-4 col-12 mb-3 image-upload upload">
                                                    <label for="file-upload" class="upload-button">
                                                        <i class="fas fa-cloud-upload-alt"></i> Upload Cover
                                                    </label>
                                                    <input id="file-upload" name="image" type="file"
                                                        accept="image/*" style="display: none;">
                                                </div>
                                                <div class="col-md-8 col-12 imageContainer" id="image-column"
                                                    style="display: none;">
                                                    <div class="preview-container">
                                                        <img id="image-preview" src="#" alt="Uploaded Image"
                                                            style="display: none;">
                                                        <button id="remove-button" class="remove-button removeBtn"><i
                                                                class="fas fa-times"></i></button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mx-2">
                                                <div class="col-md-4 mb-3 col-12 video-upload upload">
                                                    <label for="video-upload" class="upload-button">
                                                        <i class="fas fa-cloud-upload-alt"></i> Upload Video
                                                    </label>
                                                    <input id="video-upload" name="video" type="file"
                                                        accept="video/*" style="display: none;">
                                                </div>
                                                <div class="col-md-8 col-12 videoContainer" id="video-column"
                                                    style="display: none;">
                                                    <div class="preview-container">
                                                        <video id="video-preview" class="previewVedio" src="#"
                                                            controls></video>
                                                        <button id="button-remove" class="remove-button removeBtn"><i
                                                                class="fas fa-times"></i></button>
                                                    </div>
                                                </div>
                                            </div>



                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-label-secondary"
                                                data-bs-dismiss="modal" id="closeButtonadd">
                                                Close
                                            </button>
                                            <button type="submit" class="btn btn-primary">Upload video</button>
                                        </div>
                                    </form>



                                </div>
                            </div>
                        </div>


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

            <script>
                const addEditVideoModal = document.getElementById("addEditVideoModal");
                const editRecordLinks = document.querySelectorAll(".edit-record");

                editRecordLinks.forEach(link => {
                    link.addEventListener("click", function(event) {
                        const videoId = link.getAttribute("data-id");
                        const videoTitle = link.getAttribute("data-title");
                        const videoCover = link.getAttribute("data-cover");
                        const videoSrc = link.getAttribute("data-video");
                        console.log(videoId)
                        // Populate the modal fields with the existing data
                        document.getElementById("nameWithTitle").value = videoTitle;
                        document.getElementById("image-preview-edit").src = videoCover;
                        document.getElementById("video-preview-edit").src = videoSrc;

                        // Update form action to include video ID for editing
                        const form = addEditVideoModal.querySelector("form");
                        // form.action = `createParagraph.html?id=${id_url}`
                        let url = `{{ route('dashboard-help-update', 'toreplace') }}`;
                        url = url.replace('toreplace', videoId)
                        form.action = url;
                    });
                });
            </script>

            <script>
                const editVideoModal = document.getElementById("addEditVideoModal");
                const imageUploadEdit = editVideoModal.querySelector(".image-upload-edit");
                const imageColumnEdit = editVideoModal.querySelector("#image-column-edit");
                const fileUploadEdit = editVideoModal.querySelector(".file-upload-edit");
                const imagePreviewEdit = editVideoModal.querySelector(".image-preview-edit");
                const removeButtonEdit = editVideoModal.querySelector("#remove-button-edit");


                fileUploadEdit.addEventListener("change", function(event) {
                    
                    const selectedFile = event.target.files[0];
                    if (selectedFile) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreviewEdit.src = e.target.result;
                            imagePreviewEdit.style.display = "block";
                            removeButtonEdit.style.display = "block";
                            imageColumnEdit.style.display = "block"; // Show the image column
                        };
                        reader.readAsDataURL(selectedFile);
                    }
                });


                removeButtonEdit.addEventListener("click", function() {
                    imagePreviewEdit.src = "#";
                    imagePreviewEdit.style.display = "none";
                    removeButtonEdit.style.display = "none";
                    fileUploadEdit.value = ""; // Reset the input
                    imageColumnEdit.style.display = "none"; // Hide the image column
                });
            


                // Similar logic for handling video previews and removal for the edit modal
                const videoUploadEdit = editVideoModal.querySelector("#video-upload-edit");
                const videoPreviewEdit = editVideoModal.querySelector("#video-preview-edit");
                const videoColumnEdit = editVideoModal.querySelector("#video-column-edit");
                const buttonRemoveEdit = editVideoModal.querySelector("#button-remove-edit1");

                videoUploadEdit.addEventListener("change", function(event) {
                    const selectedVideo = event.target.files[0];
                    if (selectedVideo) {
                        videoPreviewEdit.src = URL.createObjectURL(selectedVideo);
                        videoPreviewEdit.style.display = "block";
                        videoColumnEdit.style.display = "block";
                        buttonRemoveEdit.style.display = "block";

                        buttonRemoveEdit.addEventListener("click", function() {
                            videoPreviewEdit.src = "";
                            videoPreviewEdit.style.display = "none";
                            videoColumnEdit.style.display = "none";
                            buttonRemoveEdit.style.display = "none";
                            videoUploadEdit.value = ""; // Reset the input
                        });
                    }
                });
            </script>

            <script>
                $(document).ready(function() {
                    $('#closeButtonadd').on('click', function(e) {
                        $('#addForm')[0].reset();
                        $('.preview-container').hide();
                        console.log('rdsfzdghgdvbhsfdjn')
                    });

                });
            </script>


        @endsection
