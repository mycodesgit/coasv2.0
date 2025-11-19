<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('template/student/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/sched-style.css') }}" media="(min-width: 768px)">

    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('template/plugins/toastr/toastr.min.css') }}">
    <!-- DataTable -->
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('template/student/style.css') }}">
    

    <link rel="shortcut icon" type="" href="{{ asset('template/img/CPSU_L.png') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/fullcalendar/fullcalendar.css') }}">

    <style>
        .floating-chat-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: #28a745;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            cursor: pointer;
            z-index: 1050;
            transition: all 0.3s ease;
            border: 4px solid white;
        }
        .floating-chat-btn:hover {
            transform: scale(1.1);
            background: #218838;
        }
        .floating-chat-btn i {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        .badge-notif {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
    </style>
</head>

<body class="text-sm">
    <div style="height: 25px; background: #fdfdfd; position: fixed; top: 0; left: 0; right: 0; z-index: 998;"></div>
    <nav id="sidebar">
        @include('partials.control_student_sidebar')
    </nav>
    
    <main>

        <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-fixed-custom" style="border-radius: 15px; background-color: #ffffff !important; margin-top: -15px;">
            <div class="container-fluid">
                <a class="navbar-brand text-gray" href="#"><i class="fas fa-diagram-predecessor" style="color: #666"></i> CISS</a>
                <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button> -->
                <div class="collapse navbar-collapse" id="navbarScroll">
                    <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                        <!-- <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li> -->
                    </ul>
                </div>
                <form class="">
                    <a href="{{ route('destory.logout') }}" class="btn btn-default btn-sm btnsignout">
                        <i class="fas fa-power-off"></i> Sign Out
                    </a>
                </form>
            </div>
            <div class="d-block" style="z-index: 999">
                <img src="{{ asset('template/img/cpsulogov4.png') }}" style="width:70px;" class="center-top">
            </div>
        </nav>
        
        <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-fixed-custom d-lg-none" style="border-radius: 15px; background-color: #198754 !important; margin-top: -15px;">
            <div class="container-fluid">
                <a class="navbar-brand text-light" href="#"><i class="fas fa-diagram-predecessor" style="color: #e9ecef"></i> CISS</a>
                <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button> -->
                <div class="collapse navbar-collapse" id="navbarScroll">
                    <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                        <!-- <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li> -->
                    </ul>
                </div>
                <form class="">
                    <a href="{{ route('destory.logout') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-power-off"></i> Sign Out
                    </a>
                </form>
            </div>
            <div class="d-block d-md-none" style="z-index: 999">
                <img src="{{ asset('template/img/cpsulogov4.png') }}" style="width:70px;" class="center-top">
            </div>
        </nav>

        @yield('body')
    </main>

    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/student/app.js') }}" defer></script>

    <!-- Toastr -->
    <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>

    <script src="{{ asset('template/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script> 
    <script src="{{ asset('template/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('template/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('template/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('template/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <script src="{{ asset('template/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('template/plugins/fullcalendar/fullcalendar.js') }}"></script>

    <!-- Toastr -->
    <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    @if(request()->routeIs('schedstudentclassShow'))
        <script>
            var days = @json($days);
            var times = @json($times);
        </script>
        @include('student.scheds.viewscheduleresultscript')
    @endif

    @if(request()->routeIs('pre.show'))
        @include('script.enrllmnt.preenrolSerialize')
    @endif
    
    <script>
        const currentUserId = $('meta[name="current-user-id"]').attr('content') || null;
        let chatReceiverId = null;
        let chatInterval = null;

        // Open chat
        $(document).on('click', '#sendChatEvalButton', function() {
            chatReceiverId = $(this).data('receiver-id');
            const receiverName = $(this).data('receiver-name') || 'User';

            $('#chatModalLabel').text('Chat with ' + receiverName);
            $('#chatMessages').html('<div class="text-center"><small>Loading messages...</small></div>');

            loadMessages();

            const chatModal = document.getElementById('chatModal');
            chatModal.style.display = 'block';
            chatModal.classList.add('show');
            chatModal.setAttribute('aria-hidden', 'false');
            chatModal.setAttribute('aria-modal', 'true');
            chatModal.setAttribute('role', 'dialog');

            // Auto-refresh every 5 seconds
            if (chatInterval) clearInterval(chatInterval);
            chatInterval = setInterval(loadMessages, 5000);

            // Cleanup on close
            $('#chatModal').off('hidden.bs.modal').on('hidden.bs.modal', function () {
                clearInterval(chatInterval);
                chatInterval = null;
                chatReceiverId = null;
            });
        });

        // Load messages
        function loadMessages() {
            if (!chatReceiverId || !currentUserId) return;

            $.get("{{ route('fetchMessages', '') }}/" + chatReceiverId)
                .done(function(data) {
                    let html = '';
                    if (data.length === 0) {
                        html = '<div class="text-center text-muted"><small>No messages yet. Start the conversation!</small></div>';
                    }

                    data.forEach(msg => {
                        const isMe = parseInt(msg.sender_id) === parseInt(currentUserId);
                        const name = isMe ? 'You' : (msg.sender?.name || 'User');
                        const time = moment(msg.created_at).format('MMM D, h:mm A');

                        if (isMe) {
                            html += `
                            <div class="direct-chat-msg right mb-3">
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name float-right text-muted">${name}</span>
                                    <span class="direct-chat-timestamp float-left text-muted">${time}</span>
                                </div>
                                <div class="direct-chat-text bg-primary text-white float-right">${escapeHtml(msg.message)}</div>
                            </div>`;
                        } else {
                            html += `
                            <div class="direct-chat-msg mb-3">
                                <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name float-left">${name}</span>
                                    <span class="direct-chat-timestamp float-right text-muted">${time}</span>
                                </div>
                                <div class="direct-chat-text bg-light border">${escapeHtml(msg.message)}</div>
                            </div>`;
                        }
                    });

                    $('#chatMessages').html(html);
                    scrollToBottom();
                })
                .fail(function() {
                    $('#chatMessages').html('<div class="text-danger text-center">Failed to load messages.</div>');
                });
        }

        // Send message
        $('#sendMessageButton').on('click', sendMessage);
        $('#chatInput').on('keypress', function(e) {
            if (e.which === 13 && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        function sendMessage() {
            const message = $('#chatInput').val().trim();
            if (!message || !chatReceiverId) return;

            $.post("{{ route('sendMessage') }}", {
                receiver_id: chatReceiverId,
                message: message,
                _token: $('meta[name="csrf-token"]').attr('content')
            })
            .done(function(res) {
                if (res.success) {
                    $('#chatInput').val('');
                    loadMessages();
                } else {
                    alert('Failed to send message.');
                }
            })
            .fail(function() {
                alert('Network error. Please try again.');
            });
        }

        function scrollToBottom() {
            const elem = $('#chatMessages')[0];
            elem.scrollTop = elem.scrollHeight;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
</script>
</body>

</html>