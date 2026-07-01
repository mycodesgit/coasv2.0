<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>CISS - Login</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/bootstrap/css/bootstrap.min.css') }}">
    <!-- Login Design -->
    <link rel="stylesheet" href="{{ asset('uilibs/css/login-style.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/toastr/toastr.min.css') }}">
    <!-- fontawesome -->
    <link rel="stylesheet" href="{{ asset('uilibs/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <!-- Logo -->
    <link rel="shortcut icon" type="" href="{{ asset('uilibs/images/cpsulogov4.png') }}">

    <style type="text/css">
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            /* background-image: url('{{ asset('template/img/bg-campuswifi.png') }}'); */
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 100%;
            z-index: -1;
        }

        /* Numeric Keyboard Container */
        .keyboard-container {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #ffffff;
            border: 3px solid #007B3A;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            gap: 10px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.4);
            z-index: 999;
            /* display: none; */

            /* Animation properties */
            opacity: 0;
            transform: translate(-50%, 50px);
            transition: all 0.5s ease;
            pointer-events: none;
        }

        .keyboard-container.show {
            opacity: 1;
            transform: translate(-50%, 0);
            pointer-events: auto;
        }

        /* Key Buttons */
        .key-btn {
            width: 50px;
            height: 50px;
            font-size: 1.6rem;
            text-align: center;
            cursor: pointer;
            background: #007B3A;
            color: #fff;
            border: none;
            border-radius: 5px;
            transition: transform 0.2s;
        }

        .key-btn.clicked {
            animation: haptic-animation 0.3s ease !important;
        }

        .goback.clicked {
            animation: haptic-animation 0.3s ease !important;
        }

        @keyframes haptic-animation {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        /* Control Buttons (Backspace & Clear) */
        .key-control {
            background: #DC3545;
        }

        .key-control:hover {
            background: #c82333;
        }

        .key-control-dash {
            background: #e9b10a;
        }

        .key-control-dash:hover {
            background: #e9b10a;
        }

        .key-letter {
            background: #6c757d;
        }

        .key-letter:hover {
            background: #6c757d;
        }

        /* Hide Keyboard on Mobile */
        @media (max-width: 768px) {
            .keyboard-container {
                display: none !important;
            }
        }

    </style>
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-icons d-none d-md-flex">
            <i class="fas fa-book-open"></i>
            <i class="fas fa-building"></i>
            <i class="fas fa-graduation-cap"></i>
            <i class="fas fa-book-open"></i>
            <i class="fas fa-building"></i>
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="row border rounded-5 p-3 bg-white shadow box-area">
            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #04401f;">
                {{-- <div id="particles-js"></div> --}}
                <div class="featured-image mb-3">
                    <center><img src="{{ asset('template/img/cpsulogov4.png') }}" class="img-fluid" id="" style="width: 100px; padding-top: 0px;"></center>
                    <p class="text-white text-center" style="font-family: 'Courier New', Courier, monospace; font-weight: 600; font-size: 1.5em !important">CISS</p>
                </div>
                <small class="text-white text-wrap text-center" style="width: 17rem;font-family: 'Courier New', Courier, monospace;">CPSU Integrated System Solution</br></small>
                {{-- <center><img src="{{ asset('template/img/studentsimg.png') }}" class="img-fluid" id="cpsulogoleftsideImage" style="width: 60%; padding-top: 0px;"></center> --}}
            </div> 
        
            <div class="col-md-6 right-box">
                <div class="row align-items-center">
                    <div class="header-text mb-2 text-center">
                        <img src="{{ asset('template/img/cpsulogov4.png') }}" style="width:100px; margin-top: -250px" id="cpsulogoImage">
                        <h2>Hi, Cenphilian</h2>
                        <p>Sign in to start session</p>
                        @php
                            date_default_timezone_set('Asia/Manila');

                            $now = now();
                            $openingDate = \Carbon\Carbon::create(2026, 5, 25, 0, 0, 0, 'Asia/Manila');
                            $startTime = now()->setHour(8)->setMinute(0)->setSecond(0);
                            $endTime = now()->setHour(17)->setMinute(0)->setSecond(0);
                        @endphp
                    </div>
                    <form action="{{ route('stud_login') }}" method="post">
                        @csrf
                        <div class="input-group mb-2">
                            <input type="text" name="studid" class="form-control form-control-lg bg-light fs-6" placeholder="Student ID number" id="studentIdInput">
                        </div>
                        <div class="input-group mb-1">
                            <input type="password" name="password" class="form-control form-control-lg bg-light fs-6" placeholder="Password" id="studentPassInput">
                        </div>
                        <div class="input-group mb-3 d-flex justify-content-between">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="formCheck" onclick="myFunction()">
                                <label for="formCheck" class="form-check-label text-secondary"><small>Show Password</small></label>
                            </div>
                            @if($now->lt($openingDate) || !$now->isWeekday() || $now->lt($startTime) || $now->gte($endTime))
                            @else
                                <div class="forgot">
                                    <small><a href="{{ route('forgot.index') }}" class="">Forgot Password?</a></small>
                                </div>
                            @endif
                        </div>
                        <div class="input-group mb-3">
                            <button class="btn btn-lg btn-success w-100 fs-6">Login</button>
                        </div>
                        {{-- <div class="modal-content">
                            <div class="modal-header text-center justify-content-center mb-3">
                                <h5 class="modal-title">
                                    <i class="fas fa-exclamation-triangle text-warning"></i> Temporarily Unavailable
                                </h5>
                            </div>

                            <div class="modal-body text-center bg-light p-4 rounded-3 mb-2">

                                <p style="font-size: 11pt; font-weight: 500;">
                                    The student login portal is currently undergoing system maintenance. We appreciate your patience while we work to improve the system.
                                
                                    Please return to the home page and try again soon.
                                </p>
                            </div>
                        </div> --}}
                        {{-- <a href="{{ route('main') }}" class="btn btn-outline-warning btn-lg w-100 fs-6 text-bold mb-2 text-dark">Back</a> --}}
                        <div class="text-center"><a href="{{ route('main') }}" class="text-center text-dark" style="text-decoration: none">Back to main page</a></div>
                    </form>
                </div>
            </div> 
            <span style="font-size: 9pt; text-align: center; margin-top: 10px;">Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca.</span>
        </div>
    </div>

    <div id="numericKeyboard" class="keyboard-container">
        <!-- Numeric Keys -->
        <button class="key-btn" data-key="1">1</button>
        <button class="key-btn" data-key="2">2</button>
        <button class="key-btn" data-key="3">3</button>
        <button class="key-btn" data-key="4">4</button>
        <button class="key-btn" data-key="5">5</button>
        <button class="key-btn" data-key="6">6</button>
        <button class="key-btn" data-key="7">7</button>
        <button class="key-btn" data-key="8">8</button>
        <button class="key-btn" data-key="9">9</button>
        <button class="key-btn" data-key="0">0</button>
        <button class="key-btn key-control-dash" data-key="-">
            <i class="fas fa-minus"></i>
        </button>

        <!-- Alphabetic Keys -->
        <button class="key-btn key-letter" data-key="K">K</button>
        <button class="key-btn key-letter" data-key="N">N</button>
        <button class="key-btn key-letter" data-key="R">R</button>
        <button class="key-btn key-letter" data-key="S">S</button>
        <button class="key-btn key-letter" data-key="C">C</button>
        <button class="key-btn key-letter" data-key="U">U</button>
        <button class="key-btn key-letter" data-key="G">G</button>

        <!-- Control Keys -->
        <button class="key-btn key-control" data-key="backspace">
            DEL
        </button>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('uilibs/plugins/jquery/jquery.min.js') }}?v={{ time() }}"></script>
    <!-- Moment -->
    <script src="{{ asset('uilibs/plugins/moment/moment.min.js') }}?v={{ time() }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('uilibs/plugins/toastr/toastr.min.js') }}?v={{ time() }}"></script>
    {{-- <script src="{{ asset('particles/particles.js') }}"></script>
    <script src="{{ asset('particles/app.js') }}"></script> --}}
    <!-- Context -->
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>

    <script>
        $(document).ready(function() {
            @if(session('error'))
                toastr.error("{{ session('error') }}", "Error", {
                    closeButton: false,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 10000
                });
            @endif

            @if(session('success'))
                toastr.success("{{ session('success') }}", "Success", {
                    closeButton: false,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 10000
                });
            @endif
        });
    </script>
    
    {{-- <script>
        document.addEventListener('DOMContentLoaded', () => {
            const keyboard = document.getElementById('numericKeyboard');
            const studentIdInput = document.getElementById('studentIdInput');
            const studentPassInput = document.getElementById('studentPassInput');

            let activeInput = null;

            // Show keyboard with animation
            const showKeyboard = (inputField) => {
                if (window.innerWidth >= 768) {
                    keyboard.classList.add('show');
                    activeInput = inputField;
                }
            };

            studentIdInput.addEventListener('focus', () => showKeyboard(studentIdInput));
            studentPassInput.addEventListener('focus', () => showKeyboard(studentPassInput));

            // Hide keyboard with animation when clicking outside
            document.addEventListener('click', (event) => {
                if (
                    !keyboard.contains(event.target) &&
                    event.target !== studentIdInput &&
                    event.target !== studentPassInput
                ) {
                    keyboard.classList.remove('show');
                }
            });

            // Handle key clicks with event delegation
            keyboard.addEventListener('click', (event) => {
                let key = event.target.dataset.key || event.target.parentElement.dataset.key;
                if (!key || !activeInput) return;

                if (key.length === 1 || key === '-') {
                    activeInput.value += key;
                } 
                else if (key === 'backspace') {
                    activeInput.value = activeInput.value.slice(0, -1);
                } 
                else if (key === 'clear') {
                    activeInput.value = '';
                }
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('.key-btn');
        
            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    button.classList.add('clicked');
        
                    setTimeout(() => {
                        button.classList.remove('clicked');
                    }, 300); 
                });
            });
        });

        function myFunction() {
            var x = document.getElementById("studentPassInput");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script> --}}
    <script>
        document.addEventListener("mousemove", (e) => {
            const icons = document.querySelectorAll(".bg-icons i");

            const x = (e.clientX / window.innerWidth - 0.5) * 20;
            const y = (e.clientY / window.innerHeight - 0.5) * 20;

            icons.forEach((icon, index) => {
                const speed = (index + 1) * 0.5;

                icon.style.transform = `translate(${x * speed}px, ${y * speed}px)`;
                icon.style.transition = "transform 0.2s ease-out";
            });
        });
    </script>
</body>
</html>