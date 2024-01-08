@extends('layouts.base')
@section('title', 'Users Tasks')
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

                        <div class="row me-2 mt-3">
                            <div class="col-md-2">
                                <div class="me-3">

                                    <div class="dataTables_length" id="DataTables_Table_0_length"><label class="fw-bold">
                                            Users
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


                        <table class="table border-top dataTable" id="usersTable">
                            <thead class="table-light">
                                <tr>

                                    <th>Users</th>
                                    <th>Country</th>
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
                                                            src="{{ $user->image != '' ? asset('profile/' . $user->image) : '../../assets/img/app_icon.png' }}"
                                                            alt="Avatar" class="rounded-circle">
                                                    </div>
                                                </div>




                                                <div class="d-flex flex-column"><a href="#"
                                                        class="text-body text-truncate"><span
                                                            class="fw-semibold user-name-text">{{ $user->name }}</span></a>
                                                    <small class="text-muted">&#64;{{ $user->email }}</small>

                                                </div>
                                            </div>
                                        </td>

                                        <td>{{ $user->country }}</td>

                                        <td class="detailbtn">
                                            <a href="javascript:;" class="text-body dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-sm mx-1"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end m-0">
                                                <a href="" data-bs-toggle="modal"
                                                    data-bs-target="#Assigntask{{ $user->id }}" class="dropdown-item">
                                                    @if ($user->assign)
                                                        Un Assign
                                                    @else
                                                        Assign task
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="modal fade" id="Assigntask{{ $user->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                    <div class="modal-content verifymodal">
                                                        <div class="modal-header">
                                                            <div class="modal-title text-start" id="modalCenterTitle">
                                                                @if ($user->assign)
                                                                    Are you you want to un assign this task?
                                                                @else
                                                                    Are you you want to assign this task?
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="modal-body text-start">
                                                            <div class="body">
                                                                @if ($user->assign)
                                                                    if you will un assign this task user cannot uplaod
                                                                    video
                                                                @else
                                                                    if you will assign this task user can uplaod video
                                                                @endif

                                                            </div>
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
                                                                        href="{{ route('dashboard-task-assign', ['user_id' => $user->id, 'task_id' => $task_id]) }}">Assign</a>
                                                                </div>
                                                            </div>
                                                        </div>

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
                                            {{ $users->render('task.pagination') }}

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








            </div>
        </div>
    @endsection
    @section('script')
        <script>
            $(document).ready(function() {
                $('#searchInput').keyup(function() {
                    var searchValue = $(this).val();
                    var loader = $('#loader');
                    loader.show();

                    task_id = '{{ $task_id }}'
                    console.log(task_id)


                    // if (searchValue.length) { // Adjust the minimum length as needed
                    $.ajax({
                        url: '/dashboard/task/list/user/list/' +
                            task_id, // Replace with your controller route
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

    @endsection
