@extends('layouts.base')
@section('title', 'Levels')
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
                                            All Levels


                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div
                                    class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
                                    <div id="DataTables_Table_0_filter" class="dataTables_filter searchinput">
                                                
                                           

                                    </div>


                                    <div class="dt-buttons btn-group flex-wrap ">
                                        <button class="btn btn-secondary add-new btn-primary mx-3" tabindex="0"
                                            aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal"
                                            data-bs-target="#addNewBus"><span><i
                                                    class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                                                    class="d-none d-sm-inline-block">Add New Level</span></span></button>
                                    </div>

                                </div>

                            </div>

                        </div>



                        <table class="table border-top dataTable">
                            <thead class="table-light">
                                <tr>

                                    <th>Name</th>
                                    <th>Description</th>

                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody id="searchResults">
                                @foreach ($levels as $level)
                                    <tr class="odd">


                                        <td class="">{{ $level->name }}</td>
                                        <td class="">{{ $level->description }}</td>


                                        <td class="" style="">
                                            <div class="d-flex align-items-center">
                                                <a href="" data-bs-toggle="modal"
                                                    data-bs-target="#editBus{{ $level->id }}"
                                                    class="text-body delete-record"><i class="ti ti-edit"></i></a>

                                                <a href="" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $level->id }}"
                                                    class="text-body delete-record">
                                                    <i class="ti ti-trash x`ti-sm mx-2"></i>
                                                </a>


                                            </div>


                                            <div class="modal fade" data-bs-backdrop='static'
                                                id="deleteModal{{ $level->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                    <div class="modal-content deleteModal verifymodal">
                                                        <div class="modal-header">
                                                            <div class="modal-title" id="modalCenterTitle">Are you sure you
                                                                want to delete
                                                                this Level?
                                                            </div>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="body">After deleting this level you cannot use
                                                                this level</div>
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
                                                                        href="{{ route('dashboard-level-delete', $level->id) }}">Delete</a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" data-bs-backdrop='static'
                                                id="editBus{{ $level->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalCenterTitle">Edit Level</h5>

                                                        </div>

                                                        <form action="{{ route('dashboard-level-edit', $level->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col mb-3">
                                                                        <label for="nameWithTitle"
                                                                            class="form-label">Level
                                                                        </label>
                                                                        <input type="text" id="nameWithTitle"
                                                                            name="name" value="{{ $level->name }}"
                                                                            class="form-control" placeholder="Add Level"
                                                                            required />
                                                                        <span class="text-danger mt-1">
                                                                            @error('name')
                                                                                {{ $message }}
                                                                            @enderror
                                                                        </span>
                                                                    </div>

                                                                </div>

                                                                <div class="row">
                                                                    <div class="col mb-3">
                                                                        <label for="nameWithTitle" class="form-label">Description</label>
                                                                        <textarea type="text" id="nameWithTitle" name="description"
                                                                            class="form-control" placeholder="Add description"  required >{{ $level->description  }}</textarea>
                                                                        <span class="text-danger mt-1">
                                                                            @error('description')
                                                                                {{ $message }}
                                                                            @enderror
                                                                        </span>
                                                                    </div>
                    
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-label-secondary"
                                                                    data-bs-dismiss="modal">
                                                                    Close
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">Add
                                                                    Level</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach



                            </tbody>
                        </table>

                        <div class="modal fade" data-bs-backdrop='static' id="addNewBus" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalCenterTitle">Add New Level</h5>

                                    </div>

                                    <form id="addBusForm" action="{{ route('dashboard-level-add') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col mb-3">
                                                    <label for="nameWithTitle" class="form-label">Level</label>
                                                    <input type="text" id="nameWithTitle" name="name"
                                                        class="form-control" placeholder="Add Level" required />
                                                    <span class="text-danger mt-1">
                                                        @error('name')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col mb-3">
                                                    <label for="nameWithTitle" class="form-label">Description</label>
                                                    <textarea type="text" id="nameWithTitle" name="description"
                                                        class="form-control" placeholder="Add description" required ></textarea>
                                                    <span class="text-danger mt-1">
                                                        @error('description')
                                                            {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>

                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-label-secondary"
                                                data-bs-dismiss="modal" id="closeButton">
                                                Close
                                            </button>
                                            <button type="submit" class="btn btn-primary">Add Level</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>

                        {{-- 
                        <div id="paginationContainer">
                            <div class="row mx-2">
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_info" id="DataTables_Table_0_info" role="status"
                                        aria-live="polite">Showing to
                                        of
                                        entries</div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_paginate paging_simple_numbers" id="paginationLinks">
                                        @if ($buses->hasPages())

                                            {{ $buses->links() }}
                                        @endif


                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <!-- Offcanvas to add new user -->








            </div>
        </div>
    @endsection

    @section('script')

        {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#searchInput').keyup(function() {
                    var searchValue = $(this).val();
                    var loader = $('#loader');

                    loader.show();
                    // if (searchValue.length) { // Adjust the minimum length as needed
                    $.ajax({
                        url: '{{ route('dashboard-level') }}', // Replace with your controller route
                        method: 'GET',
                        data: {
                            query: searchValue
                        },
                        success: function(data) {

                            $("#searchResults").html(data)

                        },
                        complete: function() {
                            loader.hide(); // Hide the loader after request is complete
                        }
                    });
                    // }
                });
            });
            $(document).ready(function() {
                $('#closeButton').on('click', function(e) {
                    $('#addBusForm')[0].reset();

                });

            });
        </script> --}}
    @endsection
