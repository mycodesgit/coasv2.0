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
                <th style="font-weight: bold; font-size: 10pt;">Name: &nbsp;&nbsp;&nbsp; {{ $studrepcard->fname }} {{ strtoupper(substr($studrepcard->mname, 0, 1)) }}. {{ $studrepcard->lname }}</th>
                <th class="" style="text-align: left !important; font-size: 10pt; font-weight: initial; color: #000 !important;">Date: &nbsp;&nbsp;{{ \Carbon\Carbon::now()->format('F j, Y') }}</th>
            </thead>
            <thead>
                <th style="font-weight: initial; font-size: 10pt;">Date of Birth: &nbsp;&nbsp;{{ \Carbon\Carbon::parse($studrepcard->bday)->format('F j, Y') }}</th>
                <th class="" style="text-align: left !important; font-size: 10pt; font-weight: initial; color: #000 !important;">Place of Birth: &nbsp;&nbsp;{{ $studrepcard->pbirth }}</th>
            </thead>
            <thead>
                <th colspan="2" style="text-align: left !important; font-size: 10pt; font-weight: initial; color: #000 !important;">
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

                Address: &nbsp;&nbsp; {{ $displayAddress }}
                </th>
            </thead>
        </table>
        <center><h6>OTHER PRELIMINARY</h6></center>
    </div>

    <div class="" style="margin-top: 0px">
        <table>
            <thead>
                <th style="font-weight: initial; font-size: 10pt;">Date of Admission: &nbsp;&nbsp;&nbsp; {{ \Carbon\Carbon::parse($studrepcard->date_admission)->format('F j, Y') }}</th>
            </thead>
            <thead>
                <th style="font-weight: initial; font-size: 10pt;">Degree/Curriculum: {{ $studrepcard->progName }}</th>
            </thead>
            <thead>
                <th style="font-weight: initial; font-size: 10pt;">Major: {{ $studrepcard->submamiName }}</th>
            </thead>
        </table>
    </div>

    <div style="border-top: 1px solid #000; margin-top: 20px;"></div>
        
    <div style="border-top: 1px solid #000; margin-top: 10px;"></div>

    <div style="margin-top: 10px">
        <table>
            <thead>
                <th style="font-weight: bold; font-size: 10pt;" width="62%"><center>SUBJECTS</center></th>
                <th class="" style="font-weight: bold; font-size: 10pt;">FINAL<br> GRADE</th>
                <th class="" style="font-weight: bold; font-size: 10pt;">COMPL<br> GRADE</th>
                <th class="" style="font-weight: bold; font-size: 10pt;">CREDITS</th>
            </thead>
        </table>
    </div>
    <div style="border-top: 1px solid #000; margin-top: 10px;"></div>

    <div style="margin-top: 10px">
        
    </div>
</body>
</html>
