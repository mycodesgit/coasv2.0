<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Returnee Students</title>
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
        @if(Auth::guard('web')->user()->campus == 'MC')
            <img src="{{ public_path('template/img/studcur/studcourseheaderMain.png') }}" width="80%">
        @elseif(Auth::guard('web')->user()->campus == 'VC')
            <img src="{{ public_path('template/img/studcur/studcourseheaderVictorias.jpg') }}" width="80%">
        @elseif(Auth::guard('web')->user()->campus == 'SCC')
            <img src="{{ public_path('template/img/studcur/studcourseheaderSanCarlos.jpg') }}" width="80%">
        @elseif(Auth::guard('web')->user()->campus == 'HC')
            <img src="{{ public_path('template/img/studcur/studcourseheaderHinigaran.jpg') }}" width="80%">
        @elseif(Auth::guard('web')->user()->campus == 'MP')
            <img src="{{ public_path('template/img/studcur/studcourseheaderMoisesPadilla.jpg') }}" width="80%">
        @elseif(Auth::guard('web')->user()->campus == 'IC')
            <img src="{{ public_path('template/img/studcur/studcourseheaderIlog.jpg') }}" width="80%">
        @elseif(Auth::guard('web')->user()->campus == 'CA')
            <img src="{{ public_path('template/img/studcur/studcourseheaderCandoni.jpg') }}" width="80%">
        @elseif(Auth::guard('web')->user()->campus == 'CC')
            <img src="{{ public_path('template/img/studcur/studcourseheaderCauayan.jpg') }}" width="80%">
        @elseif(Auth::guard('web')->user()->campus == 'SC')
            <img src="{{ public_path('template/img/studcur/studcourseheaderSipalay.jpg') }}" width="80%">
        @elseif(Auth::guard('web')->user()->campus == 'HinC')
            <img src="{{ public_path('template/img/studcur/studcourseheaderHinoba-an.jpg') }}" width="80%">
        @endif
    </div>
    <div align="center" style="font-family: Calibri !important; color: #000 !important; margin-top: -35px;">
        <h4>
            {{ $semesteractive == 1 ? '1st Semester' : ($semesteractive == 2 ? '2nd Semester' : ($semesteractive == 3 ? 'Summer' : '')) }} - {{ $schlyearactive }}
        </h4>
    </div>
    <h2 style="text-align: center">Returnee Students Enrolled</h2>
    <table border="1" cellspacing="0" cellpadding="5" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Student ID</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $stud)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $stud->studentID }}</td>
                    <td>
                        {{ $stud->lname }},
                        {{ $stud->fname }}
                        @if(!empty($stud->mname) && $stud->mname !== 'N/A')
                            {{ substr($stud->mname, 0,1) }}.
                        @endif
                        @if(!empty($stud->ext) && $stud->ext !== 'N/A')
                            {{ $stud->ext }}
                        @endif
                    </td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
