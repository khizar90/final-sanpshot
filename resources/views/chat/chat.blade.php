@extends('layouts.base')
@section('title', 'Levels')
@section('main', 'Accounts Management')
@section('link')
    <link rel="stylesheet" href="/assets/vendor/css/pages/app-chat.css" />


@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="app-chat card  overflow-hidden">
                <div class="row g-0 ">

                    <!-- Chat & Contacts -->
                    <div class="col app-chat-contacts app-sidebar flex-grow-0 overflow-hidden border-end"
                        id="app-chat-contacts">
                        <div class="sidebar-header">
                            <div class="text-center my-2 me-lg-0">
                                Admin Support

                            </div>
                            <i class="ti ti-x cursor-pointer d-lg-none d-block position-absolute mt-2 me-1 top-0 end-0"
                                data-overlay data-bs-toggle="sidebar" data-target="#app-chat-contacts"></i>
                        </div>
                        <hr class="container-m-nx m-0" />
                        <div class="sidebar-body">
                            <div class="chat-contact-list-item-title">
                                <h5 class="text-primary mb-0 px-4 pt-3 pb-2">Chats</h5>
                            </div>
                            <!-- Chats -->
                            <ul class="list-unstyled chat-contact-list" id="chat-list">

                                <li class="chat-contact-list-item chat-list-item-0 d-none">
                                    <h6 class="text-muted mb-0">No Chats Found</h6>
                                </li>

                                @foreach ($latestMessagesData as $data)
                                    <li class="chat-contact-list-item" data-user-id="{{ $data->user_id }}"
                                        data-conversation-url="{{ route('dashboard-message-conversation', $data->user->id) }}">
                                        <a class="d-flex align-items-center">
                                            <div class="flex-shrink-0 avatar">
                                                <img src="{{ $data->user->image != '' ? asset('profile/' . $data->user->image) : '../../assets/img/app_icon.png' }}"
                                                    alt="Avatar" class="rounded-circle" />
                                            </div>
                                            <div class="chat-contact-info flex-grow-1 ms-2">
                                                <h6 class="chat-contact-name text-truncate m-0">{{ $data->user->name }}</h6>
                                                <p class="chat-contact-status text-muted text-truncate mb-0">
                                                    {{ $data->message }}
                                                </p>
                                            </div>
                                            {{-- <small class="text-muted mb-auto">5 Minutes</small> --}}
                                        </a>
                                        <hr>

                                    </li>
                                @endforeach

                            </ul>

                        </div>
                    </div>
                    <!-- /Chat contacts -->

                    <!-- Chat History -->
                    <div class="col app-chat-history bg-body">
                        <div class="center-container-spiner" style="display: none">
                            <div class="sk-wave sk-primary">
                                <div class="sk-wave-rect"></div>
                                <div class="sk-wave-rect"></div>
                                <div class="sk-wave-rect"></div>
                                <div class="sk-wave-rect"></div>
                                <div class="sk-wave-rect"></div>
                            </div>
                        </div>
                        <div class="chat-history-wrapper" id="chat-history">




                        </div>
                    </div>
                    <!-- /Chat History -->



                    <div class="app-overlay"></div>
                </div>
            </div>
        </div>
    @endsection

    @section('script')
    <script src="/assets/js/app-chat.js"></script>

        <script>
            function scrollToBottom() {
                var chatHistory = document.getElementById('chat-history-body');
                chatHistory.scrollTop = chatHistory.scrollHeight;

            }

            $(document).ready(function() {
                var chatHistory = $('#chat-history');

                $('.chat-contact-list-item').on('click', function(e) {
                    var spinlodaer = $('.center-container-spiner');

                    spinlodaer.show();



                    var userId = $(this).data('user-id'); // Get the user_id from the data attribute
                    var url = '{{ route('dashboard-message-conversation', 'user_id') }}';
                    url = url.replace('user_id', userId);
                    chatHistory.empty();

                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(response) {
                            // $('.app-chat-history').show();
                            spinlodaer.hide()
                            $("#chat-history").html(response)
                            scrollToBottom();
                            console.log('AJAX request successful');
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                });

            });

            $(document).ready(function() {
                $(document).on('submit', '#messageForm', function(e) {
                    e.preventDefault();
                    var loader = $('#loader');
                    var sending = $('#sending');
                    var sendicon = $('#sendicon');

                    loader.show()
                    sendicon.hide();
                    sending.hide();

                    var formData = new FormData(this);
                    $('.message-input').val('');

                    $.ajax({
                        type: "POST",
                        url: '{{ route('dashboard-message-send') }}',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            loader.hide()
                            sendicon.show();
                            sending.show();
                            console.log(response);

                            var newMessage = `
                                <li class="chat-message chat-message-right">
                                    <div class="d-flex overflow-hidden">
                                        <div class="chat-message-wrapper flex-grow-1">
                                            <div class="chat-message-text">
                                                <p class="mb-0">${response.message}</p>

                                            </div> 
                                            
                                        </div>
                                        <div class="user-avatar flex-shrink-0 ms-3">
                                            <div class="avatar avatar-sm">
                                                <img src="../../assets/img/avatars/1.png" alt="Avatar" class="rounded-circle" />
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                `;

                            $('#list-unstyled').append(newMessage);
                            scrollToBottom();


                         

                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                });
            });
        </script>


    @endsection
