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
    {{-- <div class="tab-pane fade {{ $type == 'pending' ? 'active' :'' }}" id="pills-profile" role="tabpanel"
        aria-labelledby="pills-profile-tab">.5rd.</div>
    <div class="tab-pane fade {{ $type == 'cashed' ? 'active' :'' }}" id="pills-contact" role="tabpanel"
        aria-labelledby="pills-contact-tab">...</div> --}}
</div>
