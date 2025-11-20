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
    <link rel="stylesheet" href="{{ asset('template/dist/css/chatstyle.css') }}">
    

    <link rel="shortcut icon" type="" href="{{ asset('template/img/CPSU_L.png') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/fullcalendar/fullcalendar.css') }}">

    <style>
        #chat-box {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 260px;
            background: white;
            border-radius: 10px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: none;
            flex-direction: column;
            overflow: hidden;
        }

        #chat-header {
            background: #007bff;
            color: white;
            padding: 10px;
            cursor: pointer;
        }

        #chat-messages {
            height: 260px;
            overflow-y: auto;
            padding: 10px;
            background: #f8f9fa;
        }

        #chat-input-area {
            padding: 10px;
            border-top: 1px solid #ddd;
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
        {{-- <div class="fab" id="openChat">
            <i class="fas fa-comment-dots"></i>
        </div>

        <div class="chat-popup" id="chatPopup">
            <div class="chat-header">
                <span>ChatBot</span>
                <button class="close-chat" id="closeChat">&times;</button>
            </div>
            <div class="chat-body">

            </div>
            <div class="chat-input">
                <input type="text" placeholder="Send a message..." id="msgInput">
                <button id="sendBtn"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div> --}}
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
        // Authenticated user
        window.USER_ID = "{{ auth()->id() }}";
        // For admin: set dynamically when selecting student
        window.RECEIVER_ID = null; 
    </script>

    <script>
const USER_ID = window.USER_ID ?? null;
let RECEIVER_ID = window.RECEIVER_ID ?? null;

const fab = document.getElementById('openChat');
const popup = document.getElementById('chatPopup');
const closeBtn = document.getElementById('closeChat');
const sendBtn = document.getElementById('sendBtn');
const msgInput = document.getElementById('msgInput');
const chatBody = document.querySelector('.chat-body');

// Open chat popup
fab.addEventListener('click', () => {
    popup.style.display = popup.style.display === 'flex' ? 'none' : 'flex';
    loadMessages();
});

// Close chat
closeBtn.addEventListener('click', () => popup.style.display = 'none');

// Open chat for a specific student (admin only)
function openChat(studentId) {
    RECEIVER_ID = studentId;
    popup.style.display = 'flex';
    loadMessages();
}

// Load messages
function loadMessages() {
    if (!RECEIVER_ID) return;

    fetch(`/chat/messages?receiver_id=${RECEIVER_ID}`)
        .then(res => res.json())
        .then(messages => {
            chatBody.innerHTML = "";

            if (messages.length === 0) {
                chatBody.innerHTML = `<div class="message bot">No messages yet.</div>`;
                return;
            }

            messages.forEach(msg => {
                const div = document.createElement("div");
                div.classList.add("message");

                if (msg.sender_id == USER_ID) {
                    div.classList.add("user");
                } else {
                    div.classList.add("bot");
                }

                div.textContent = msg.message;
                chatBody.appendChild(div);
            });

            chatBody.scrollTop = chatBody.scrollHeight;
        });
}

// Polling every 2 seconds
setInterval(loadMessages, 2000);

// Send message
function sendMessage() {
    const text = msgInput.value.trim();
    if (!text || !RECEIVER_ID) return;

    fetch("/chat/send", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            message: text,
            receiver_id: RECEIVER_ID
        })
    })
    .then(res => res.json())
    .then(() => {
        msgInput.value = "";
        loadMessages();
    });
}

sendBtn.addEventListener('click', sendMessage);
msgInput.addEventListener('keypress', e => {
    if (e.key === 'Enter') sendMessage();
});
</script>

</body>

</html>