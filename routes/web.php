<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MainController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LoginFacultyController;
use App\Http\Controllers\ControlController;
use App\Http\Controllers\ForAllEncryptIDController;
use App\Http\Controllers\AdAdmissionController;
use App\Http\Controllers\AdPrntController;
use App\Http\Controllers\AdCaptureImageController;
use App\Http\Controllers\AdExamineeController;
use App\Http\Controllers\AdConfirmController;
use App\Http\Controllers\AdAcceptedController;

use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\EnstudgradeController;
use App\Http\Controllers\EnSubjectsController;
use App\Http\Controllers\EnreportsController;
use App\Http\Controllers\EnStudentPerCurriculumController;

use App\Http\Controllers\SchedClassCollegeController;
use App\Http\Controllers\SchedClassProgramsController;
use App\Http\Controllers\SchedClassRoomsController;
use App\Http\Controllers\SchedClassEnrollController;
use App\Http\Controllers\SchedFacultyListController;
use App\Http\Controllers\SchedFacultyDesignationController;
use App\Http\Controllers\SchedSubOfferController;
use App\Http\Controllers\SchedClassController;
use App\Http\Controllers\SchedReportsController;

use App\Http\Controllers\StudFundAssessmentController;
use App\Http\Controllers\StudFeeAssessmentController;

use App\Http\Controllers\ScholarshipController;

use App\Http\Controllers\GradingController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware'=>['guest']],function(){
    Route::get('/',[MainController::class,'main'])->name('main');
    Route::get('/linkstorage', function () {
        Artisan::call('storage:link');
    });

    Route::prefix('/portal')->group(function () {
        Route::get('/',[PortalController::class,'index'])->name('admission-portal');
        Route::get('/apply', [PortalController::class, 'admission_apply'])->name('admission-apply');
        Route::get('/getProgramsByCampus', [PortalController::class, 'getProgramsByCampus'])->name('getProgramsByCampus');
        Route::post('/post_admission_apply', [PortalController::class, 'post_admission_apply'])->name('post_admission_apply');
        Route::get('/track',[PortalController::class,'admission_track'])->name('admission_track');
        Route::post('/admission-status', [PortalController::class, 'admission_track_status'])->name('admission_track_status');
    });


    Route::get('/emp', [LoginController::class, 'login'])->name('login');
    Route::post('/emp/user_login', [LoginController::class, 'emp_login'])->name('emp_login');
});

