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




                <div class="d-flex flex-column"><a href="#" class="text-body text-truncate"><span
                            class="fw-semibold user-name-text">{{ $user->name }}</span></a>
                    <small class="text-muted">&#64;{{ $user->email }}</small>

                </div>
            </div>
        </td>

        <td>{{ $user->country }}</td>

        <td class="detailbtn">
            <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i
                    class="ti ti-dots-vertical ti-sm mx-1"></i></a>
            <div class="dropdown-menu dropdown-menu-end m-0">
                <a href="" data-bs-toggle="modal" data-bs-target="#Assigntask{{ $user->id }}"
                    class="dropdown-item">
                    @if ($user->assign)
                        Un Assign
                    @else
                        Assign task
                    @endif
                </a>
            </div>
            <div class="modal fade" id="Assigntask{{ $user->id }}" tabindex="-1" aria-hidden="true">
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
