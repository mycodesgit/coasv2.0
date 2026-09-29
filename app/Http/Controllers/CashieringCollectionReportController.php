<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PDF;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudentLevel;
use App\Models\EnrollmentDB\YearLevel;
use App\Models\EnrollmentDB\MajorMinor;
use App\Models\EnrollmentDB\StudentStatus;
use App\Models\EnrollmentDB\StudentType;
use App\Models\EnrollmentDB\StudentShifTrans;
use App\Models\EnrollmentDB\StudEnrolmentHistory;
use App\Models\EnrollmentDB\DeleteEnrollmentLogs;

use App\Models\AssessmentDB\Funds;
use App\Models\AssessmentDB\AccountCoa;
use App\Models\AssessmentDB\AccountAppraisal;
use App\Models\AssessmentDB\StudPayment;
use App\Models\AssessmentDB\ORComments;

use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\EnPrograms;

use App\Models\SettingDB\ConfigureCurrent;

class CashieringCollectionReportController extends Controller
{
    public function index()
    {
        return view('cashier.officialreceipt.reports.list_collection');
    }

    public function store(Request $request)
    {
        $datepaid = $request->query('datesearch');
        $campus = Auth::guard('web')->user()->campus;

        [$startDate, $endDate] = explode(' - ', $datepaid);

        // Parse the dates to Carbon instances
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        return view('cashier.officialreceipt.reports.list_collectionresult');
    }

    public function show(Request $request)
    {
        $datepaid = $request->query('datesearch');

        if (!$datepaid) {
            return response()->json(['data' => []]);
        }

        $campus = Auth::guard('web')->user()->campus;

        [$startDate, $endDate] = explode(' - ', $datepaid);

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate   = Carbon::parse($endDate)->endOfDay();

        $data = StudPayment::join(
                'coasv2_db_enrollment.students',
                'studpayment.studID',
                '=',
                'coasv2_db_enrollment.students.stud_id'
            )
            ->where('studpayment.campus', $campus)
            ->whereBetween('studpayment.datepaid', [$startDate, $endDate])

            ->select(
                'coasv2_db_enrollment.students.lname',
                'coasv2_db_enrollment.students.fname',
                'coasv2_db_enrollment.students.mname',

                'studpayment.orno',
                'studpayment.studID',
                'studpayment.datepaid',
                'studpayment.campus',
                'studpayment.semester',
                'studpayment.schlyear',

                // Certification
                DB::raw("
                    SUM(
                        CASE
                            WHEN studpayment.account LIKE 'CERT. FEE%'
                            THEN studpayment.amountpaid
                            ELSE 0
                        END
                    ) as certification
                "),

                // Tuition - accepts TUITION - GS, TUITION - BSIT, etc.
                DB::raw("
                    SUM(
                        CASE
                            WHEN studpayment.account LIKE 'TUITION -%'
                            THEN studpayment.amountpaid
                            ELSE 0
                        END
                    ) as tuition
                "),

                // Admission
                DB::raw("
                    SUM(
                        CASE
                            WHEN studpayment.account = 'ENTRANCE FEE'
                            THEN studpayment.amountpaid
                            ELSE 0
                        END
                    ) as admission
                "),

                // Athletics
                DB::raw("
                    SUM(
                        CASE
                            WHEN studpayment.account = 'ATHLETIC FEE'
                            THEN studpayment.amountpaid
                            ELSE 0
                        END
                    ) as athletics
                "),

                // Computer Lab
                DB::raw("
                    SUM(
                        CASE
                            WHEN studpayment.account = 'COMPUTER LAB FEE'
                            THEN studpayment.amountpaid
                            ELSE 0
                        END
                    ) as computer_lab
                "),

                // Cultural
                DB::raw("
                    SUM(
                        CASE
                            WHEN studpayment.account = 'CULTURAL FEE'
                            THEN studpayment.amountpaid
                            ELSE 0
                        END
                    ) as cultural
                "),

                // Developmental
                DB::raw("
                    SUM(
                        CASE
                            WHEN studpayment.account = 'DEVELOPMENTAL FEE'
                            THEN studpayment.amountpaid
                            ELSE 0
                        END
                    ) as developmental
                "),

                // Guidance
                DB::raw("
                    SUM(
                        CASE
                            WHEN studpayment.account = 'GUIDANCE FEE'
                            THEN studpayment.amountpaid
                            ELSE 0
                        END
                    ) as guidance
                "),

                // Laboratory
                DB::raw("
                    SUM(
                        CASE
                            WHEN studpayment.account = 'LAB FEE'
                            THEN studpayment.amountpaid
                            ELSE 0
                        END
                    ) as laboratory
                "),

                // Library
                DB::raw("
                    SUM(
                        CASE
                            WHEN studpayment.account LIKE 'LIBRARY FEE%'
                            THEN studpayment.amountpaid
                            ELSE 0
                        END
                    ) as library
                "),

                // Medical
                DB::raw("
                    SUM(
                        CASE
                            WHEN studpayment.account = 'MEDICAL/DENTAL FEE'
                            THEN studpayment.amountpaid
                            ELSE 0
                        END
                    ) as medical
                "),

                // Registration
                DB::raw("
                    SUM(
                        CASE
                            WHEN studpayment.account = 'REGISTRATION FEE'
                            THEN studpayment.amountpaid
                            ELSE 0
                        END
                    ) as registration
                "),

                // Student ID
                DB::raw("
                    SUM(
                        CASE
                            WHEN studpayment.account LIKE 'SCHOOL ID FEE%'
                            THEN studpayment.amountpaid
                            ELSE 0
                        END
                    ) as stud_id_card
                "),
                // Hon Dismissal
                DB::raw("
                    SUM(
                        CASE
                            WHEN studpayment.account LIKE 'HON. DISMISSAL%'
                            THEN studpayment.amountpaid
                            ELSE 0
                        END
                    ) as honorable
                "),
                // Yearbook
                DB::raw("
                    SUM(
                        CASE
                            WHEN studpayment.account LIKE 'YEARBOOK%'
                            THEN studpayment.amountpaid
                            ELSE 0
                        END
                    ) as yearbook
                "),
                // Total
                DB::raw("
                    SUM(
                        CASE
                            WHEN studpayment.account LIKE 'CERT. FEE%'
                                OR studpayment.account LIKE 'TUITION -%'
                                OR studpayment.account LIKE 'ENTRANCE FEE%'
                                OR studpayment.account LIKE 'ATHLETIC FEE%'
                                OR studpayment.account LIKE 'COMPUTER LAB FEE%'
                                OR studpayment.account LIKE 'CULTURAL FEE%'
                                OR studpayment.account LIKE 'DEVELOPMENTAL FEE%'
                                OR studpayment.account LIKE 'GUIDANCE FEE%'
                                OR studpayment.account LIKE 'LAB FEE%'
                                OR studpayment.account LIKE 'LIBRARY FEE%'
                                OR studpayment.account LIKE 'MEDICAL/DENTAL FEE%'
                                OR studpayment.account LIKE 'REGISTRATION FEE%'
                                OR studpayment.account LIKE 'SCHOOL ID FEE%'
                            THEN studpayment.amountpaid
                            ELSE 0
                        END
                    ) as total
                ")
            )

            ->groupBy(
                'coasv2_db_enrollment.students.lname',
                'coasv2_db_enrollment.students.fname',
                'coasv2_db_enrollment.students.mname',
                'studpayment.orno',
                'studpayment.studID',
                'studpayment.datepaid',
                'studpayment.campus',
                'studpayment.semester',
                'studpayment.schlyear'
            )

            ->orderBy('studpayment.datepaid', 'asc')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
