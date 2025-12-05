@extends('layouts.master_faculty')

@section('title')
CISS V.1.0 || Grading
@endsection

@section('sideheader')
<h4>Grading</h4>
@endsection

@section('sideheaderlegend')
<h4>Legend</h4>
@endsection

@yield('sidemenu')

@section('workspace')
    <section class="section mt-4">
        <!-- <div class="section-header" style="border-radius: 20px !important;">
            <h1>Blank Page</h1>
        </div> -->

        <div class="section-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" style="border-radius: 20px">
                        <div class="card-body">
                            <table id="example1" class="table table-hover styled-table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Semester</th>
                                        <th>A.Y.</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($progen as $dataprogen)
                                    <tr>
                                        <td>
                                            <span style="color: transparent">{{ $no++ }}</span>
                                            @if($dataprogen->semester == 1)
                                                1st Semester
                                            @elseif($dataprogen->semester == 2)
                                                2nd Semester
                                            @elseif($dataprogen->semester == 3)
                                                Summer
                                            @else
                                                Unknown Semester
                                            @endif
                                        </td>
                                        <td>{{ $dataprogen->schlyear }}</td>
                                        <td>
                                            <a href="{{ route('virtualfaculty_class', ['semester' => $dataprogen->semester, 'schlyear' => $dataprogen->schlyear]) }}" type="button" class="btn btn-success btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
