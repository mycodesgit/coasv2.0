<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>CISS - Student Kiosk Login</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('template/plugins/toastr/toastr.min.css') }}">
    <!-- Logo for demo purposes -->
    <link rel="shortcut icon" type="" href="{{ asset('template/img/CPSU_L.png') }}">

    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap');

        body{
            font-family: 'Poppins', sans-serif;
            background: #ececec;
        }

        /*------------ Login container ------------*/

        .box-area{
            width: 930px;
        }

        /*------------ Right box ------------*/

        .right-box{
            padding: 40px 30px 40px 40px;
        }

        /*------------ Custom Placeholder ------------*/

        ::placeholder{
            font-size: 16px;
        }

        .rounded-4{
            border-radius: 20px;
        }
        .rounded-5{
            border-radius: 30px;
        }


        /*------------ For small screens------------*/

        @media only screen and (max-width: 768px){

             .box-area{
                margin: 0 10px;

             }
             .left-box{
                height: 100px;
                overflow: hidden;
             }
             .right-box{
                padding: 20px;
             }

        }

        

        @media (min-width: 769px) { 
            #cpsulogoImage {
                display: none !important; /* Default: Hidden */
            }
        }

        @media (max-width: 768px) { 
            #cpsulogoleftsideImage {
                display: none !important; Ensure it's shown on mobile
            }
        }
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
        <div class="row border rounded-5 p-3 bg-white shadow box-area">
            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #04401f;">
                <div id="particles-js"></div>
                <div class="featured-image mb-3">
                    <center><img src="{{ asset('template/img/cpsulogov4.png') }}" class="img-fluid" id="" style="width: 100px; padding-top: 0px;"></center>
                    <p class="text-white" style="font-family: 'Courier New', Courier, monospace; font-weight: 600; font-size: 1.5em !important">Student View Grades</p>
                </div>
                <small class="text-white text-wrap text-center" style="width: 17rem;font-family: 'Courier New', Courier, monospace;">Join and experienced</br></small>
                <center><img src="{{ asset('template/img/studentsimg.png') }}" class="img-fluid" id="cpsulogoleftsideImage" style="width: 60%; padding-top: 0px;"></center>
            </div> 
        
            <div class="col-md-6 right-box">
                <div class="row align-items-center">
                    <div class="header-text mb-4 text-center">
                        <img src="{{ asset('template/img/studentsimg.png') }}" style="width:100px; margin-top: -250px" id="cpsulogoImage">
                        <h2>Hi, Cenphilian</h2>
                        <p>Sign in to view grades</p>

                    </div>
                    <form action="{{ route('emp_login') }}" method="post">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" name="studid" class="form-control form-control-lg bg-light fs-6" placeholder="Student ID number" id="studentIdInput">
                        </div>
                        <div class="input-group mb-1">
                            <input type="password" name="password" class="form-control form-control-lg bg-light fs-6" placeholder="Password" id="studentPassInput">
                        </div>
                        <div class="input-group mb-5 d-flex justify-content-between">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="formCheck" onclick="myFunction()">
                                <label for="formCheck" class="form-check-label text-secondary"><small>Show Password</small></label>
                            </div>
                            <div class="forgot">
                                {{-- <small><a href="#">Forgot Password?</a></small> --}}
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <button class="btn btn-lg btn-success w-100 fs-6">Login</button>
                        </div>
                    </form>
                </div>
            </div> 
            <span style="font-size: 9pt; text-align: center; margin-top: 10px;">Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca.</span>
        </div>
    </div>

    <!-- Home Button -->
    <a href="https://system.cpsu.edu.ph/kioskallinone-app/public" style="color: white; text-decoration: none; font-size: 1.5em;" >
        <div style="position: fixed; bottom: 35px; left: 60px; width: 90px; height: 60px; background: #007B3A; border-radius: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer;" class="goback">
            <i class="fas fa-circle-chevron-left"></i><span style="font-size: 12pt; margin-left: 5px">Back</span>
        </div>
    </a>

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
        <button class="key-btn key-letter" data-key="C">C</button>
        <button class="key-btn key-letter" data-key="U">U</button>
        <button class="key-btn key-letter" data-key="G">G</button>

        <!-- Control Keys -->
        <button class="key-btn key-control" data-key="backspace">
            DEL
        </button>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Moment -->
    <script src="{{ asset('template/plugins/moment/moment.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('particles/particles.js') }}"></script>
    <script src="{{ asset('particles/app.js') }}"></script>
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
    
    <script>
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
                    // Add the 'clicked' class
                    button.classList.add('clicked');
        
                    // Remove the class after the animation duration to allow re-click
                    setTimeout(() => {
                        button.classList.remove('clicked');
                    }, 300); // Duration matches the animation time
                });
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('.goback');
        
            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    // Add the 'clicked' class
                    button.classList.add('clicked');
        
                    // Remove the class after the animation duration to allow re-click
                    setTimeout(() => {
                        button.classList.remove('clicked');
                    }, 300); // Duration matches the animation time
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
    </script>

    <script>
        // Set the inactivity timeout in milliseconds (2 seconds)
        const inactivityTime = 12000;

        // Function to redirect to the target URL
        function redirectToHome() {
            window.location.href = 'http://localhost/kioskallinone-app/public/';
        }

        // Initialize a timeout variable
        let inactivityTimer;

        // Reset the inactivity timer
        function resetInactivityTimer() {
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(redirectToHome, inactivityTime);
        }

        // Attach event listeners for user interaction
        document.addEventListener('mousemove', resetInactivityTimer);
        document.addEventListener('keydown', resetInactivityTimer);
        document.addEventListener('click', resetInactivityTimer);
        document.addEventListener('touchstart', resetInactivityTimer);

        // Start the timer when the page loads
        window.onload = resetInactivityTimer;
    </script>
</body>
</html>