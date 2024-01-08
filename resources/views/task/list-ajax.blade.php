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
@endforeach