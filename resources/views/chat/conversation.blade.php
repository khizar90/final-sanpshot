<div class="chat-history-header border-bottom">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex overflow-hidden align-items-center">
            <i class="ti ti-menu-2 ti-sm cursor-pointer d-lg-none d-block me-2" data-bs-toggle="sidebar" data-overlay
                data-target="#app-chat-contacts"></i>
            <div class="flex-shrink-0 avatar">
                <img src="{{ $user->image != '' ? asset('profile/' . $user->image) : '../../assets/img/app_icon.png' }}"
                    alt="Avatar" class="rounded-circle" data-bs-toggle="sidebar" data-overlay
                    data-target="#app-chat-sidebar-right" />
            </div>
            <div class="chat-contact-info flex-grow-1 ms-2">
                <h6 class="m-0" id="user-name">{{ $user->name }}</h6>
                <small
                    class="user-status text-muted">{{ $user->phone }}</small>
            </div>
        </div>

    </div>
</div>
<div class="chat-history-body bg-body" id="chat-history-body">
    <ul class="list-unstyled chat-history" id="list-unstyled">
        @foreach ($messages as $message)
            @if ($message->sendBy == 'admin')
                <li class="chat-message chat-message-right">
                    <div class="d-flex overflow-hidden">
                        <div class="chat-message-wrapper flex-grow-1">
                            <div class="chat-message-text">
                                <p class="mb-0">{{ $message->message }}</p>
                            </div>
                            {{-- <div class="text-end text-muted mt-1">
                                <i class="ti ti-checks ti-xs me-1 text-success"></i>
                            </div> --}}
                        </div>
                        <div class="user-avatar flex-shrink-0 ms-3">
                            <div class="avatar avatar-sm">
                                <img src="../../assets/img/avatars/1.png" alt="Avatar" class="rounded-circle" />
                            </div>
                        </div>
                    </div>
                </li>
            @else
                <li class="chat-message">
                    <div class="d-flex overflow-hidden">
                        <div class="user-avatar flex-shrink-0 me-3">
                            <div class="avatar avatar-sm">
                                <img src="../../assets/img/app_icon.png" alt="Avatar" class="rounded-circle" />
                            </div>
                        </div>
                        <div class="chat-message-wrapper flex-grow-1">
                            <div class="chat-message-text">
                                <p class="mb-0">{{ $message->message }}
                                </p>
                            </div>

                            {{-- <div class="text-muted mt-1">
                                <small>10:02 AM</small>
                            </div> --}}
                        </div>
                    </div>
                </li>
            @endif
        @endforeach



    </ul>
</div>

<!-- Chat message form -->
<div class="chat-history-footer shadow-sm">
    <form class="form-send-message d-flex justify-content-between align-items-center" id="messageForm"
        enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <input class="form-control message-input border-0 me-3 shadow-none" placeholder="Type your message here"
            name="message" />
        <div class="message-actions d-flex align-items-center">

            <button type="submit" class="btn btn-primary d-flex send-msg-btn" id="sendMessage">
                <i class="ti ti-send me-md-1 me-0" id="sendicon"></i>
                <span class="align-middle" id="sending">Send</span>


                <span class="align-middle spinner-border text-dark" style="display: none" id="loader" role="status">
                    <span class="visually-hidden">Loading...</span>
                </span>
            </button>
        </div>
    </form>





</div>
