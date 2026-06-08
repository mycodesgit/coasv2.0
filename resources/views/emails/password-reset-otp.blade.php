<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP - CISS V.1.0</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #28a745;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #28a745;
            margin: 0;
            font-size: 24px;
        }
        .otp-code {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
            border-radius: 8px;
            border: 1px dashed #dee2e6;
        }
        .otp-digits {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #dc3545;
            font-family: monospace;
            background: #fff;
            padding: 15px;
            display: inline-block;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .info {
            background-color: #e7f3ff;
            padding: 15px;
            border-left: 4px solid #2196F3;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning {
            background-color: #fff3cd;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                padding: 15px;
            }
            .otp-digits {
                font-size: 24px;
                letter-spacing: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>CISS V.1.0</h1>
            <p>Student Information System</p>
        </div>

        <h2>Password Reset Request</h2>
        
        <p>Dear <strong>{{ $name ?? 'Student' }}</strong>,</p>
        
        <p>We received a request to reset your password for Student ID: <strong>{{ $stud_id ?? 'N/A' }}</strong>.</p>
        
        <p>Use the One-Time Password (OTP) below to complete your password reset:</p>
        
        <div class="otp-code">
            <div class="otp-digits">{{ $otp }}</div>
            <p style="margin-top: 10px; margin-bottom: 0;">
                <small>Enter this code on the password reset page</small>
            </p>
        </div>
        
        <div class="info">
            <strong>⚠️ Important Information:</strong>
            <ul style="margin-top: 10px; margin-bottom: 0;">
                <li>This OTP is valid for <strong>{{ $expiry ?? '15 minutes' }}</strong></li>
                <li>Do not share this code with anyone, including CISS staff</li>
                <li>If you didn't request this, please ignore this email</li>
            </ul>
        </div>
        
        <div class="warning">
            <strong>🔒 Security Tip:</strong>
            <p style="margin-top: 5px; margin-bottom: 0;">Always create a strong password with at least 8 characters, including uppercase, lowercase, numbers, and special characters.</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} CISS V.1.0 - Student Information System. All rights reserved.</p>
            <p><small>If you need assistance, please contact your school administrator.</small></p>
        </div>
    </div>
</body>
</html>