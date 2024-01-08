@extends('layouts.base')
@section('title', 'User Videos')
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

                        <div class="row mt-4 mb-2">
                            <div class="col-md-2">
                                <div class="me-3">

                                    <div class="dataTables_length" id="DataTables_Table_0_length"><label class="fw-bold">
                                            All User Videos
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

                                </div>
                            </div>

                        </div>

                        <div class="row mt-3">
                            <!-- Monthly Campaign State -->
                            @foreach ($userVideos as $userVideo)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card vedioCard">
                                        <div class="videocover">
                                            <div class="coverimage">

                                            </div>
                                            <a href="{{ asset('User/video/' . $userVideo->video) }}" target="_blank"
                                                class="playIcon">
                                                <i class="ti ti-player-play ti-lg"></i>

                                            </a>
                                        </div>
                                        <div class="card-body  ">
                                            <div class="text-body">
                                                <span class="fw-bold">Task Title: </span>
                                                <span> {{ $userVideo->title }}</span>
                                            </div>
                                            <div class="text-body">
                                                <span class="fw-bold">User Name: </span>
                                                <span> {{ $userVideo->user->name }}</span>
                                            </div>

                                            <div class="text-body">
                                                <span class="fw-bold">User Email: </span>
                                                <span> {{ $userVideo->user->email }}</span>
                                            </div>

                                            <div class="text-body">
                                                <span class="fw-bold">User Phone: </span>
                                                <span> {{ $userVideo->user->phone }}</span>
                                            </div>

                                            {{-- <div class="text-body">
                                                <span class="fw-bold">Point: </span>
                                                <span> {{ $userVideo->point }}</span>
                                            </div> --}}


                                            <div id="accordionPayment" class="accordion mt-4">
                                                <div class="row">
                                                    @foreach ($userVideo->question as $question)
                                                        <div class="mb-2">


                                                            <h2 class="accordion-header">
                                                                <button class="accordion-button collapsed" type="button"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#accordionPayment-{{ $question->id }}"
                                                                    aria-controls="accordionPayment-2">
                                                                    {{ $question->questions->question }}
                                                                </button>
                                                            </h2>
                                                            <div id="accordionPayment-{{ $question->id }}"
                                                                class="accordion-collapse collapse">
                                                                <div class="accordion-body">
                                                                    {{ $question->item }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                </div>


                                            </div>





                                            <div class="d-flex justify-content-between">
                                                <div class="text-center mt-3 ">

                                                    @if ($userVideo->is_approved == 1)
                                                        <span class="btn btn-primary disabled">
                                                            Video Approved
                                                        </span>
                                                        <span class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#videoDelete{{ $userVideo->id }}">
                                                            Delete Video
                                                        </span>
                                                    @endif
                                                    @if ($userVideo->is_approved == 0)
                                                        <a href="" data-bs-toggle="modal"
                                                            data-bs-target="#editBus{{ $userVideo->id }}"
                                                            class="btn btn-secondary">
                                                            Pending
                                                        </a>
                                                    @endif
                                                    @if ($userVideo->is_approved == 2)
                                                        <span class="btn btn-danger disabled">
                                                            Video Declined
                                                        </span>
                                                        <span class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#videoDelete{{ $userVideo->id }}">
                                                            Delete Video
                                                        </span>
                                                    @endif

                                                </div>


                                                <div class="modal fade" id="editBus{{ $userVideo->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                        <div class="modal-content verifymodal">
                                                            <div class="modal-header">
                                                                <div class="modal-title" id="modalCenterTitle">Are you
                                                                    sure you want to approevd
                                                                    this Video?
                                                                </div>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="body">If you will approved this video after
                                                                    that user  will get point</div>
                                                            </div>
                                                            <hr class="hr">
                
                                                            <div class="container">
                                                                <div class="row">
                                                                    <div class="first">
                                                                        <a href="" class="btn" data-bs-dismiss="modal"
                                                                            style="color: #a8aaae ">Cancel</a>
                                                                    </div>
                                                                    <div class="second">
                                                                        <a href="{{ route('dashboard-reward', ['id' => $userVideo->id, 'task_id' => $userVideo->task_id]) }}"
                                                                            class="btn btn-primary" style="background: #FEDC00">Approve</a>
                                                                    </div>
                
                                                                </div>
                                                            </div>
                
                                                        </div>
                                                    </div>
                                                </div>




                                                @if ($userVideo->is_approved == 0)
                                                    <div class="text-center mt-3 second">
                                                        @if ($userVideo->is_approved == 0)
                                                            <a href="" class="btn btn-danger" data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal{{ $userVideo->id }}">
                                                                Decline
                                                            </a>
                                                        @endif
                                                        @if ($userVideo->is_approved == 2)
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>




                                            {{-- <div class="text-center mt-3">
                                                <span data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $task->id }}"
                                                    class="btn btn-{{ $task->status == 1 ? 'success' : 'secondary' }}">
                                                    {{ $task->status == 1 ? 'Active' : 'Pending' }}
                                                </span>
                                            </div> --}}

                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="editBus{{ $userVideo->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                        <div class="modal-content verifymodal">
                                            <div class="modal-header">
                                                <div class="modal-title" id="modalCenterTitle">Are you
                                                    sure you want to approve
                                                    this Video?
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="body">If you will approve this video after
                                                    that user video will approved</div>
                                            </div>
                                            <hr class="hr">

                                            <div class="container">
                                                <div class="row">
                                                    <div class="first">
                                                        <a href="" class="btn" data-bs-dismiss="modal"
                                                            style="color: #a8aaae ">Cancel</a>
                                                    </div>
                                                    <div class="second">
                                                        <a href="{{ route('dashboard-reward', ['id' => $userVideo->id, 'task_id' => $userVideo->task_id]) }}"
                                                            class="btn">Approve</a>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" data-bs-backdrop='static' id="deleteModal{{ $userVideo->id }}"
                                    tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                        <div class="modal-content deleteModal verifymodal">
                                            <div class="modal-header">
                                                <div class="modal-title" id="modalCenterTitle">Are you sure you
                                                    want to decline
                                                    this video?
                                                </div>
                                            </div>
                                            <form action="{{ route('dashboard-decline-video', $userVideo->id) }}"
                                                method="GET">
                                                <div class="modal-body">
                                                    <div class="body">
                                                        <div class="row">
                                                            <div class="col">
                                                                <textarea id="" name="reason" class="form-control" rows="3" placeholder="Reason" required></textarea>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="text" hidden name="video_id"
                                                    value="{{ $userVideo->id }}" />
                                                <hr class="hr">

                                                <div class="container">
                                                    <div class="row">
                                                        <div class="first">
                                                            <a href="" class="btn" data-bs-dismiss="modal"
                                                                style="color: #a8aaae ">Cancel</a>
                                                        </div>
                                                        <div class="second">
                                                            <button type="submit"
                                                                class="btn text-center">Decline</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>



                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" data-bs-backdrop='static' id="videoDelete{{ $userVideo->id }}"
                                    tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                        <div class="modal-content deleteModal verifymodal">
                                            <div class="modal-header">
                                                <div class="modal-title" id="modalCenterTitle">Are you
                                                    sure you want to Delete
                                                    this Video?
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="body">If you will Delete this video it will delete permanently
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
                                                        <a href="{{ route('dashboard-delete-video',  $userVideo->id) }}"
                                                            class="btn">Delete</a>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div id="paginationContainer">
                                <div class="row mx-2">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="dataTables_info" id="DataTables_Table_0_info" role="status"
                                            aria-live="polite">Showing {{ $userVideos->firstItem() }} to
                                            {{ $userVideos->lastItem() }}
                                            of
                                            {{ $userVideos->total() }} entries</div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="dataTables_paginate paging_simple_numbers" id="paginationLinks">
                                            {{-- <h1>{{ @json($data) }}</h1> --}}
                                            @if ($userVideos->hasPages())
                                                {{ $userVideos->render('user.pagination') }}

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




        @endsection
