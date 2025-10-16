<!DOCTYPE html>
<html>
<head>
    <title>Examination Schedule</title>
</head>
<body>
    @php
        use App\Models\AdmissionDB\Year;
        $year = Year::where('status', 'On')->value('adyear');
    @endphp
    
    <p>Congratulations {{ $emailData['applicant_name'] }}!</p>
    <p>You have successfully registered for the {{ $year }} Admission Test.</p>

    <p><strong>Schedule of Examination:</strong></p>
    <p><strong>Date:</strong> {{ $emailData['date'] }}</p>
    <p><strong>Time:</strong> {{ $emailData['time'] }}</p>
    <p><strong>Venue:</strong> {{ $emailData['venue'] }}</p>

    <p>Please bring the following:</p>
    <ol>
        <li>School ID or any valid ID</li>
        <li>Pencil with Eraser</li>
    </ol>

    <p>God Bless!</p>

    <p>
        <span style="font-weight: bold">Note: This is a system-generated message. Please do not reply.</span>
    </p>
</body>
</html>
