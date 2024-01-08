@extends('layouts.base')
@section('title', 'Users')
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

                        <div class="row me-2 mt-3">
                            <div class="col-md-2">
                                <div class="me-3">

                                    <div class="dataTables_length" id="DataTables_Table_0_length"><label class="fw-bold">
                                            All Users


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
                                    <div class="dt-buttons btn-group flex-wrap">
                                        <div class="btn-group">
                                            <button class="btn btn-secondary buttons-collection btn-label-secondary mx-3"
                                                data-bs-toggle="modal" data-bs-target="#modalContainer" type="button">
                                                <span><i class="ti ti-screen-share me-1 ti-xs"></i>Export</span>
                                                <span class="dt-down-arrow"></span>
                                            </button>
                                        </div>
                                
                                    </div>
                                </div>
                            </div>

                        </div>


                        <table class="table border-top dataTable" id="usersTable">
                            <thead class="table-light">
                                <tr>

                                    <th>Users</th>
                                    <th>country</th>
                                    <th>Phones</th>
                                    <th>Status</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody id="searchResults">
                                @foreach ($users as $user)
                                    <tr class="odd">



                                        <td class="">
                                            <div class="d-flex justify-content-start align-items-center user-name">
                                                <div class="avatar-wrapper">
                                                    <div class="avatar avatar-sm me-3"><img
                                                            src="{{ asset($user->image != '' ? 'profile/' . $user->image : '../../assets/img/app_icon.png') }}"
                                                            alt="Avatar" class="rounded-circle">
                                                    </div>
                                                </div>




                                                <div class="d-flex flex-column"><a
                                                        href="{{ route('dashboard-user-profile', $user->id) }}"
                                                        class="text-body text-truncate"><span
                                                            class="fw-semibold user-name-text">{{ $user->name }}</span></a>
                                                    <small class="text-muted">&#64;{{ $user->email }}</small>

                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->country }}</td>

                                        <td>{{ $user->phone }}</td>

                                        <td class="">
                                            @if ($user->status == 1)
                                                <button class="badge bg-label-success btn"
                                                    class="text-capitalize">Approved</button>
                                            @else
                                                <button class="badge bg-label-secondary btn" data-bs-toggle="modal"
                                                    data-bs-target="#verifyModal{{ $user->id }}"
                                                    text-capitalized="">Pending

                                                </button>


                                                <div class="modal fade" id="verifyModal{{ $user->id }}" tabindex="-1"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-sm"
                                                        role="document">
                                                        <div class="modal-content verifymodal">
                                                            <div class="modal-header">
                                                                <div class="modal-title" id="modalCenterTitle">Are you
                                                                    sure you want to approve
                                                                    this account?
                                                                </div>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="body">If you will approve this account after
                                                                    that
                                                                    this user will access snapshot app.</div>
                                                            </div>
                                                            <hr class="hr">

                                                            <div class="container">
                                                                <div class="row">
                                                                    <div class="first">
                                                                        <a href="" class="btn"
                                                                            data-bs-dismiss="modal"
                                                                            style="color: #a8aaae ">Cancel</a>
                                                                    </div>
                                                                    <div class="second">
                                                                        <a class="btn text-center"
                                                                            href="{{ route('dashboard-user-verify', $user->id) }}">APPROVED</a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>

                                        <td class="" style="">
                                            <div class="d-flex align-items-center">




                                                <a href="javascript:;" class="text-body dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical ti-sm mx-1"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end m-0">
                                                    <a href="" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $user->id }}"
                                                        class="dropdown-item">
                                                        Delete
                                                    </a>

                                                    <a href="" data-bs-toggle="modal"
                                                        data-bs-target="#bonusModal{{ $user->id }}"
                                                        class="dropdown-item">
                                                        Bonus
                                                    </a>

                                                     <a href="" data-bs-toggle="modal"
                                                        data-bs-target="#message{{ $user->id }}"
                                                        class="dropdown-item">
                                                        Send Message
                                                    </a>






                                                </div>


                                            </div>
                                            <div class="modal fade" data-bs-backdrop='static'
                                                id="deleteModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                    <div class="modal-content deleteModal verifymodal">
                                                        <div class="modal-header">
                                                            <div class="modal-title" id="modalCenterTitle">Are you
                                                                sure you want to delete
                                                                this account?
                                                            </div>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="body">After delete this account user cannot
                                                                access anything in application</div>
                                                        </div>
                                                        <hr class="hr">

                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="first">
                                                                    <a href="" class="btn"
                                                                        data-bs-dismiss="modal"
                                                                        style="color: #a8aaae ">Cancel</a>
                                                                </div>
                                                                <div class="second">
                                                                    <a class="btn text-center"
                                                                        href="{{ route('dashboard-user-delete', $user->id) }}">Delete</a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="bonusModal{{ $user->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                    <div class="modal-content verifymodal">
                                                        <div class="modal-header">
                                                            <div class="modal-title" id="modalCenterTitle">
                                                                Bonus
                                                            </div>
                                                        </div>
                                                        <form action="{{ route('dashboard-user-bonus', $user->id) }}"
                                                            method="GET">
                                                            <div class="modal-body">
                                                                <div class="body">
                                                                    <input type="number" class="form-control"
                                                                        placeholder="point" name="point" required>
                                                                </div>
                                                            </div>
                                                            <hr class="hr">

                                                            <div class="container">
                                                                <div class="row">
                                                                    <div class="first">
                                                                        <a href="" class="btn"
                                                                            data-bs-dismiss="modal"
                                                                            style="color: #a8aaae ">Cancel</a>
                                                                    </div>
                                                                    <div class="second">
                                                                        <button class="btn text-center"
                                                                            href="">Give</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="message{{ $user->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content verifymodal">
                                                        <div class="modal-header">
                                                            <div class="modal-title" id="modalCenterTitle">
                                                                Message
                                                            </div>
                                                        </div>
                                                        <form action="{{ route('dashboard-user-message') }}"
                                                            method="GET">
                                                            <div class="modal-body">
                                                                <input type="text" hidden name="user_id" value="{{ $user->id }}" id="">
                                                                <div class="body">
                                                                    <textarea rows="5" class="form-control"
                                                                        placeholder="Type Here" name="message" required></textarea>
                                                                </div>
                                                            </div>
                                                            <hr class="hr">

                                                            <div class="container">
                                                                <div class="row">
                                                                    <div class="first">
                                                                        <a href="" class="btn"
                                                                            data-bs-dismiss="modal"
                                                                            style="color: #a8aaae ">Cancel</a>
                                                                    </div>
                                                                    <div class="second">
                                                                        <button class="btn text-center"
                                                                            href="">Send</button>
                                                                    </div>
                                                                </div>
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

                        
                            <div id="paginationContainer">
                                <div class="row mx-2">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="dataTables_info" id="DataTables_Table_0_info" role="status"
                                            aria-live="polite">Showing {{ $users->firstItem() }} to
                                            {{ $users->lastItem() }}
                                            of
                                            {{ $users->total() }} entries</div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="dataTables_paginate paging_simple_numbers" id="paginationLinks">
                                            {{-- <h1>{{ @json($data) }}</h1> --}}
                                            @if ($users->hasPages())
                                                {{ $users->render('user.pagination') }}

                                                {{-- {{ $users->withQueryString()->links() }} --}}
                                                {{-- {{ $users->links() }} --}}
                                                {{-- {{ $users->links('', ['status' => '', 'devices' => '', 'category' => '' ,]) }} --}}
                                                {{-- {{ $users->appends(['status' => '', 'devices' => '', 'category' => '', 'search' => ''])->links() }} --}}
                                                {{-- {{ $users->appends(['status' => $accountStatus, 'devices' => $devices, 'category' => $category, 'search' => $searchText])->links() }} --}}
                                            @endif


                                        </div>
                                    </div>
                                </div>
                            </div>


                    </div>
                </div>
                <!-- Offcanvas to add new user -->
                <div class="modal fade" id="modalContainer" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content verifymodal">
                            <div class="modal-header">
                                <div class="modal-title" id="modalCenterTitle">Are you sure you want to export all users
                                    in CSV formart?</div>

                            </div>
                            <div class="modal-body">
                                <div class="body"> Chose which users you wnat to exports? Approve or pendding or both?
                                </div>
                            </div>


                            <form method="GET" action="{{ route('dashboard-user-export/csv') }}">
                                @csrf

                                <div class="card-body">
                                    <div class="row gy-3">
                                        <div class="col-lg-12 mx-auto">
                                            <!-- 1. Delivery Address -->

                                            <!-- 2. Delivery Type -->
                                            <div class="row gy-3">
                                                <div class="col-md">
                                                    <div class="form-check custom-option custom-option-icon">
                                                        <label class="form-check-label custom-option-content"
                                                            for="both">
                                                            <span class="custom-option-body">
                                                                <i class="ti ti-users"></i>

                                                                <span class="custom-option-title"> Both </span>
                                                                <small>Export approved and pending users </small>
                                                            </span>
                                                            <input name="action" class="form-check-input" type="radio"
                                                                value="2" checked />
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md">
                                                    <div class="form-check custom-option custom-option-icon">
                                                        <label class="form-check-label custom-option-content"
                                                            for="approved">
                                                            <span class="custom-option-body">
                                                                <i class="ti ti-users"></i>

                                                                <span class="custom-option-title"> Approved </span>
                                                                <small>Export approved users</small>
                                                            </span>
                                                            <input name="action" class="form-check-input" type="radio"
                                                                value="1" />
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md">
                                                    <div class="form-check custom-option custom-option-icon">
                                                        <label class="form-check-label custom-option-content"
                                                            for="pending">
                                                            <span class="custom-option-body">
                                                                <i class="ti ti-users"></i>
                                                                <span class="custom-option-title"> Pending </span>
                                                                <small> Export pending users </small>
                                                            </span>
                                                            <input name="action" class="form-check-input" type="radio"
                                                                value="0" />
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                                <hr class="hr">
                                <div class="container">
                                    <div class="row">
                                        <div class="first">
                                            <a class="btn" data-bs-dismiss="modal" style="color: #a8aaae ">Cancel</a>
                                        </div>
                                        <div class="second">
                                            <button type="submit" class="btn"
                                                onclick="dismissModal()">Export</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" data-bs-backdrop='static' id="addNewBus" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalCenterTitle">Add New User</h5>

                            </div>

                            <form id="addBusForm">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col mb-3">
                                            <label for="nameWithTitle" class="form-label">Name</label>
                                            <input type="text" id="nameWithTitle" name="name" class="form-control"
                                                placeholder="Johan Doe" required />

                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col mb-3">
                                            <label for="nameWithTitle" class="form-label">Email Address</label>
                                            <input type="email" id="nameWithTitle" name="email" class="form-control"
                                                placeholder="Email" required />

                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col mb-3">
                                            <label for="nameWithTitle" class="form-label">Password</label>
                                            <input type="password" id="nameWithTitle" name="passsword"
                                                class="form-control" placeholder="*********" required />

                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                        id="closeButton">
                                        Close
                                    </button>
                                    <button type="submit" class="btn btn-primary">Add User</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>







            </div>
        </div>
    @endsection
    @section('script')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#searchInput').keyup(function() {
                    var searchValue = $(this).val();
                    var loader = $('#loader');
                    loader.show();

                    // if (searchValue.length) { // Adjust the minimum length as needed
                    $.ajax({
                        url: '{{ route('dashboard-user-') }}', // Replace with your controller route
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
                    // }
                });
            });
            $(document).ready(function() {
                $('#closeButton').on('click', function(e) {
                    $('#addBusForm')[0].reset();

                });

            });
        </script>
         <script>
            function dismissModal() {
                $('#modalContainer').modal('hide');
            }
        </script>

    @endsection