Route::group(['middleware'=>['login_auth']],function(){
    Route::prefix('emp/control')->group(function () {
        Route::get('/', [ControlController::class, 'home'])->name('home');
        Route::get('/logout', [ControlController::class, 'logout'])->name('logout');
    });

    Route::prefix('emp/admission')->group(function () {
        
        Route::get('/', [AdAdmissionController::class, 'index'])->name('admission-index'); 
    
        Route::prefix('applicant')->group(function () {
            Route::get('/add', [AdAdmissionController::class, 'applicant_add'])->name('applicant-add');
            Route::post('applicant-add', [AdAdmissionController::class, 'post_applicant_add'])->name('post-applicant-add');
            Route::get('/list', [AdAdmissionController::class, 'applicant_list'])->name('applicant-list');
            // Route::get('/list/search', [AdAdmissionController::class, 'applicant_list_search'])->name('applicant_list_search');
            Route::get('/list/search/applicants', [AdAdmissionController::class, 'srchappList'])->name('srchappList');
            Route::get('/list/search/applicants/ajax', [AdAdmissionController::class, 'getsrchappList'])->name('getsrchappList');
            Route::get('/list/search/edit/srch/{id}', [AdAdmissionController::class, 'applicant_edit_srch'])->name('applicant_edit_srch');
            Route::get('/list/search/edit/{id}', [AdAdmissionController::class, 'applicant_edit'])->name('applicant_edit');
            Route::post('/applist/encrypt', [ForAllEncryptIDController::class, 'idcrypt'])->name('idcrypt');
            Route::put('/list/search/update/{id}', [AdAdmissionController::class, 'applicant_update'])->name('applicant_update');
            Route::get('/{id}/schedule', [AdAdmissionController::class, 'applicant_schedule'])->name('applicant_schedule');
            Route::get('/delete/{id}', [AdAdmissionController::class, 'applicant_delete'])->name('applicant_delete');
            Route::get('/{id}/confirm', [AdAdmissionController::class, 'applicant_confirm'])->name('applicant_confirm');
            Route::post('/confirm', [AdAdmissionController::class, 'applicant_confirmajax'])->name('applicant_confirmajax');
            Route::get('/slots', [AdAdmissionController::class, 'slots'])->name('slots'); 
            Route::get('/slots/search', [AdAdmissionController::class, 'slots_search'])->name('slots_search');

            Route::get('/{id}/print', [AdPrntController::class, 'applicant_print'])->name('applicant_print');
            Route::get('/{id}/permit', [AdPrntController::class, 'applicant_permit'])->name('applicant_permit');
            Route::get('/{id}/view', [AdPrntController::class, 'applicant_genPDF'])->name('applicant_genPDF');
            Route::get('/{id}/viewPermit', [AdPrntController::class, 'applicant_genPermit'])->name('applicant_genPermit');

            Route::post('/capture/{id}/save', [AdCaptureImageController::class, 'applicant_save_image'])->name('applicant_save_image');
            Route::post('/schedule/{id}/save', [AdAdmissionController::class, 'applicant_schedule_save'])->name('applicant_schedule_save');
            Route::post('/schedule/save', [AdAdmissionController::class, 'applicant_schedulemod_save'])->name('applicant_schedulemod_save');
        });

        Route::prefix('examinee')->group(function () {
            Route::get('/examineeList', [AdExamineeController::class, 'examinee_list'])->name('examinee-list');
            Route::get('/list/srchexamineeList', [AdExamineeController::class, 'srchexamineeList'])->name('srchexamineeList');
            Route::get('/list/srchexamineeList/ajax', [AdExamineeController::class, 'getsrchexamineeList'])->name('getsrchexamineeList');
            Route::get('/list/srchexamineeList/edit/srchexam/{id}', [AdExamineeController::class, 'examinee_edit_srch'])->name('examinee_edit_srch');
            Route::get('/list/srchexamineeList/edit/{id}', [AdExamineeController::class, 'examinee_edit'])->name('examinee_edit');
            Route::get('/delete/{id}', [AdExamineeController::class, 'examinee_delete'])->name('examinee_delete');
            Route::get('/{id}/assignresult', [AdExamineeController::class, 'assignresult'])->name('assignresult');
            Route::put('/result/{id}/save', [AdExamineeController::class, 'examinee_result_save'])->name('examinee_result_save');
            Route::put('/result/{id}/save', [AdExamineeController::class, 'examinee_result_save_nd'])->name('examinee_result_save_nd');
            Route::post('/result/save', [AdExamineeController::class, 'examinee_resultmod_save'])->name('examinee_resultmod_save');
            Route::get('/{id}/confirm', [AdExamineeController::class, 'examinee_confirm'])->name('examinee_confirm');
            Route::post('/confirm', [AdExamineeController::class, 'examinee_confirmajax'])->name('examinee_confirmajax');

            Route::get('/result/list', [AdExamineeController::class, 'result_list'])->name('result-list');
            Route::get('/list/srchexamineeResultList', [AdExamineeController::class, 'srchexamineeResultList'])->name('srchexamineeResultList');
            Route::get('/list/srchexamineeResultList/ajax', [AdExamineeController::class, 'getsrchexamineeResultList'])->name('getsrchexamineeResultList');
            Route::get('/list/printPreEnrolment/srch/{id}', [AdPrntController::class, 'pre_enrolment_print_srch'])->name('pre_enrolment_print_srch');
            Route::get('/list/printPreEnrolment/{id}', [AdPrntController::class, 'pre_enrolment_print'])->name('pre_enrolment_print');
            Route::get('/{id}/view', [AdPrntController::class, 'genPreEnrolment'])->name('genPreEnrolment');
            Route::get('/{id}/print', [AdPrntController::class, 'applicant_print'])->name('applicant_print');
            Route::get('/{id}/confirmResult', [AdExamineeController::class, 'confirmResult'])->name('confirmResult');
            Route::get('/{id}/confirmPreEnrolment', [AdExamineeController::class, 'confirmPreEnrolment'])->name('confirmPreEnrolment');
            Route::post('/confirmPreEnrolment', [AdExamineeController::class, 'examinee_confirmPreEnrolmentajax'])->name('examinee_confirmPreEnrolmentajax');
        });

        Route::prefix('confirm')->group(function () {    
            Route::get('/list', [AdConfirmController::class, 'examinee_confirm'])->name('examinee-confirm');
            Route::get('/list/srchconfirmList', [AdConfirmController::class, 'srchconfirmList'])->name('srchconfirmList');
            Route::get('/list/srchconfirmList/ajax', [AdConfirmController::class, 'getsrchconfirmList'])->name('getsrchconfirmList');
            Route::get('/get-programs', [AdConfirmController::class, 'getCampPrograms'])->name('getCampPrograms');
            Route::get('/list/srchconfirmList/accept', [AdConfirmController::class, 'accept'])->name('accept');
            Route::get('/{id}/deptInterview', [AdConfirmController::class, 'deptInterview'])->name('deptInterview');
            Route::get('/list/conprintPreEnrolment/pdf/{id}', [AdPrntController::class, 'conpre_enrolment_print_srch'])->name('conpre_enrolment_print_srch');
            Route::post('/rating/save', [AdConfirmController::class, 'save_applicantmod_rating'])->name('save_applicantmod_rating');
            Route::put('/rating/{id}/save', [AdConfirmController::class, 'save_applicant_rating'])->name('save_applicant_rating');
            Route::post('/saveapplicant', [AdConfirmController::class, 'examinee_pushAcceptajax'])->name('examinee_pushAcceptajax');
            Route::get('/{id}/saveapplicant', [AdConfirmController::class, 'save_accepted_applicant'])->name('save_accepted_applicant');
            Route::get('/{id}/pushapp', [AdConfirmController::class, 'accepted_push_enroll_applicant'])->name('accepted_push_enroll_applicant');
            Route::get('/{id}/pushapplicant', [AdConfirmController::class, 'save_enroll_applicant'])->name('save_enroll_applicant');
            Route::get('/{id}/applicantAccept', [AdConfirmController::class, 'accept'])->name('accept');

            Route::get('/accepted', [AdAcceptedController::class, 'applicant_accepted'])->name('applicant-accepted');
            Route::get('/list/acceptedList', [AdAcceptedController::class, 'srchacceptedList'])->name('srchacceptedList');
            Route::get('/list/acceptedList/ajax', [AdAcceptedController::class, 'getsrchacceptedListapp'])->name('getsrchacceptedListapp');
            Route::get('/enrolled', [AdAcceptedController::class, 'applicant_enrolled'])->name('applicant-enrolled');
            Route::get('/list/enrolledList', [AdAcceptedController::class, 'srchacceptedEnrolledList'])->name('srchacceptedEnrolledList');
        });

        Route::prefix('configure')->group(function () {    
            Route::get('/', [AdAdmissionController::class, 'configure_admission'])->name('configure_admission');
            Route::post('add', [AdAdmissionController::class, 'add_Program'])->name('add_Program');
            Route::post('addStrand', [AdAdmissionController::class, 'add_Strand'])->name('add_Strand');
            Route::post('addAdmissionDate', [AdAdmissionController::class, 'add_admission_date'])->name('add_admission_date');
            Route::post('addAdmissionTime', [AdAdmissionController::class, 'add_admission_time'])->name('add_admission_time');
            Route::post('addAdmissionVenue', [AdAdmissionController::class, 'add_admission_venue'])->name('add_admission_venue');

            Route::get('/programEdit/edit/{id}', [AdAdmissionController::class, 'edit_program'])->name('edit_program');
            Route::post('/programEdit/update', [AdAdmissionController::class, 'programEdit'])->name('programEdit');
            Route::get('/programDelete/{id}/delete', [AdAdmissionController::class, 'programDelete'])->name('programDelete');

            Route::get('/strandEdit/{id}/edit', [AdAdmissionController::class, 'edit_strand'])->name('edit_strand');
            Route::post('/strandEdit/update', [AdAdmissionController::class, 'strandEdit'])->name('strandEdit');
            Route::get('/strandDelete/{id}/delete', [AdAdmissionController::class, 'strandDelete'])->name('strandDelete');

            Route::get('/dateEdit/{id}/edit', [AdAdmissionController::class, 'edit_date'])->name('edit_date');
            Route::post('/dateEdit/update', [AdAdmissionController::class, 'dateEdit'])->name('dateEdit');
             Route::get('/dateDelete/{id}/delete', [AdAdmissionController::class, 'dateDelete'])->name('dateDelete');

            Route::get('/timeEdit/{id}/edit', [AdAdmissionController::class, 'edit_time'])->name('edit_time');
            Route::post('/timeEdit/update', [AdAdmissionController::class, 'timeEdit'])->name('timeEdit');
            Route::get('/timeDelete/{id}/delete', [AdAdmissionController::class, 'timeDelete'])->name('timeDelete');

            Route::get('/venueEdit/{id}/edit', [AdAdmissionController::class, 'edit_venue'])->name('edit_venue');
            Route::post('/venueEdit/update', [AdAdmissionController::class, 'venueEdit'])->name('venueEdit');
            Route::get('/venueDelete/{id}/delete', [AdAdmissionController::class, 'venueDelete'])->name('venueDelete');
        });

        Route::prefix('reports')->group(function () {    
            Route::get('/applicant', [AdPrntController::class, 'applicant_printing'])->name('applicant_printing');
            Route::post('/applicantReports', [AdPrntController::class, 'applicant_reports'])->name('applicant_reports');
            Route::get('/applicantsReports/PDF', [AdPrntController::class, 'applicantPDF_reports'])->name('applicantPDF_reports');

            Route::get('/schedules', [AdPrntController::class, 'schedules_printing'])->name('schedules_printing');
            Route::get('/schedulesReports', [AdPrntController::class, 'schedules_reports'])->name('schedules_reports');
            Route::get('/schedulesReports/PDF', [AdPrntController::class, 'schedulesPDF_reports'])->name('schedulesPDF_reports');

            Route::get('/no/sched', [AdPrntController::class, 'nosched_printing'])->name('nosched_printing');
            Route::post('/no/sched/reports', [AdPrntController::class, 'nosched_reports'])->name('nosched_reports');
            Route::get('/no/sched/PDF', [AdPrntController::class, 'noschedPDF_reports'])->name('noschedPDF_reports');

            Route::get('/examination', [AdPrntController::class, 'examination_printing'])->name('examination_printing');
            Route::get('/examinationReports', [AdPrntController::class, 'examination_reports'])->name('examination_reports');
            Route::get('/examinationReports/PDF', [AdPrntController::class, 'examinationPDF_reports'])->name('examinationPDF_reports');
            
            Route::get('/qualified', [AdPrntController::class, 'qualified_printing'])->name('qualified_printing');
            Route::get('/qualifiedReports', [AdPrntController::class, 'qualified_reports'])->name('qualified_reports');
            Route::get('/accepted', [AdPrntController::class, 'accepted_printing'])->name('accepted_printing');
            Route::get('/acceptedReports', [AdPrntController::class, 'accepted_reports'])->name('accepted_reports');
            Route::get('/confirm', [AdPrntController::class, 'confirm_printing'])->name('confirm_printing');
            Route::get('/confirmReports', [AdPrntController::class, 'confirm_reports'])->name('confirm_reports');
        });
    });

    Route::prefix('enmod/enrollment')->group(function () {
        
        Route::get('/', [EnrollmentController::class, 'index'])->name('enrollment-index');

        Route::prefix('search')->group(function () {
            Route::get('/student', [EnrollmentController::class, 'searchStud'])->name('searchStud');
            Route::get('/student/enroll', [EnrollmentController::class, 'searchStudEnroll'])->name('searchStudEnroll');
            Route::get('/student/enroll/RF', [EnrollmentController::class, 'studrfprint'])->name('studrfprint');
            Route::get('/fetch-subjects', [EnrollmentController::class, 'fetchSubjects'])->name('fetchSubjects');
            Route::get('/editfetch-subjects', [EnrollmentController::class, 'editfetchSubjects'])->name('editfetchSubjects');
            Route::get('/get-sub-title', [EnrollmentController::class, 'coursefetchSubjects'])->name('coursefetchSubjects');
            Route::get('/get-sub-fee', [EnrollmentController::class, 'fetchFeeSubjects'])->name('fetchFeeSubjects');
            Route::post('/student/enroll/submit', [EnrollmentController::class, 'studEnrollmentCreate'])->name('studEnrollmentCreate');
        });

        Route::prefix('edit')->group(function () {
            Route::get('/student/enroll', [EnrollmentController::class, 'editsearchStud'])->name('editsearchStud');
            Route::get('/student/enroll/view', [EnrollmentController::class, 'editsearchStudRead'])->name('editsearchStudRead');
            Route::get('/student/enroll/editRF', [EnrollmentController::class, 'studrfprint'])->name('studrfprint');
        });

        Route::prefix('gradesheet')->group(function () {
            Route::get('/search', [EnstudgradeController::class, 'studgrade_search'])->name('studgrade_search');
            Route::get('/search/list', [EnstudgradeController::class, 'studgrade_searchlist'])->name('studgrade_searchlist');
            // Route::post('/search/list/studentsGrade', [EnstudgradeController::class, 'geneStudent'])->name('geneStudent');
            Route::get('/search/list/studentsGrade/{id}', [EnstudgradeController::class, 'geneStudent1'])->name('geneStudent1');
        });

        Route::prefix('subjects')->group(function () {
            Route::get('/list', [EnSubjectsController::class, 'subjectsRead'])->name('subjectsRead');
            Route::get('/ajaxsublist', [EnSubjectsController::class, 'getsubjectsRead'])->name('getsubjectsRead');
        });

        Route::prefix('report')->group(function () {
            Route::get('/info/students', [EnreportsController::class, 'studInfo'])->name('studInfo');
            Route::get('/info/students/searchList', [EnreportsController::class, 'studInfo_search'])->name('studInfo_search');
            Route::get('/info/students/view/{id}', [EnreportsController::class, 'studInfo_view'])->name('studInfo_view');

            Route::get('/info/students/curriculum', [EnStudentPerCurriculumController::class, 'studCurr'])->name('studCurr');
            Route::get('/info/students/curriculum/search', [EnStudentPerCurriculumController::class, 'studCurrsearch'])->name('studCurrsearch');
            Route::get('/info/students/curriculum/searchajax', [EnStudentPerCurriculumController::class, 'getstudCurrSearch'])->name('getstudCurrSearch');
        });

    });

    Route::prefix('schedmod/scheduler')->group(function () {
        
        Route::get('/', [SchedClassCollegeController::class, 'index'])->name('scheduler-index');

        Route::prefix('college')->group(function () {
            Route::get('/list', [SchedClassCollegeController::class, 'collegeRead'])->name('collegeRead');
            Route::get('/collegelist/ajaxview', [SchedClassCollegeController::class, 'getcollegeRead'])->name('getcollegeRead');
            Route::post('/collegelist/update', [SchedClassCollegeController::class, 'collegeUpdate'])->name('collegeUpdate');
            Route::post('/collegelist/encrypt', [ForAllEncryptIDController::class, 'idcrypt'])->name('idcrypt');
        });

        Route::prefix('programs')->group(function () {
            Route::get('/list', [SchedClassProgramsController::class, 'programsRead'])->name('programsRead');
            Route::get('/proglist/ajaxview', [SchedClassProgramsController::class, 'getprogramsRead'])->name('getprogramsRead');
        });

        Route::prefix('rooms')->group(function () {
            Route::get('/list', [SchedClassRoomsController::class, 'roomsRead'])->name('roomsRead');
            Route::get('/roomlist/ajaxview', [SchedClassRoomsController::class, 'getroomsRead'])->name('getroomsRead');
        });

        Route::prefix('class')->group(function () {
            Route::get('/list', [SchedClassEnrollController::class, 'courseEnroll_list'])->name('courseEnroll_list');
            Route::get('/list/search', [SchedClassEnrollController::class, 'courseEnroll_list_search'])->name('courseEnroll_list_search');
            Route::get('/list/search/ajaxviewclass', [SchedClassEnrollController::class, 'getclassEnRead'])->name('getclassEnRead');
            Route::post('/list/Add', [SchedClassEnrollController::class, 'classEnrollCreate'])->name('classEnrollCreate');
            Route::post('/list/update', [SchedClassEnrollController::class, 'classEnrolledUpdate'])->name('classEnrolledUpdate');
            Route::get('/list/delete{id}', [SchedClassEnrollController::class, 'classEnrolledDelete'])->name('classEnrolledDelete');
        });

        Route::prefix('subjectOff')->group(function () {
            Route::get('/list/', [SchedSubOfferController::class, 'subjectsOffered'])->name('subjectsOffered');
            Route::get('/list/search', [SchedSubOfferController::class, 'subjectsOffered_search'])->name('subjectsOffered_search');
            Route::get('/list/search/ajaxsuboff', [SchedSubOfferController::class, 'getsubjectsOfferedRead'])->name('getsubjectsOfferedRead');
            Route::get('/get-subname-subcode', [SchedSubOfferController::class, 'fetchSubjectName'])->name('fetchSubjectName');
            Route::post('/list/search/add', [SchedSubOfferController::class, 'subjectsOfferedCreate'])->name('subjectsOfferedCreate');
            Route::post('/list/search/suboff/update', [SchedSubOfferController::class, 'subjectsOfferedUpdate'])->name('subjectsOfferedUpdate');
        });

        Route::prefix('faculty')->group(function () {
            Route::get('/list', [SchedFacultyListController::class, 'faculty_list'])->name('faculty_list');
            Route::get('/flist/search', [SchedFacultyListController::class, 'faculty_listsearch'])->name('faculty_listsearch');
        });

        Route::prefix('designation')->group(function () {
            Route::get('/list', [SchedFacultyDesignationController::class, 'faculty_design'])->name('faculty_design');
            Route::get('/fdlist/search', [SchedFacultyDesignationController::class, 'faculty_design_search'])->name('faculty_design_search');
            Route::post('/fdlist/Add', [SchedFacultyDesignationController::class, 'faculty_designdAdd'])->name('faculty_designdAdd');
            Route::post('/fdlist/update', [SchedFacultyDesignationController::class, 'faculty_designdUpdate'])->name('faculty_designdUpdate');
            Route::get('/getProgramId/{progAcronym}', [SchedFacultyDesignationController::class, 'getProgramId'])->name('getProgramId');
        });

        Route::prefix('schedule')->group(function () {
            Route::get('/class', [SchedClassController::class, 'classSchedRead'])->name('classSchedRead');
            Route::get('/faculty', [SchedClassController::class, 'facultySchedRead'])->name('facultySchedRead');
            Route::get('/room', [SchedClassController::class, 'roomSchedRead'])->name('roomSchedRead');
        });

        Route::prefix('reports')->group(function () {
            Route::get('/list/facultyload', [SchedReportsController::class, 'facultyloadRead'])->name('facultyloadRead');
            Route::get('/list/facultyload/search', [SchedReportsController::class, 'facultyload_search'])->name('facultyload_search');
        });

    });

    Route::prefix('assessmod/assessment')->group(function () {
        
        Route::get('/', [StudFundAssessmentController::class, 'index'])->name('assessment-index');

        Route::prefix('funds')->group(function () {
            Route::get('/list', [StudFundAssessmentController::class, 'fundsRead'])->name('fundsRead');
            Route::get('/list/ajaxfund', [StudFundAssessmentController::class, 'getfundsRead'])->name('getfundsRead');
            Route::post('/list/add', [StudFundAssessmentController::class, 'fundCreate'])->name('fundCreate');
            Route::post('/list/fund/update', [StudFundAssessmentController::class, 'fundUpdate'])->name('fundUpdate');
            Route::get('/list/fund/delete{id}', [StudFundAssessmentController::class, 'fundDelete'])->name('fundDelete');

            Route::get('/list/coa', [StudFundAssessmentController::class, 'accountCOARead'])->name('accountCOARead');
            Route::get('/list/ajaxcoa', [StudFundAssessmentController::class, 'getaccountCOARead'])->name('getaccountCOARead');
            Route::post('/list/add/coa', [StudFundAssessmentController::class, 'accountCOACreate'])->name('accountCOACreate');
            Route::post('/list/coa/update', [StudFundAssessmentController::class, 'accountCOAUpdate'])->name('accountCOAUpdate');
            Route::get('/list/coa/delete{id}', [StudFundAssessmentController::class, 'accountCOADelete'])->name('accountCOADelete');

            Route::get('/list/accounts/appraisal', [StudFundAssessmentController::class, 'accountAppraisalRead'])->name('accountAppraisalRead');
            Route::get('/list/ajaxaccnt', [StudFundAssessmentController::class, 'getaccountAppraisalRead'])->name('getaccountAppraisalRead');
            Route::post('/list/add/accntapp', [StudFundAssessmentController::class, 'accountAppraisalCreate'])->name('accountAppraisalCreate');
            Route::post('/list/accounts/appraisal/update', [StudFundAssessmentController::class, 'accountAppraisalUpdate'])->name('accountAppraisalUpdate');
            Route::get('/list/accounts/appraisal/delete{id}', [StudFundAssessmentController::class, 'accountAppraisalDelete'])->name('accountAppraisalDelete');
        });

        Route::prefix('studfee')->group(function () {
            Route::get('/search', [StudFeeAssessmentController::class, 'searchStudfee'])->name('searchStudfee');
            Route::get('/search/list', [StudFeeAssessmentController::class, 'list_searchStudfee'])->name('list_searchStudfee');
            Route::get('/search/list/ajaxstudfee', [StudFeeAssessmentController::class, 'getstudFeeRead'])->name('getstudFeeRead');
            Route::post('/search/list/add', [StudFeeAssessmentController::class, 'studFeeCreate'])->name('studFeeCreate');
            Route::post('/search/list/update', [StudFeeAssessmentController::class, 'studFeeUpdate'])->name('studFeeUpdate');
            Route::get('/search/list/delete{id}', [StudFeeAssessmentController::class, 'studFeeDelete'])->name('studFeeDelete');
        });
    }); 

    Route::prefix('studschmod/scholarship')->group(function () {
        
        Route::get('/', [ScholarshipController::class, 'index'])->name('scholarship-index');

        Route::prefix('studScholar')->group(function () {
            Route::get('/list/chedScholar', [ScholarshipController::class, 'chedscholarlist'])->name('chedscholarlist');
            Route::get('/list/chedScholar/ajax', [ScholarshipController::class, 'getchedscholarlist'])->name('getchedscholarlist');
            Route::post('/list/chedScholar/add', [ScholarshipController::class,'chedscholarCreate'])->name('chedscholarCreate');
            Route::post('/list/chedScholar/update', [ScholarshipController::class,'chedscholarUpdate'])->name('chedscholarUpdate');
            Route::get('/list/chedScholar/delete{id}', [ScholarshipController::class, 'chedscholarDelete'])->name('chedscholarDelete');

            Route::get('/list/uniScholar', [ScholarshipController::class, 'unischolarlist'])->name('unischolarlist');
            Route::get('/list/uniScholar/ajax', [ScholarshipController::class, 'getunischolarlist'])->name('getunischolarlist');
            Route::post('/list/uniScholar/add', [ScholarshipController::class,'unischolarCreate'])->name('unischolarCreate');
            Route::post('/list/uniScholar/update', [ScholarshipController::class,'unischolarUpdate'])->name('unischolarUpdate');
            Route::get('/list/uniScholar/delete{id}', [ScholarshipController::class, 'unischolarDelete'])->name('unischolarDelete');

            Route::get('/list/allScholar', [ScholarshipController::class, 'allscholarlist'])->name('allscholarlist');
            Route::get('/list/allScholar/ajax', [ScholarshipController::class, 'getallscholarlist'])->name('getallscholarlist');
            Route::post('/list/allScholar/IDencrypt', [ForAllEncryptIDController::class, 'idcrypt'])->name('idcrypt');
            Route::post('/list/allScholar/add', [ScholarshipController::class,'allscholarCreate'])->name('allscholarCreate');
            Route::post('/list/allScholar/update', [ScholarshipController::class,'allscholarUpdate'])->name('allscholarUpdate');

            Route::get('/list/students/scholar', [ScholarshipController::class, 'chedstudscholarRead'])->name('chedstudscholarRead');
            Route::get('/list/students/scholar/searchlist', [ScholarshipController::class, 'studscholar_searchRead'])->name('studscholar_searchRead');
            Route::get('/list/students/scholar/searchlist/ajax', [ScholarshipController::class, 'getstudscholarSearchRead'])->name('getstudscholarSearchRead');
            Route::post('/list/students/scholar/update', [ScholarshipController::class,'studscholarUpdate'])->name('studscholarUpdate');
            Route::post('/list/students/scholar/encrypt', [ForAllEncryptIDController::class, 'idcrypt'])->name('idcrypt');
        });

        Route::prefix('studenhistory')->group(function () {
            Route::get('/list/search/student', [ScholarshipController::class, 'studEnHistory'])->name('studEnHistory');
            Route::get('/list/search/student/view', [ScholarshipController::class, 'viewsearchStudHistory'])->name('viewsearchStudHistory');
            Route::get('/list/search/student/ajax', [ScholarshipController::class, 'searchStudHistory'])->name('searchStudHistory');
        });
    });

    Route::prefix('estudgrdmod/grades')->group(function () {
        
        Route::get('/', [GradingController::class, 'index'])->name('grading-index');

        Route::prefix('studGrade')->group(function () {
            Route::get('/list', [GradingController::class, 'grades'])->name('grades');
            Route::get('/list/view/studgrde/{subjID}', [GradingController::class, 'gradesstud'])->name('gradesstud');
            Route::get('/list/viewsearch', [GradingController::class, 'gradesstud_search'])->name('gradesstud_search');
            Route::post('/list/view/studgrde/save', [GradingController::class, 'save_grades'])->name('save_grades');
            Route::post('/list/view/studgrdeComp/save', [GradingController::class, 'save_gradesComp'])->name('save_gradesComp');
            Route::post('/list/view/studgrde/submit/{subjID}', [GradingController::class, 'updateStatus_gradessubmit'])->name('updateStatus_gradessubmit');
            Route::get('/list/view/studgrde/gradesheetPDF/{subjID}', [GradingController::class, 'PDFgradesheetnew'])->name('PDFgradesheetnew');
        });

    });

    Route::prefix('adempset/settings')->group(function () {
        
        Route::get('/', [SettingController::class, 'index'])->name('settings-index');

        Route::prefix('usersAccount')->group(function () {
            Route::get('/list/all/users', [SettingController::class, 'usersRead'])->name('usersRead');
            Route::post('/users/list/add',[SettingController::class,'userCreate'])->name('userCreate');
            Route::get('/list/info', [SettingController::class, 'accountRead'])->name('accountRead');
            Route::get('/list/edituser/{id}', [SettingController::class, 'edit_user'])->name('edit_user');
            Route::post('/list/update', [SettingController::class, 'updateUser'])->name('updateUser');
            Route::post('/list/users/updatePass', [SettingController::class, 'userUpdatePassword'])->name('userUpdatePassword');

            Route::get('/conf', [SettingController::class, 'setconfigure'])->name('setconfigure');
            Route::post('/conf/add',[SettingController::class,'setconfCreate'])->name('setconfCreate');

            Route::get('/confGradeAuthSet', [SettingController::class, 'setgradepassconfigure'])->name('setgradepassconfigure');
            Route::post('/confGradeAuthSet/add',[SettingController::class,'setgradepassconfCreate'])->name('setgradepassconfCreate');
            Route::post('/confGradeAuthSet/update/{id}', [SettingController::class, 'updateGradepass'])->name('updateGradepass');
        });

    });

});


