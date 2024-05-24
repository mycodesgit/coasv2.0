<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>

    <style>

        #table {
            margin-top: 30px;
            font-family: Arial;
            border-collapse: collapse;
            width: 100%;
        }

        #table td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 10pt;
        } 
        #table th {
            border: 1px solid #000;
            padding: 8px;
        }
        #table tfoot {
            border: 1px solid #000;
            padding: 8px;
        }

        #table tr:nth-child(even){background-color: #f2f2f2;}

        #table tr:hover {background-color: #ddd;}

        #table th {
            padding-top: 8px;
            padding-bottom: 8px;
            text-align: center;
            background-color: #fff;
            font-size: 10pt;
        }
        .titletext{
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header style="margin-top: -50px; margin-left: 50px; text-align: center;">
        <img src="{{ asset('template/img/headerimg1.png') }}">
    </header>

    <div class="titletext">
        <p>List of Applicants in {{ request('year') }}</p>    
    </div>

	<table id="table" class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>App ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Course</th>
                <th>Strand</th>
                <th>Interviewer</th>
                <th>Campus</th>
            </tr>
        </thead>
        <tbody>
            @php $no=1; @endphp
            @foreach($data as $dataaccept)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $dataaccept->admission_id }}</td>
                    <td style="text-transform: uppercase;">
                        <b>{{$dataaccept->fname}} 
                            @if($dataaccept->mname == null)
                                @else {{ substr($dataaccept->mname,0,1) }}.
                            @endif {{$dataaccept->lname}}  

                            @if($dataaccept->ext == 'N/A') 
                                @else{{$dataaccept->ext}}
                            @endif
                        </b>
                    </td>
                    <td>
                        @if ($dataaccept->type == 1) New 
                            @elseif($dataaccept->type == 2) Returnee 
                            @elseif($dataaccept->type == 3) Transferee 
                        @endif
                    </td>
                    <td>{{ $dataaccept->course }}</td>
                    <td>{{ $dataaccept->strand }}</td>
                    <td>{{ $dataaccept->interviewer }}</td>
                    <td>{{ $dataaccept->campus }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>