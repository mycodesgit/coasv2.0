<!DOCTYPE html>
<html>
<head>
    <title>Examination Schedule</title>
</head>
<body>
    <p>Congratulations {{ $emailData['applicant_name'] }}!</p>
    <p>You have successfully registered for the 2025 Admission Test.</p>

    <p><strong>Schedule of Examination:</strong></p>
    <p><strong>Date:</strong> {{ $emailData['date'] }}</p>
    <p><strong>Time:</strong> {{ $emailData['time'] }}</p>
    <p><strong>Venue:</strong> {{ $emailData['venue'] }}</p>

    <p>Please bring the following:</p>
    <ol>
        <li>School ID or any valid ID</li>
        <li>Pencil with Eraser</li>
    </ol>

    <p>Good luck!</p>
</body>
</html>
