<!DOCTYPE html>
<html>
<head>
    <title>Enrollment History</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            font-family: Calibri !important;
            color: #000;
        }
        table, th, td {
            border: 1px solid #e9ecef;
        }
        th{
            font-size: 12pt;
        }
        th, td {
            padding: 4px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div align="center" style="margin-top: -30px">
        <img src="{{ asset('template/img/studcourseheaderMain.png') }}" width="80%">
    </div>
    <div align="center" style="font-family: Calibri !important; color: #000 !important; margin-top: -35px;">
        <h4>
            @if(request('semester') == 1)
                1st Semester {{ request('schlyear') }}
            @elseif(request('semester') == 2)
                2nd Semester {{ request('schlyear') }}
            @elseif(request('semester') == 3)
                Summer {{ request('schlyear') }}
            @else
                Unknown
            @endif
        </h4>
    </div>

    <div align="center" style="font-family: Calibri !important; color: #000 !important; margin-top: -10px;">
        <h3>
            {{ $enrolledstud->first()->progName }} {{ $enrolledstud->first()->studYear }}-{{ $enrolledstud->first()->studSec }}
        </h3>
    </div>

    @php $maleCount = 0; $femaleCount = 0; @endphp
    @foreach($enrolledstud as $student)
        @if($student->gender == 'Male')
            @php $maleCount++; @endphp
        @else
            @php $femaleCount++; @endphp
        @endif
    @endforeach

    <!-- Table for Male Students -->
    @if($maleCount > 0)
        <h4>Male Students</h4>
        <table class="table table-striped" style="margin-top: -20px">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student ID</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($enrolledstud as $student)
                    @if($student->gender == 'Male')
                        <tr>
                            <td><strong>{{ $no++ }}</strong></td>
                            <td><strong>{{ $student->studentID }}</strong></td>
                            <td><strong>{{ $student->lname }}, {{ $student->fname }} {{ $student->mname ? substr($student->mname, 0, 1).'.' : '' }} {{ $student->ext != 'N/A' ? $student->ext : '' }}</strong></td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @endif

    @if($femaleCount > 0)
        <h4>Female Students</h4>
        <table class="table table-striped" style="margin-top: -20px">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student ID</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($enrolledstud as $student)
                    @if($student->gender == 'Female')
                        <tr>
                            <td><strong>{{ $no++ }}</strong></td>
                            <td><strong>{{ $student->studentID }}</strong></td>
                            <td><strong>{{ $student->lname }}, {{ $student->fname }} {{ $student->mname ? substr($student->mname, 0, 1).'.' : '' }} {{ $student->ext != 'N/A' ? $student->ext : '' }}</strong></td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>
