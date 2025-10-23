<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Faculty Verification Code</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .email-header {
            background-color: #1b5e20;
            color: #ffffff;
            text-align: center;
            padding: 18px 0;
            font-size: 20px;
            font-weight: bold;
        }
        .email-body {
            padding: 30px 40px;
            color: #333333;
            line-height: 1.6;
        }
        .email-body p {
            margin: 10px 0;
        }
        .email-body strong {
            color: #1b5e20;
        }
        .schedule-box {
            background-color: #f1f8e9;
            border-left: 4px solid #1b5e20;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        ol {
            margin: 10px 0 20px 20px;
        }
        .note {
            background-color: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
            padding: 12px 16px;
            border-radius: 4px;
            font-size: 14px;
        }
        .email-footer {
            background-color: #f1f1f1;
            color: #666;
            text-align: center;
            padding: 12px;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            Faculty Verification Code
        </div>
        <div class="email-body">

            <p>Hi, <strong>{{ $emailData['faculty_name'] }}</strong>!</p>
            <p>We received a login request for your CISS account using your personal institutional Google account.</p>
            <p>Please use the verification code below to complete your sign-in process:</p>

            <div class="schedule-box">
                <h2>{{ $emailData['verification_code'] }}</h2>
            </div>

            <p>If you did not initiate this login, please ignore this email for your security.</p>

            <p>God Bless!</p>

            <div class="note">
                <strong>Note:</strong> This is a system-generated message. Please do not reply.
            </div>
        </div>
        <div class="email-footer">
            &copy; {{ date('Y') }} Central Philippines State University
        </div>
    </div>
</body>
</html>
