<!DOCTYPE html>
<html>
<head>
    <title>Student Record</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Calibri !important;
        }
        table, th, td {
            /*border: 1px solid #e9ecef;*/
        }
        th, td {
            padding: 2px;
            text-align: left;
        }
        td {
            font-size: 9pt;
        }
        .studinfolabel {
            text-align: left;
            font-size: 10pt;
            font-family: sans-serif;
        }
        .studinfoID {
            margin-left: 30px;
        }
    </style>
</head>
<body>
    <div align="center" style="margin-top: -30px">
        <img src="{{ public_path('template/img/eval/studentrecordmain.jpg') }}" width="80%">
    </div>

    <div class="" style="margin-top: 35px">
        <table>
            <thead>
                <th style="font-weight: bold; font-size: 10pt;">Name: &nbsp;&nbsp;&nbsp; {{ $student->fname }} {{ strtoupper(substr($student->mname, 0, 1)) }}. {{ $student->lname }}</th>
                <th class="" style="text-align: left !important; font-size: 10pt; font-weight: initial; color: #000 !important;">Date: &nbsp;&nbsp;{{ \Carbon\Carbon::now()->format('F j, Y') }}</th>
            </thead>
            <thead>
                <th style="font-weight: initial; font-size: 10pt;">Date of Birth: &nbsp;&nbsp;{{ \Carbon\Carbon::parse($student->bday)->format('F j, Y') }}</th>
                <th class="" style="text-align: left !important; font-size: 10pt; font-weight: initial; color: #000 !important;">Place of Birth: &nbsp;&nbsp;{{ $student->pbirth }}</th>
            </thead>
            <thead>
                <th colspan="2" style="text-align: left !important; font-size: 10pt; font-weight: initial; color: #000 !important;">
                @php
                    $address = '';

                    if (!empty($student->brgy)) {
                        $address .= $student->brgy;
                    }

                    if (!empty($student->city)) {
                        $address .= (!empty($address) ? ', ' : '') . $student->city;
                    }

                    if (!empty($student->province)) {
                        $address .= (!empty($address) ? ', ' : '') . $student->province;
                    }
                    $displayAddress = !empty($address) ? $address : 'Address not available';
                @endphp

                Address: &nbsp;&nbsp; {{ $displayAddress }}
                </th>
            </thead>
        </table>
        <center><h6>OTHER PRELIMINARY</h6></center>
    </div>

    <div class="" style="margin-top: 0px">
        <table>
            <thead>
                <th style="font-weight: initial; font-size: 10pt;">Date of Admission: &nbsp;&nbsp;&nbsp; {{ \Carbon\Carbon::parse($student->date_admission)->format('F j, Y') }}</th>
            </thead>
            <thead>
                <th style="font-weight: initial; font-size: 10pt;">Degree/Curriculum: {{ $student->progName }}</th>
            </thead>
            <thead>
                <th style="font-weight: initial; font-size: 10pt;">Major: {{ $student->submamiName }}</th>
            </thead>
        </table>
    </div>

    <div style="border-top: 1px solid #000; margin-top: 20px;"></div>

    <div style="margin-top: 0px">
        <table>
            <thead>
                <th style="font-weight: bold; font-size: 10pt; width: 20%">Subject Code</th>
                <th class="" style="font-weight: bold; font-size: 10pt; width: 48%;">Decriptive Title</th>
                <th class="" style="font-weight: bold; font-size: 10pt;">Rating</th>
                <th class="" style="font-weight: bold; font-size: 10pt;">Comp.</th>
                <th class="" style="font-weight: bold; font-size: 10pt;">Credits</th>
            </thead>
        </table>
    </div>
    <div style="border-top: 1px solid #000; margin-top: 0px;"></div>

    <div>
        @if(!empty($subjectsData))
            @foreach($subjectsData as $schoolYear => $semesters)
                @foreach($semesters as $semester => $subjects)
                    <div style="text-align: center; margin-top: 12px; margin-bottom: 5px; font-weight: bold;">
                        @if($semester == '1') First Semester 
                        @elseif($semester == '2') Second Semester 
                        @elseif($semester == '3') Summer 
                        @endif  
                        {{ $schoolYear }}
                    </div>
                    <table>
                        <tbody>
                            @foreach($subjects as $data)
                            <tr>
                                <td style="font-weight: initial; font-size: 10pt; width: 20%">{{ $data['subject']->sub_name }}</td>
                                <td style="font-weight: initial; font-size: 10pt; width: 48%">{{ $data['subject']->sub_title }}</td>
                                <td style="font-weight: initial; font-size: 10pt;">{{ $data['gpaFgrade'] }}</td>
                                <td style="font-weight: initial; font-size: 10pt;">{{ $data['gpaComp'] }}</td>
                                <td style="font-weight: initial; font-size: 10pt;">{{ $data['subject']->creditEarned }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            @endforeach
        @else
            <p>No data available.</p>
        @endif
    </div>
</body>
</html>
