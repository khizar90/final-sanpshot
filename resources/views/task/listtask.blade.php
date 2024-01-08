@extends('layouts.base')
@section('title', 'Task List')
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

                        <div class="row border-bottom me-2 mt-3">
                            <div class="col-md-2">
                                <div class="me-3">

                                    <div class="dataTables_length" id="DataTables_Table_0_length"><label class="fw-bold">
                                            All Task
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div
                                    class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
                                    <div id="DataTables_Table_0_filter" class="dataTables_filter searchinput">
                                        <label class="user_search input-group input-group-merge">


                                            <input type="text" class="form-control" placeholder="Search.." value=""
                                                id="searchInput" aria-controls="DataTables_Table_0">
                                            {{-- <span class="input-group-text" style="display: none" ><i
                                                    class="ti ti-rotate-clockwise-2"></i></span> --}}
                                            <div class="spinner-border text-primary mx-2" style="display: none"
                                                id="loader" role="status">
                                                <span class="visually-hidden"></span>
                                            </div>
                                        </label>

                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="row  mt-3" id="searchResults">
                            @foreach ($tasks as $task)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card vedioCard">
                                        <div class="videocover">
                                            <div class="coverimage">
                                                <img class="card-img-top"
                                                    src="{{ asset('Admin/Task/image/' . $task->image) }}"
                                                    alt="Card image cap" />
                                            </div>
                                            <a href="{{ asset('Admin/Task/video/' . $task->video) }}" target="_blank"
                                                class="playIcon">
                                                <i class="ti ti-player-play ti-lg"></i>

                                            </a>
                                        </div>
                                        <div class="card-body  ">
                                            <div
                                                class="title d-flex align-items-center justify-content-center justify-content-between">
                                                <h5 class="card-title mt-3">{{ $task->title }}</h5>
                                                <div class="">
                                                    <a href="{{ route('dashboard-task-edit', $task->id) }}"
                                                        class="text-body edit-record">
                                                        <i class="ti ti-edit ti-sm me-2"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="text-body">
                                                <span class="fw-bold">Description: </span>
                                                <span> {{ $task->description }}</span>
                                            </div>
                                            <div class="text-body">
                                                <span class="fw-bold">Point: </span>
                                                <span> {{ $task->point }}</span>
                                            </div>
                                            <div class="text-body">
                                                <span class="fw-bold">Guideline: </span>
                                                <span> {{ $task->guideline }}</span>
                                            </div>
                                            <div class="text-body">
                                                <span class="fw-bold">Deadline: </span>
                                                <span> {{ $task->deadline }}</span>
                                            </div>

                                            <div class="d-flex justify-content-between">
                                                <div class="text-center mt-3">
                                                    <a href="{{ route('dashboard-task-user-list', $task->id) }}"
                                                        class="btn btn-primary">
                                                        Assign Users
                                                    </a>

                                                </div>
                                                <div class="text-center mt-3">
                                                    <a href="{{ route('dashboard-task-user-videos', $task->id) }}"
                                                        class="btn btn-primary">
                                                        See Videos
                                                    </a>
                                                </div>
                                            </div>


                                            <div class="text-center mt-3">
                                                <span data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $task->id }}"
                                                    class="btn btn-{{ $task->status == 1 ? 'success' : 'secondary' }}">
                                                    {{ $task->status == 1 ? 'Active' : 'Deactive' }}
                                                </span>
                                            </div>
                                            <div class="text-center mt-3">
                                                <span data-bs-toggle="modal"
                                                    data-bs-target="#deleteTask{{ $task->id }}"
                                                    class="btn btn-danger">
                                                   Delete
                                                </span>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" data-bs-backdrop='static' id="deleteModal{{ $task->id }}"
                                    tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                        <div class="modal-content deleteModal verifymodal">
                                            <div class="modal-header">
                                                <div class="modal-title" id="modalCenterTitle">Are you sure you
                                                    want to {{ $task->status == 1 ? 'deactive' : 'active' }}
                                                    this task?
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="body">After {{ $task->status == 1 ? 'deactive' : 'active' }}
                                                    this task user {{ $task->status == 1 ? 'cannot' : 'can' }}
                                                    use this task</div>
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
                                                            href="{{ route('dashboard-task-active', $task->id) }}">Done</a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" data-bs-backdrop='static' id="deleteTask{{ $task->id }}"
                                    tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                        <div class="modal-content deleteModal verifymodal">
                                            <div class="modal-header">
                                                <div class="modal-title" id="modalCenterTitle">Are you sure you
                                                    want to delete
                                                    this task?
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="body">After deleted
                                                    this task user cannot see it</div>
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
                                                            href="{{ route('dashboard-task-delete', $task->id) }}">Delete</a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div id="paginationContainer">
                            <div class="row mx-2">
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_info" id="DataTables_Table_0_info" role="status"
                                        aria-live="polite">Showing {{ $tasks->firstItem() }} to
                                        {{ $tasks->lastItem() }}
                                        of
                                        {{ $tasks->total() }} entries</div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_paginate paging_simple_numbers" id="paginationLinks">
                                        {{-- <h1>{{ @json($data) }}</h1> --}}
                                        @if ($tasks->hasPages())
                                            {{ $tasks->render('user.pagination') }}
                                        @endif


                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
        @endsection
        @section('script')
            <script>
                $(document).ready(function() {
                    $('#searchInput').keyup(function() {
                        console.log('Button clicked');
                        var searchValue = $(this).val();
                        console.log(searchValue);
                        var loader = $('#loader');
                        loader.show();

                        $.ajax({
                            url: '{{ route('dashboard-task-list') }}',
                            method: 'GET',
                            data: {
                                query: searchValue
                            },
                            success: function(data) {
                                console.log(data);
                                $("#searchResults").html(data)
                            },
                            complete: function() {
                                loader.hide(); // Hide the loader after request is complete
                            }
                        });
                        // Add any additional functionality or actions here
                    });
                });
            </script>
            {{-- <script>
                $(document).ready(function() {
                    $('#searchInput').keyup(function() {
                        
                        var searchValue = $(this).val();
                        var loader = $('#loader');
                        loader.show();



                        // if (searchValue.length) { // Adjust the minimum length as needed
                        $.ajax({
                            url: '{{ route('dashboard-task-list') }}'
                            method: 'GET',
                            data: {
                                query: searchValue
                            },
                            success: function(data) {
                                console.log(data);
                                // $("#searchResults").html(data)

                            },
                            complete: function() {
                                loader.hide(); // Hide the loader after request is complete
                            }
                        });
                        // }
                    });
                });
                $(document).ready(function() {
                    $('#testbutton').click(function() {
                        console.log('hy');
                    });
                    
                })
             
            </script> --}}
        @endsection
