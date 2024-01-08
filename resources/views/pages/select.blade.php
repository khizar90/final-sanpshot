
@extends('layouts.base')
@section('title', 'Add task')
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
                <div class="row text-center p-3">
                    <label class="fw-bold">
                        Add Task


                    </label>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3"></div>
                    <div class="col-md-6 mb-5">
                        <form action="{{ route('dashboard-upload-video') }}" id="addForm" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="nameWithTitle" class="form-label">Title</label>
                                        <input type="text" id="nameWithTitle" name="title" class="form-control"
                                            placeholder="Title" required />
                                        <span class="text-danger mt-1">
                                            @error('title')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>


                                    <div class="col">
                                        <label for="nameWithTitle" class="form-label">Reward Point</label>
                                        <input type="number" id="nameWithTitle" name="point" class="form-control"
                                            placeholder="400" required />
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
                                    <div class="col-md-8 col-12 imageContainer" id="image-column" style="display: none;">
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
                                        <input id="video-upload" name="video" type="file" accept="video/*"
                                            style="display: none;">
                                    </div>
                                    <div class="col-md-8 col-12 videoContainer" id="video-column" style="display: none;">
                                        <div class="preview-container">
                                            <video id="video-preview" class="previewVedio" src="#" controls></video>
                                            <button id="button-remove" class="remove-button removeBtn"><i
                                                    class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="nameWithTitle" class="form-label">Description</label>
                                        <textarea id="" name="description" class="form-control" rows="5" placeholder="Description" required></textarea>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="nameWithTitle" class="form-label">Guidelines</label>
                                        <textarea id="" name="guideline" class="form-control" rows="5" placeholder="Guidelines" required></textarea>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="nameWithTitle" class="form-label">Video Format</label>
                                        <textarea id="" name="video_format" class="form-control" rows="5" placeholder="Video Format" required></textarea>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="nameWithTitle" class="form-label">DeadLines</label>
                                        <textarea id="" name="deadline" class="form-control" rows="5" placeholder="Deadlines" required></textarea>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="nameWithTitle" class="form-label">Select Question</label>
                                        <div class="col-md-12 select2-primary">
                                            <select class="form-select" id="multiple-select-field" data-placeholder="Choose anything" multiple>
                                                <option>Christmas Island</option>
                                                <option>South Sudan</option>
                                                <option>Jamaica</option>
                                                <option>Kenya</option>
                                                <option>French Guiana</option>
                                                <option>Mayotta</option>
                                                <option>Liechtenstein</option>
                                                <option>Denmark</option>
                                                <option>Eritrea</option>
                                                <option>Gibraltar</option>
                                                <option>Saint Helena, Ascension and Tristan da Cunha</option>
                                                <option>Haiti</option>
                                                <option>Namibia</option>
                                                <option>South Georgia and the South Sandwich Islands</option>
                                                <option>Vietnam</option>
                                                <option>Yemen</option>
                                                <option>Philippines</option>
                                                <option>Benin</option>
                                                <option>Czech Republic</option>
                                                <option>Russia</option>
                                            </select>
                                        </div>
                                    </div>
                                       
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
            <div class="card mb-4">
                <h5 class="card-header">Multi Column with Form Separator</h5>
                <form class="card-body">
                  <h6>1. Account Details</h6>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label" for="multicol-username">Username</label>
                      <input type="text" id="multicol-username" class="form-control" placeholder="john.doe" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" for="multicol-email">Email</label>
                      <div class="input-group input-group-merge">
                        <input
                          type="text"
                          id="multicol-email"
                          class="form-control"
                          placeholder="john.doe"
                          aria-label="john.doe"
                          aria-describedby="multicol-email2" />
                        <span class="input-group-text" id="multicol-email2">@example.com</span>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-password-toggle">
                        <label class="form-label" for="multicol-password">Password</label>
                        <div class="input-group input-group-merge">
                          <input
                            type="password"
                            id="multicol-password"
                            class="form-control"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="multicol-password2" />
                          <span class="input-group-text cursor-pointer" id="multicol-password2"
                            ><i class="ti ti-eye-off"></i
                          ></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-password-toggle">
                        <label class="form-label" for="multicol-confirm-password">Confirm Password</label>
                        <div class="input-group input-group-merge">
                          <input
                            type="password"
                            id="multicol-confirm-password"
                            class="form-control"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="multicol-confirm-password2" />
                          <span class="input-group-text cursor-pointer" id="multicol-confirm-password2"
                            ><i class="ti ti-eye-off"></i
                          ></span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <hr class="my-4 mx-n4" />
                  <h6>2. Personal Info</h6>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label" for="multicol-first-name">First Name</label>
                      <input type="text" id="multicol-first-name" class="form-control" placeholder="John" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" for="multicol-last-name">Last Name</label>
                      <input type="text" id="multicol-last-name" class="form-control" placeholder="Doe" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" for="multicol-country">Country</label>
                      <select id="multicol-country" class="select2 form-select" data-allow-clear="true">
                        <option value="">Select</option>
                        <option value="Australia">Australia</option>
                        <option value="Bangladesh">Bangladesh</option>
                        <option value="Belarus">Belarus</option>
                        <option value="Brazil">Brazil</option>
                        <option value="Canada">Canada</option>
                        <option value="China">China</option>
                        <option value="France">France</option>
                        <option value="Germany">Germany</option>
                        <option value="India">India</option>
                        <option value="Indonesia">Indonesia</option>
                        <option value="Israel">Israel</option>
                        <option value="Italy">Italy</option>
                        <option value="Japan">Japan</option>
                        <option value="Korea">Korea, Republic of</option>
                        <option value="Mexico">Mexico</option>
                        <option value="Philippines">Philippines</option>
                        <option value="Russia">Russian Federation</option>
                        <option value="South Africa">South Africa</option>
                        <option value="Thailand">Thailand</option>
                        <option value="Turkey">Turkey</option>
                        <option value="Ukraine">Ukraine</option>
                        <option value="United Arab Emirates">United Arab Emirates</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="United States">United States</option>
                      </select>
                    </div>
                    <div class="col-md-6 select2-primary">
                      <label class="form-label" for="multicol-language">Language</label>
                      <select id="multicol-language" class="select2 form-select" multiple>
                        <option value="en" selected>English</option>
                        <option value="fr" selected>French</option>
                        <option value="de">German</option>
                        <option value="pt">Portuguese</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" for="multicol-birthdate">Birth Date</label>
                      <input
                        type="text"
                        id="multicol-birthdate"
                        class="form-control dob-picker"
                        placeholder="YYYY-MM-DD" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" for="multicol-phone">Phone No</label>
                      <input
                        type="text"
                        id="multicol-phone"
                        class="form-control phone-mask"
                        placeholder="658 799 8941"
                        aria-label="658 799 8941" />
                    </div>
                  </div>
                  <div class="pt-4">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                    <button type="reset" class="btn btn-label-secondary">Cancel</button>
                  </div>
                </form>
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

