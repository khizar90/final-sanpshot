@extends('layouts.base')
@section('title', 'Profile')
@section('main', 'Accounts Management')
@section('link')
    <link rel="stylesheet" href="/assets/vendor/css/pages/page-profile.css" />
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-12">
                    <div class="card card2 mb-4">
                        <div class="user-profile-header-banner">
                            <img src="{{ asset('assets/img/pages/profile-banner.png') }}" alt="Banner image"
                                class="rounded-top" />
                        </div>
                        <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog  modal-dialog-centered modal-simple modal-upgrade-plan">
                                <div class="modal-content  p-0 bg-transparent">
                                    <div class="modal-body p-0">
                                        {{-- <button type="button" class="btn-close bg-transparent" data-bs-dismiss="modal"
                                            aria-label="Close"></button> --}}
                                        <div class="text-center">
                                            <img src="{{ $user->image != '' ? asset('profile/' . $user->image) : '../../assets/img/avatars/1.png' }}"
                                                alt="user image" class="img-fluid rounded" />
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                            <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                                <img src="{{ $user->image != '' ? asset('profile/' . $user->image) : '../../assets/img/app_icon.png' }}"
                                    alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img"
                                    onclick="{{ $user->image != '' ? 'openModal()' : '' }}" />
                            </div>
                            <div class="flex-grow-1 mt-3 mt-sm-5">
                                <div
                                    class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                                    <div class="user-profile-info">
                                        <h4>{{ $user->name }}</h4>
                                        <ul
                                            class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                            <li class="list-inline-item d-flex gap-1">
                                                <i class="ti ti-mail"></i> {{ $user->email }}
                                            </li>
                                            <li class="list-inline-item d-flex gap-1">
                                                <i class="ti ti-map-pin"></i> {{ $user->country }}
                                            </li>
                                            <li class="list-inline-item d-flex gap-1">
                                                <i class="ti ti-calendar"></i> Joined
                                                {{ $user->created_at->format(' M Y') }}
                                            </li>
                                        </ul>
                                    </div>
                                    <a href="javascript:void(0)" data-bs-toggle="modal"
                                        data-bs-target="#verifyModal{{ $user->id }}"
                                        class="btn btn-{{ $user->status == 1 ? 'primary' : 'label-secondary' }} waves-effect waves-light">{{ $user->status == 1 ? 'Approved' : 'Pending' }}
                                    </a>
                                    <div class="modal fade" id="verifyModal{{ $user->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                            <div class="modal-content verifymodal">
                                                <div class="modal-header">
                                                    <div class="modal-title" id="modalCenterTitle">
                                                        {{ $user->status == 1
                                                            ? 'Are you sure you want to unapprove this account ?'
                                                            : 'Are you  you want to approve this account?' }}
                                                    </div>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="body">
                                                        {{ $user->status == 1
                                                            ? 'if you will unapprove this account after that this user will not access SnapShot app ?'
                                                            : 'If you will approve this account after that this user will access SnapShot app' }}
                                                    </div>
                                                </div>
                                                <hr class="hr">

                                                <div class="container">
                                                    <div class="row">
                                                        <div class="first">
                                                            <a href="" class="btn" data-bs-dismiss="modal"
                                                                style="color: #a8aaae ">Cancel</a>
                                                        </div>
                                                        <div class="second {{ $user->status == 1 ? 'newbtn1' : '' }}">
                                                            <a class="btn text-center"
                                                                href="{{ route('dashboard-user-verify', $user->id) }}">{{ $user->status == 1 ? 'Unapprove' : 'Approved' }}</a>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Header -->

            <!--/ Navbar pills -->

            <!-- User Profile Content -->
            <div class="row">

                <div class="col-xl-4 col-lg-5 col-md-5">
                    <!-- About User -->
                    <div class="card card2 mb-4">
                        <div class="card-body">
                            <small class="card-text text-uppercase">About</small>
                            <ul class="list-unstyled mb-4 mt-3">
                                <li class="d-flex align-items-center mb-3">
                                    <i class="ti ti-user"></i><span class="fw-bold mx-2">Full Name:</span> <span>
                                        {{ $user->name }}
                                    </span>
                                </li>
                                <li
                                    class="d-flex align-items-center mb-3 text-{{ $user->status == 1 ? 'success' : 'danger' }}">
                                    <i class="ti ti-{{ $user->status == 1 ? 'check' : 'x' }}"></i><span
                                        class="fw-bold mx-2">Status:</span>
                                    <span>{{ $user->status == 1 ? 'Approved' : 'Pending' }}</span>
                                </li>



                                <li class="d-flex  mb-3">
                                    <i class="ti ti-atom"></i><span class="fw-bold mx-2">Reason:</span>
                                    <span>{{ $user->reason }}</span>
                                </li>

                                <li class="d-flex  mb-3">
                                    <i class="ti ti-stack-pop"></i><span class="fw-bold mx-2">Level:</span>
                                    <span>{{ $user->level->name }}</span>
                                </li>

                                <li class="d-flex  mb-3">
                                    <i class="ti ti-book"></i><span class="fw-bold mx-2">About:</span>
                                    <span>{{ $user->information }}</span>
                                </li>
                                <li class="d-flex align-items-center mb-3">
                                    <i class="ti ti-crown"></i><span class="fw-bold mx-2">Profession:</span>
                                    <span>{{ $user->profession }}</span>
                                </li>

                            </ul>
                            <small class="card-text text-uppercase">Contacts</small>
                            <ul class="list-unstyled mb-4 mt-3">
                                <li class="d-flex align-items-center mb-3">
                                    <i class="ti ti-phone-call"></i><span class="fw-bold mx-2">Contact:</span>
                                    <span>{{ $user->phone }}</span>
                                </li>

                                <li class="d-flex align-items-center mb-3">
                                    <i class="ti ti-mail"></i><span class="fw-bold mx-2">Email:</span>
                                    <span>{{ $user->email }}</span>
                                </li>
                            </ul>

                        </div>
                    </div>
                    <!--/ About User -->
                    <!-- Profile Overview -->

                    <!--/ Profile Overview -->
                </div>

                <div class="col-xl-8 col-lg-7 col-md-7">
                    <!-- Activity Timeline -->
                    {{-- <div class="card fixedcard card-action mb-4 h-px-500">
                        <div class="card-header align-items-center">
                           
                        </div>
                        <div class="card-body pb-0">
                           
                        </div>
                    </div> --}}
                    <div class="row g-4">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <div class="card">
                                <div class="card-body text-center" id="tabbutton">

                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        <div class="col-md-3">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link {{ $type == 'user_task' ? 'active' : '' }}"
                                                    id="{{ $type }}" data-bs-toggle="pill"
                                                    data-bs-target="#{{ $type }}" type="button" role="tab"
                                                    aria-controls="{{ $type }}" aria-selected="false"
                                                    data-type="user_task">New not Accepted</button>
                                            </li>
                                        </div>
                                        <div class="col-md-3">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link {{ $type == 'new' ? 'active' : '' }}"
                                                    id="{{ $type }}" data-bs-toggle="pill"
                                                    data-bs-target="#{{ $type }}" type="button" role="tab"
                                                    aria-controls="{{ $type }}" aria-selected="true"
                                                    data-type="new">Current Accepted</button>
                                            </li>
                                        </div>
                                        <div class="col-md-3">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link {{ $type == 'pending' ? 'active' : '' }}"
                                                    id="{{ $type }}" data-bs-toggle="pill"
                                                    data-bs-target="#{{ $type }}" type="button" role="tab"
                                                    aria-controls="{{ $type }}" aria-selected="false"
                                                    data-type="pending">Awaiting Approval</button>
                                            </li>
                                        </div>
                                        

                                        <div class="col-md-3">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link {{ $type == 'cashed' ? 'active' : '' }}"
                                                    id="{{ $type }}" data-bs-toggle="pill"
                                                    data-bs-target="#{{ $type }}" type="button" role="tab"
                                                    aria-controls="{{ $type }}" aria-selected="false"
                                                    data-type="cashed">To Be Cashed</button>
                                            </li>
                                        </div>
                                       
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="{{ $type }}" role="tabpanel"
                                            aria-labelledby="{{ $type }}">
                                            <div class="col-lg-12 mb-4 col-md-12">
                                                <div class="car h-100 task-card">

                                                    <div class="card-body pt-2">
                                                        <div class="row gy-3">
                                                            <div class="card-info text-end">
                                                                <h5 class="mb-0">{{ $totalPoints }}</h5>
                                                                <small>Total Point</small>
                                                            </div>

                                                            <div class="row text-start">

                                                                <div class="col-md-4">
                                                                    <div class="text">
                                                                        <h5>Title</h5>
                                                                    </div>
                                                                </div>


                                                                <div class="col-md-4">
                                                                    <div class="text">
                                                                        <h5>Status</h5>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">

                                                                    <div class="text">
                                                                        <h5>Point</h5>
                                                                    </div>
                                                                </div>




                                                            </div>

                                                            @foreach ($tasks as $task)
                                                                <div class="row text-start text-body">

                                                                    @if ($task->status == 'Gifted')
                                                                        <div class="col-md-4 mb-3 col-4">

                                                                            <div class="text-success">
                                                                                {{ $task->title }}
                                                                            </div>
                                                                        </div>


                                                                        <div class="col-md-4 mb-3 col-4">
                                                                            <div class="text-success">
                                                                                {{ $task->status }}
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 mb-3 col-4">

                                                                            <div class="text-success">
                                                                                {{ $task->point }}
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <div class="col-md-4 mb-3 col-4">

                                                                            <div class="text">
                                                                                {{ $task->title }}
                                                                            </div>
                                                                        </div>


                                                                        <div class="col-md-4 mb-3 col-4">
                                                                            <div class="text">
                                                                                {{ $task->status }}
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 mb-3 col-4">

                                                                            <div class="text">
                                                                                {{ $task->point }}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

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
        <script src="/assets/js/pages-profile.js"></script>

        <script>
            function openModal() {
                $('#imageModal').modal('show');
            }
        </script>
        <script>
            $(document).on('click', '.nav-link', function(e) {
                e.preventDefault();
                let type = $(this).data('type');
                var loader = $('#spinner');
                loader.show();
                let userId = {{ $user->id }};

                let url = `{{ route('dashboard-user-profile', 'toreplace') }}`;
                url = url.replace('toreplace', userId);
                console.log(url)
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        type: type
                    },
                    success: function(response) {
                        $("#tabbutton").html(response);
                        loader.hide();


                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            });
        </script>
    @endsection
