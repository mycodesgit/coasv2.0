<!DOCTYPE html>
<html>
<head>
    <title>Student Record</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, Helvetica, sans-serif !important;
            color: #000 !important;
            font-weight: 200 !important;
            font-size: 12pt !important;
        }
        table, th, td {
            border: 4px solid #000000;
        }
        th, td {
            padding: 2px;
            text-align: left;
            border: 4px solid #000000;
        }
        td {
            font-size: 9pt;
            border: 4px solid #000000;
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
    <div class="" style="margin-top: 35px">
        @php
            $address = '';

            if (!empty($studrepcard->brgy)) {
                $address .= $studrepcard->brgy;
            }

            if (!empty($studrepcard->city)) {
                $address .= (!empty($address) ? ', ' : '') . $studrepcard->city;
            }

            if (!empty($studrepcard->province)) {
                $address .= (!empty($address) ? ', ' : '') . $studrepcard->province;
            }
            $displayAddress = !empty($address) ? $address : 'Address not available';
        @endphp
        <table>
            <thead>
                <tr>
                    <th>Name:</th>
                    <th>Date</th>
                    <th>Address</th>
                    <th>Birthday</th>
                    <th>Place of Birth</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $studrepcard->fname ?? 'N/A' }} {{ strtoupper(substr($studrepcard->mname ?? '', 0, 1)) }}. {{ $studrepcard->lname ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::now()->format('F j, Y') }}</td>
                    <td>{{ $displayAddress }}</td>
                    <td>{{ !empty($studrepcard->bday) ? \Carbon\Carbon::parse($studrepcard->bday)->format('F j, Y') : 'N/A' }}</td>
                    <td>{{ $studrepcard->pbirth ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
        <center><h6>OTHER PRELIMINARY</h6></center>
    </div>

    <div class="" style="margin-top: 0px">
        <table>
            <thead>
                <th style="font-weight: initial; font-size: 10pt;">
                    Date of Admission: &nbsp;&nbsp;&nbsp; 
                    {{ !empty($studrepcard->date_admission) ? \Carbon\Carbon::parse($studrepcard->date_admission)->format('F j, Y') : 'N/A' }}
                </th>
            </thead>
            <thead>
                <th style="font-weight: initial; font-size: 10pt;">
                    Degree/Curriculum: {{ $studrepcard->progName ?? 'N/A' }}
                </th>
            </thead>
            <thead>
                <th style="font-weight: initial; font-size: 10pt;">
                    Major: {{ $studrepcard->submamiName ?? 'N/A' }}
                </th>
            </thead>
        </table>
    </div>

    <div style="border-top: 1px solid #000; margin-top: 20px;"></div>

    <div style="border-top: 1px solid #000; margin-top: 0px;"></div>

    <table>
        <tr>
            <td></td>
        </tr>
    </table>
    <div>
        @foreach($subjectsData as $schoolYear => $semesters)
            @foreach($semesters as $semester => $subjects)
                <table>
                    <thead>
                        <tr style="background-color: dimgrey; color: white;">
                            <th colspan="5" style="font-weight: initial; font-size: 10pt; width: 20%">
                                <h6>@if($semester == '1' ) First Semester @elseif ($semester == '2' ) Second Semester @elseif ($semester == '3' ) Summer @endif  {{ $schoolYear }}
                            </th>
                        </tr>
                        <tr>
                            <th style="font-weight: bold; font-size: 10pt; width: 20%">Subject Code</th>
                            <th class="" style="font-weight: bold; font-size: 10pt; width: 48%;">Decriptive Title</th>
                            <th class="" style="font-weight: bold; font-size: 10pt;">Rating</th>
                            <th class="" style="font-weight: bold; font-size: 10pt;">Comp.</th>
                            <th class="" style="font-weight: bold; font-size: 10pt;">Credits</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $data)
                            <tr>
                                <td style="font-weight: initial; font-size: 10pt; width: 20%">{{ $data['subject']->sub_name }}</td>
                                <td class="" style="font-weight: initial; font-size: 10pt; width: 48%;">{{ $data['subject']->sub_title }}</td>
                                <td class="" style="font-weight: initial; font-size: 10pt;">{{ $data['gpaFgrade'] }}</td>
                                <td class="" style="font-weight: initial; font-size: 10pt;">{{ $data['gpaComp'] }}</td>
                                <td class="" style="font-weight: initial; font-size: 10pt;">{{ $data['subject']->creditEarned }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        @endforeach
    </div>
</body>
</html>
