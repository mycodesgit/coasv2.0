@extends('layouts.master_student')

@section('title')
    CISS V.1.0 || Pre-Enrollment
@endsection

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="content-box">
                <form method="GET" action="" id="enrollStud" class="container-fluid">
                    @csrf
                    <div class="row g-3 align-items-end">

                        <div class="col-12 col-md-3">
                            <label class="form-label mb-1">
                                <span style="font-family: Verdana, sans-serif">Student ID Number</span>
                            </label>
                            <input type="text" name="stud_id" class="form-control form-control-sm"
                                oninput="formatInput(this); this.value = this.value.toUpperCase()">
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label mb-1">
                                <span style="font-family: Verdana, sans-serif">School Year</span>
                            </label>
                            <select class="form-select form-select-sm" name="schlyear">
                                @foreach ($sy as $datasy)
                                    <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label mb-1">
                                <span style="font-family: Verdana, sans-serif">Semester</span>
                            </label>
                            <select class="form-select form-select-sm" name="semester">
                                @foreach ($sy as $datasy)
                                    <option value="{{ $datasy->semester }}">
                                        @if ($datasy->semester == 1)
                                            1st Sem
                                        @elseif($datasy->semester == 2)
                                            2nd Sem
                                        @elseif($datasy->semester == 3)
                                            Summer
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label mb-1 d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-success btn-sm w-100">OK</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function formatInput(input) {
            let cleaned = input.value.replace(/[^A-Za-z0-9]/g, '');
            
            if (cleaned.length > 0) {
                let formatted = cleaned.substring(0, 4) + '-' + cleaned.substring(4, 8) + '-' + cleaned.substring(8, 9);
                input.value = formatted;
            } else {
                input.value = '';
            }
        }

        function handleDelete(event) {
            if (event.key === 'Backspace') {
                let input = event.target;
                let value = input.value;
                input.value = value.substring(0, value.length - 1);
                formatInput(input);
            }
        }

        var selectQueueCatRoute  = "{{ route('counterUserUpdate') }}";
    </script>
@endsection
