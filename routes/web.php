<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MainController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LoginFacultyController;
use App\Http\Controllers\ControlController;
use App\Http\Controllers\AdAdmissionController;
use App\Http\Controllers\AdPrntController;
use App\Http\Controllers\AdCaptureImageController;
use App\Http\Controllers\AdExamineeController;
use App\Http\Controllers\AdConfirmController;
use App\Http\Controllers\AdAcceptedController;

use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\EnstudgradeController;
use App\Http\Controllers\EnreportsController;

use App\Http\Controllers\SchedClassEnrollController;
use App\Http\Controllers\SchedReportsController;

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
    Route::get('/admission-status', [PortalController::class, 'admission_track_status'])->name('admission_track_status');
});

Route::get('/emp', [LoginController::class, 'login'])->name('login');
Route::post('/emp/user_login', [LoginController::class, 'emp_login'])->name('emp_login');

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
            Route::get('/list/search', [AdAdmissionController::class, 'applicant_list_search'])->name('applicant_list_search');
            Route::get('/list/search/applicants', [AdAdmissionController::class, 'srchappList'])->name('srchappList');
            Route::get('/list/search/edit/{id}', [AdAdmissionController::class, 'applicant_edit'])->name('applicant_edit');
            Route::put('/list/search/update/{id}', [AdAdmissionController::class, 'applicant_update'])->name('applicant_update');
            Route::get('/{id}/schedule', [AdAdmissionController::class, 'applicant_schedule'])->name('applicant_schedule');
            Route::get('/delete/{id}', [AdAdmissionController::class, 'applicant_delete'])->name('applicant_delete');
            Route::get('/{id}/confirm', [AdAdmissionController::class, 'applicant_confirm'])->name('applicant_confirm');
            Route::get('/slots', [AdAdmissionController::class, 'slots'])->name('slots'); 
            Route::get('/slots/search', [AdAdmissionController::class, 'slots_search'])->name('slots_search');

            Route::get('/{id}/print', [AdPrntController::class, 'applicant_print'])->name('applicant_print');
            Route::get('/{id}/permit', [AdPrntController::class, 'applicant_permit'])->name('applicant_permit');
            Route::get('/{id}/view', [AdPrntController::class, 'applicant_genPDF'])->name('applicant_genPDF');
            Route::get('/{id}/viewPermit', [AdPrntController::class, 'applicant_genPermit'])->name('applicant_genPermit');

            Route::post('/capture/{id}/save', [AdCaptureImageController::class, 'applicant_save_image'])->name('applicant_save_image');
            Route::post('/schedule/{id}/save', [AdAdmissionController::class, 'applicant_schedule_save'])->name('applicant_schedule_save');
        });

        Route::prefix('examinee')->group(function () {
            Route::get('/examineeList', [AdExamineeController::class, 'examinee_list'])->name('examinee-list');
            Route::get('/list/srchexamineeList', [AdExamineeController::class, 'srchexamineeList'])->name('srchexamineeList');
            Route::get('/{id}/edit', [AdExamineeController::class, 'examinee_edit'])->name('examinee_edit');
            Route::get('/delete/{id}', [AdExamineeController::class, 'examinee_delete'])->name('examinee_delete');
            Route::get('/{id}/assignresult', [AdExamineeController::class, 'assignresult'])->name('assignresult');
            Route::put('/result/{id}/save', [AdExamineeController::class, 'examinee_result_save'])->name('examinee_result_save');
            Route::put('/result/{id}/save', [AdExamineeController::class, 'examinee_result_save_nd'])->name('examinee_result_save_nd');
            Route::get('/{id}/confirm', [AdExamineeController::class, 'examinee_confirm'])->name('examinee_confirm');
            Route::get('/result/list', [AdExamineeController::class, 'result_list'])->name('result-list');
            Route::get('/list/srchexamineeResultList', [AdExamineeController::class, 'srchexamineeResultList'])->name('srchexamineeResultList');
            Route::get('/{id}/printPreEnrolment', [AdPrntController::class, 'pre_enrolment_print'])->name('pre_enrolment_print');
            Route::get('/{id}/view', [AdPrntController::class, 'genPreEnrolment'])->name('genPreEnrolment');
            Route::get('/{id}/print', [AdPrntController::class, 'applicant_print'])->name('applicant_print');
            Route::get('/{id}/confirmResult', [AdExamineeController::class, 'confirmResult'])->name('confirmResult');
            Route::get('/{id}/confirmPreEnrolment', [AdExamineeController::class, 'confirmPreEnrolment'])->name('confirmPreEnrolment');
        });

        Route::prefix('confirm')->group(function () {    
            Route::get('/list', [AdConfirmController::class, 'examinee_confirm'])->name('examinee-confirm');
            Route::get('/list/srchconfirmList', [AdConfirmController::class, 'srchconfirmList'])->name('srchconfirmList');
            Route::get('/list/srchconfirmList/accept', [AdConfirmController::class, 'accept'])->name('accept');
            Route::get('/{id}/deptInterview', [AdConfirmController::class, 'deptInterview'])->name('deptInterview');
            Route::put('/rating/{id}/save', [AdConfirmController::class, 'save_applicant_rating'])->name('save_applicant_rating');
            Route::get('/{id}/saveapplicant', [AdConfirmController::class, 'save_accepted_applicant'])->name('save_accepted_applicant');
            Route::get('/{id}/pushapp', [AdConfirmController::class, 'accepted_push_enroll_applicant'])->name('accepted_push_enroll_applicant');
            Route::get('/{id}/pushapplicant', [AdConfirmController::class, 'save_enroll_applicant'])->name('save_enroll_applicant');
            Route::get('/{id}/applicantAccept', [AdConfirmController::class, 'accept'])->name('accept');
            Route::get('/accepted', [AdAcceptedController::class, 'applicant_accepted'])->name('applicant-accepted');
            Route::get('/list/acceptedList', [AdAcceptedController::class, 'srchacceptedList'])->name('srchacceptedList');
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

    Route::prefix('emp/enrollment')->group(function () {
        
        Route::get('/', [EnrollmentController::class, 'index'])->name('enrollment-index');

        Route::prefix('search')->group(function () {
            Route::get('/student', [EnrollmentController::class, 'searchStud'])->name('searchStud');
            Route::get('/liveSearchStudent', [EnrollmentController::class, 'liveSearchStudent'])->name('liveSearchStudent');
            Route::get('/student/enroll', [EnrollmentController::class, 'searchStudEnroll'])->name('searchStudEnroll');
            Route::get('/student/enroll/rf', [EnrollmentController::class, 'studrf_print'])->name('studrf_print');
        });

        Route::prefix('gradesheet')->group(function () {
            Route::get('/search', [EnstudgradeController::class, 'studgrade_search'])->name('studgrade_search');
            Route::get('/search/list', [EnstudgradeController::class, 'studgrade_searchlist'])->name('studgrade_searchlist');
            // Route::post('/search/list/studentsGrade', [EnstudgradeController::class, 'geneStudent'])->name('geneStudent');
            Route::get('/search/list/studentsGrade/{id}', [EnstudgradeController::class, 'geneStudent1'])->name('geneStudent1');
        });

        Route::prefix('report')->group(function () {
            Route::get('/info/students', [EnreportsController::class, 'studInfo'])->name('studInfo');
            Route::get('/info/students/searchList', [EnreportsController::class, 'studInfo_search'])->name('studInfo_search');
            Route::get('/info/students/view/{id}', [EnreportsController::class, 'studInfo_view'])->name('studInfo_view');
        });

    });

    Route::prefix('emp/scheduler')->group(function () {
        
        Route::get('/', [SchedClassEnrollController::class, 'index'])->name('scheduler-index');

        Route::prefix('class')->group(function () {
            Route::get('/list', [SchedClassEnrollController::class, 'courseEnroll_list'])->name('courseEnroll_list');
            Route::get('/list/search', [SchedClassEnrollController::class, 'courseEnroll_list_search'])->name('courseEnroll_list_search');
            Route::post('/list/Add', [SchedClassEnrollController::class, 'classEnrolledAdd'])->name('classEnrolledAdd');

            Route::get('/list/subjectOff', [SchedClassEnrollController::class, 'subjectsOffered'])->name('subjectsOffered');
            Route::get('/list/subjectOff/search', [SchedClassEnrollController::class, 'subjectsOffered_search'])->name('subjectsOffered_search');
        });

        Route::prefix('faculty')->group(function () {
            Route::get('/list', [SchedClassEnrollController::class, 'faculty_list'])->name('faculty_list');
            Route::get('/flist/search', [SchedClassEnrollController::class, 'faculty_listsearch'])->name('faculty_listsearch');
        });

        Route::prefix('designation')->group(function () {
            Route::get('/list', [SchedClassEnrollController::class, 'faculty_design'])->name('faculty_design');
            Route::get('/fdlist/search', [SchedClassEnrollController::class, 'faculty_design_search'])->name('faculty_design_search');
            Route::post('/fdlist/Add', [SchedClassEnrollController::class, 'faculty_designdAdd'])->name('faculty_designdAdd');
            Route::get('/fdlist/edit/{id}', [SchedClassEnrollController::class, 'facdegEdit'])->name('facdegEdit');
            Route::get('/getProgramId/{code}', [SchedClassEnrollController::class, 'getProgramId'])->name('getProgramId');
        });

        Route::prefix('reports')->group(function () {
            Route::get('/list/subjects', [SchedReportsController::class, 'subjectsRead'])->name('subjectsRead');
            Route::get('/list/facultyload', [SchedReportsController::class, 'facultyloadRead'])->name('facultyloadRead');
            Route::get('/list/facultyload/search', [SchedReportsController::class, 'facultyload_search'])->name('facultyload_search');
        });

    });

    Route::prefix('emp/scholarship')->group(function () {
        
        Route::get('/', [ScholarshipController::class, 'index'])->name('scholarship-index');

        Route::prefix('studScholar')->group(function () {
            Route::get('/add', [ScholarshipController::class, 'scholarAdd'])->name('scholarAdd');
            Route::post('/addScholarship', [ScholarshipController::class, 'scholarCreate'])->name('scholarCreate');
            Route::get('/list/chedScholar', [ScholarshipController::class, 'chedscholarRead'])->name('chedscholarRead');
            Route::get('/list/chedScholar/search', [ScholarshipController::class, 'chedscholarSearch'])->name('chedscholarSearch');
        });

    });

    Route::prefix('emp/grades')->group(function () {
        
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

    Route::prefix('emp/settings')->group(function () {
        
        Route::get('/', [SettingController::class, 'index'])->name('settings-index');

        Route::prefix('usersAccount')->group(function () {
            Route::get('/list', [SettingController::class, 'usersRead'])->name('usersRead');
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


