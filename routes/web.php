<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\QueueingMonitorController;

use App\Http\Controllers\MainController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LoginFacultyController;
use App\Http\Controllers\GoogleFacAuthController;
use App\Http\Controllers\ControlController;
use App\Http\Controllers\ForAllEncryptIDController;
use App\Http\Controllers\AdAdmissionController;
use App\Http\Controllers\AdAdmissionAppController;
use App\Http\Controllers\AdPrntController;
use App\Http\Controllers\AdCaptureImageController;
use App\Http\Controllers\AdExamineeController;
use App\Http\Controllers\AdConfirmController;
use App\Http\Controllers\AdAcceptedController;
use App\Http\Controllers\AdChangeCampusController;
use App\Http\Controllers\AdCoursePreferenceController;
use App\Http\Controllers\AdBillingController;
use App\Http\Controllers\AdReuploadController;

use App\Http\Controllers\EnStudAddController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\EnProgStudEvalController;
use App\Http\Controllers\EnStudHistoryController;
use App\Http\Controllers\EnstudgradeController;
use App\Http\Controllers\EnSubjectsController;
use App\Http\Controllers\EnreportsController;
use App\Http\Controllers\EnStudentPerCurriculumController;
use App\Http\Controllers\EnStudentPerSubjectController;
use App\Http\Controllers\EnStudGrdeViewController;
use App\Http\Controllers\EnStudReportCardController;
use App\Http\Controllers\EnGradesheetLogbookController;
use App\Http\Controllers\EnStudELPLController;
use App\Http\Controllers\EnStudNoEnrolleeController;
use App\Http\Controllers\DeletedLogEnrollmentController;
use App\Http\Controllers\EnStudEnrolledController;
use App\Http\Controllers\EnStudEncodeGradesLogController;

use App\Http\Controllers\SchedClassCollegeController;
use App\Http\Controllers\SchedClassProgramsController;
use App\Http\Controllers\SchedClassRoomsController;
use App\Http\Controllers\SchedClassEnrollController;
use App\Http\Controllers\SchedFacultyListController;
use App\Http\Controllers\SchedFacultyDesignationController;
use App\Http\Controllers\SchedSubOfferController;
use App\Http\Controllers\SchedClassController;
use App\Http\Controllers\SchedFacultyController;
use App\Http\Controllers\SchedRoomController;
use App\Http\Controllers\SchedReportsController;

use App\Http\Controllers\StudFundAssessmentController;
use App\Http\Controllers\StudFeeAssessmentController;
use App\Http\Controllers\StudFeeTemplateController;
use App\Http\Controllers\StudStateAccntAssessmentController;
use App\Http\Controllers\StudHEBillingController;

use App\Http\Controllers\CashieringORController;

use App\Http\Controllers\ScholarshipController;

use App\Http\Controllers\GradingFacultyController;

use App\Http\Controllers\KioskAdminController;

use App\Http\Controllers\QueueingSettingController;

use App\Http\Controllers\SettingController;

use App\Http\Controllers\KioskDashController;

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


Route::group(['middleware'=>['guest', 'kiosk.session.expired', 'restrict.access']],function(){
    Route::get('/',[MainController::class,'main'])->name('main');
    Route::get('/linkstorage', function () {
        Artisan::call('storage:link');
    });

    Route::prefix('/portal')->group(function () {
        Route::get('/',[PortalController::class,'index'])->name('admission-portal');
        Route::get('/apply', [PortalController::class, 'admission_apply'])->name('admission-apply');
        Route::get('/getProgramsByCampus', [PortalController::class, 'getProgramsByCampus'])->name('getProgramsByCampus');
        Route::get('/getTestSchedByCampus', [PortalController::class, 'getExamSchedCampus'])->name('getExamSchedCampus');
        Route::post('/check-email', [PortalController::class, 'checkEmail'])->name('checkEmail');
        Route::post('/post_admission_apply', [PortalController::class, 'post_admission_apply'])->name('post_admission_apply');
        Route::get('/apply/submit/successfully', [PortalController::class, 'submitsucapply'])->name('submitsucapply');
        Route::post('/send-thank-you-email', [PortalController::class, 'sendThankYouEmail'])->name('sendThankYouEmail');
        //Route::get('/apply/submit/successfully', function () { return view('portal.applysubmit');});
        Route::get('/track',[PortalController::class,'admission_track'])->name('admission_track');
        Route::post('/admission-status', [PortalController::class, 'admission_track_status'])->name('admission_track_status');

        Route::get('/xYcmd/upload.documents',[AdReuploadController::class,'repup'])->name('repup');
        Route::post('/xYcmd/upload.documents/search', [AdReuploadController::class, 'searchApplicant'])->name('searchApplicant');
        Route::post('/xYcmd/upload.documents/search/uploaddocs', [AdReuploadController::class, 'uploadDocuments'])->name('uploadDocuments');
        Route::get('/xYcmd/upload.documents/redirect/expire',[AdReuploadController::class,'repupredirectexpire'])->name('repupredirectexpire');
    });

    Route::prefix('/queueing')->group(function () {
        Route::get('/transaction',[QueueingMonitorController::class,'queueRead'])->name('queue-monitor');
        Route::get('/queue-stream', [QueueingMonitorController::class, 'streamQueueData'])->name('queue.stream');

        // Route::post('/queue/call', [QueueingMonitorController::class, 'callCustomer'])->name('callCustomer'); 
        // Route::post('/queue/next', [QueueingMonitorController::class, 'serveNext'])->name('serveNext'); 
    });


    Route::get('/emp', [LoginController::class, 'login'])->name('login');
    Route::get('/stud/kiosk', [LoginController::class, 'loginkioskstud'])->name('loginkioskstud');
    Route::post('/emp/user_login', [LoginController::class, 'emp_login'])->name('emp_login');

    Route::get('/faculty', [LoginFacultyController::class, 'loginfac'])->name('loginfac');
    Route::post('/faculty/empfac_login', [LoginFacultyController::class, 'fac_login'])->name('fac_login');

    Route::get('/faculty/auth/google', [GoogleFacAuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/faculty/auth/google/callback', [GoogleFacAuthController::class, 'handleGoogleCallback']);
    Route::get('/faculty/verify/code/otp', [GoogleFacAuthController::class, 'verifyForm'])->name('verify');
    Route::post('/faculty/verify', [GoogleFacAuthController::class, 'verify'])->name('verify.code');
});


Route::group(['middleware'=>['stud_auth', 'CheckMaintenanceMode']],function(){
    Route::prefix('student')->group(function () {
        Route::get('/info/kiosk/dashboard/view', [KioskDashController::class, 'kioskhome'])->name('kioskhome');
    });
});

Route::group(['middleware'=>['fac_auth', 'CheckMaintenanceMode']],function(){
    Route::prefix('faculty')->group(function () {
        Route::get('/dashboard', [ControlController::class, 'homefaculty'])->name('homefaculty');
        Route::get('/logout/fac', [ControlController::class, 'logout'])->name('logoutfac');
    });

    Route::prefix('estudgrdmod/grades/faculty')->group(function () {
        
        Route::get('/', [GradingFacultyController::class, 'index'])->name('grading-index');

        Route::prefix('stud/attendance')->group(function () {
            Route::get('/list/current/sem', [GradingFacultyController::class, 'attendancefac'])->name('attendancefac');
            Route::get('/list/current/sem/search', [GradingFacultyController::class, 'attendance_searchfac'])->name('attendance_searchfac');
            Route::get('/list/current/sem/search/pdf', [GradingFacultyController::class, 'attendance_searchfacpdfpage'])->name('attendance_searchfacpdfpage');
            Route::get('/list/current/sem/search/view/pdf/{id}', [GradingFacultyController::class, 'studsubjectsReadPDFfacattendance'])->name('studsubjectsReadPDFfacattendance');
        });

        Route::prefix('studGrade')->group(function () {
            Route::get('/list/semester', [GradingFacultyController::class, 'semesterfac'])->name('semesterfac');
            Route::get('/list/virtualroom', [GradingFacultyController::class, 'virtualfaculty_class'])->name('virtualfaculty_class');
            Route::get('/list/virtualsubjectroom/{id}', [GradingFacultyController::class, 'virtual_facultysubjectclass'])->name('virtual_facultysubjectclass');
            Route::post('/list/view/studgrde/save', [GradingFacultyController::class, 'save_grades'])->name('save_grades');
            Route::post('/list/view/studgrdeComp/save', [GradingFacultyController::class, 'save_gradesComp'])->name('save_gradesComp');
            Route::post('/list/view/studgrde/submit/{subjID}', [GradingFacultyController::class, 'updateStatus_gradessubmit'])->name('updateStatus_gradessubmit');
            Route::get('/list/view/studgrde/gradesheetPDF/{subjID}', [GradingFacultyController::class, 'PDFgradesheetnew'])->name('PDFgradesheetnew');
        });

    });
});

Route::group(['middleware'=>['login_auth', 'CheckMaintenanceMode']],function(){
    Route::prefix('emp/control')->group(function () {
        Route::get('/', [ControlController::class, 'home'])->name('home');
        Route::get('/logout', [ControlController::class, 'logout'])->name('logout');
    });

    Route::prefix('emp/admission')->group(function () {
        
        Route::get('/', [AdAdmissionController::class, 'index'])->name('admission-index'); 
    
        Route::prefix('applicant')->group(function () {
            Route::post('/applist/encrypt', [ForAllEncryptIDController::class, 'idcrypt'])->name('idcrypt');

            Route::get('/add', [AdAdmissionController::class, 'applicant_add'])->name('applicant-add');
            Route::post('applicant-add', [AdAdmissionController::class, 'applicantCreate'])->name('applicantCreate');

            Route::get('/list', [AdAdmissionAppController::class, 'applicant_list'])->name('applicant-list');
            Route::get('/list/search/applicants', [AdAdmissionAppController::class, 'srchappList'])->name('srchappList');
            Route::get('/list/search/applicants/ajax', [AdAdmissionAppController::class, 'getsrchappList'])->name('getsrchappList');
            Route::post('/list/search/applicants/update', [AdAdmissionAppController::class, 'applicantUpdate'])->name('applicantUpdate');
            Route::post('/delete/{id}', [AdAdmissionAppController::class, 'applicant_delete'])->name('applicant_delete');
            Route::post('/schedule/save', [AdAdmissionAppController::class, 'applicant_schedulemod_save'])->name('applicant_schedulemod_save');
            Route::post('/confirm', [AdAdmissionAppController::class, 'applicant_confirmajax'])->name('applicant_confirmajax');

            Route::get('/list/search/edit/srch/{id}', [AdAdmissionController::class, 'applicant_edit_srch'])->name('applicant_edit_srch');
            Route::get('/list/search/edit/{id}', [AdAdmissionController::class, 'applicant_edit'])->name('applicant_edit');
            
            Route::put('/list/search/update/{id}', [AdAdmissionController::class, 'applicant_update'])->name('applicant_update');
            Route::get('/{id}/schedule', [AdAdmissionController::class, 'applicant_schedule'])->name('applicant_schedule');
            
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
            Route::get('/list/srchexamineeList/ajax', [AdExamineeController::class, 'getsrchexamineeList'])->name('getsrchexamineeList');
            Route::post('/result/save', [AdExamineeController::class, 'examinee_resultmod_save'])->name('examinee_resultmod_save');
            Route::post('/confirm', [AdExamineeController::class, 'examinee_confirmajax'])->name('examinee_confirmajax');

            Route::get('/result/list', [AdExamineeController::class, 'result_list'])->name('result-list');
            Route::get('/result/list/srchexamineeResultList', [AdExamineeController::class, 'resultlist_search'])->name('resultlist_search');
            Route::get('/result/list/srchexamineeResultList/ajax', [AdExamineeController::class, 'getsrchexamineeResultList'])->name('getsrchexamineeResultList');


            Route::get('/list/srchexamineeList/edit/srchexam/{id}', [AdExamineeController::class, 'examinee_edit_srch'])->name('examinee_edit_srch');
            Route::get('/list/srchexamineeList/edit/{id}', [AdExamineeController::class, 'examinee_edit'])->name('examinee_edit');
            Route::get('/delete/{id}', [AdExamineeController::class, 'examinee_delete'])->name('examinee_delete');
            Route::get('/{id}/assignresult', [AdExamineeController::class, 'assignresult'])->name('assignresult');
            Route::put('/result/{id}/save', [AdExamineeController::class, 'examinee_result_save'])->name('examinee_result_save');
            Route::put('/result/{id}/save', [AdExamineeController::class, 'examinee_result_save_nd'])->name('examinee_result_save_nd');
            
            Route::get('/{id}/confirm', [AdExamineeController::class, 'examinee_confirm'])->name('examinee_confirm');
            

            
            
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
            Route::get('/acceptedAll', [AdAcceptedController::class, 'applicant_acceptedAll'])->name('applicant-acceptedall');
            Route::get('/list/acceptedList', [AdAcceptedController::class, 'srchacceptedList'])->name('srchacceptedList');
            Route::get('/list/acceptedListAll', [AdAcceptedController::class, 'srchacceptedListAll'])->name('srchacceptedListAll');
            Route::get('/list/acceptedList/ajax', [AdAcceptedController::class, 'getsrchacceptedListapp'])->name('getsrchacceptedListapp');
            Route::get('/list/acceptedList/ajax/all', [AdAcceptedController::class, 'getsrchacceptedListappAll'])->name('getsrchacceptedListappAll');
            Route::get('/list/acceptedList/xx/srch/{id}', [AdPrntController::class, 'conpre_enrolment_print_srch'])->name('conpre_enrolment_print_srch');
            Route::get('/enrolled', [AdAcceptedController::class, 'applicant_enrolled'])->name('applicant-enrolled');
            Route::get('/list/enrolledList', [AdAcceptedController::class, 'srchacceptedEnrolledList'])->name('srchacceptedEnrolledList');
            Route::post('/pushapplicantenrollment', [AdAcceptedController::class, 'save_enroll_applicant'])->name('save_enroll_applicant');
        });

        Route::prefix('configure')->group(function () {    
            Route::get('/', [AdAdmissionController::class, 'configure_admission'])->name('configure_admission');
            Route::post('add', [AdAdmissionController::class, 'add_Program'])->name('add_Program');
            Route::get('/ajax', [AdAdmissionController::class, 'configure_admissionajax'])->name('configure_admissionajax');
            Route::post('/programEdit/update', [AdAdmissionController::class, 'programUpdate'])->name('programUpdate');
            Route::get('/programDelete/{id}/delete', [AdAdmissionController::class, 'programDelete'])->name('programDelete');

            Route::post('addstrand', [AdAdmissionController::class, 'add_Strand'])->name('add_Strand');
            Route::get('/ajax/strand', [AdAdmissionController::class, 'configure_admissionstrandajax'])->name('configure_admissionstrandajax');
            Route::post('/strandEdit/update', [AdAdmissionController::class, 'strandUpdate'])->name('strandUpdate');
            Route::get('/strandDelete/{id}/delete', [AdAdmissionController::class, 'strandDelete'])->name('strandDelete');

            Route::get('/ajax/date', [AdAdmissionController::class, 'configure_admissiondateajax'])->name('configure_admissiondateajax');
            Route::post('addAdmissionDate', [AdAdmissionController::class, 'add_admission_date'])->name('add_admission_date');
            Route::post('/dateEdit/update', [AdAdmissionController::class, 'dateUpdate'])->name('dateUpdate');
            Route::get('/dateDelete/{id}/delete', [AdAdmissionController::class, 'dateDelete'])->name('dateDelete');

            Route::get('/ajax/datetime', [AdAdmissionController::class, 'configure_admissiondatetimeajax'])->name('configure_admissiondatetimeajax');
            Route::get('/ajax/dateajaxdrop', [AdAdmissionController::class, 'fetchDates'])->name('fetchDates');
            Route::post('addAdmissionTime', [AdAdmissionController::class, 'add_admission_time'])->name('add_admission_time');
            Route::post('/timeEdit/update', [AdAdmissionController::class, 'timeUpdate'])->name('timeUpdate');
            Route::get('/timeDelete/{id}/delete', [AdAdmissionController::class, 'timeDelete'])->name('timeDelete');

            Route::post('addAdmissionVenue', [AdAdmissionController::class, 'add_admission_venue'])->name('add_admission_venue');
            Route::get('/ajax/venue', [AdAdmissionController::class, 'configure_admissionvenueajax'])->name('configure_admissionvenueajax');
            Route::post('/venueEdit/update', [AdAdmissionController::class, 'venueUpdate'])->name('venueUpdate');
            Route::get('/venueDelete/{id}/delete', [AdAdmissionController::class, 'venueDelete'])->name('venueDelete');

            Route::get('/ajax/yearadmission', [AdAdmissionController::class, 'configure_admissionyearajax'])->name('configure_admissionyearajax');
            Route::post('addAdmissionYear', [AdAdmissionController::class, 'add_admission_year'])->name('add_admission_year');
            Route::post('/yearEdit/update', [AdAdmissionController::class, 'yearUpdate'])->name('yearUpdate');
            Route::get('/yearDelete/{id}/delete', [AdAdmissionController::class, 'yearDelete'])->name('yearDelete');

            Route::get('/change/applicant/campus', [AdChangeCampusController::class, 'alllistappRead'])->name('alllistappRead');
            Route::get('/change/applicant/campus/search', [AdChangeCampusController::class, 'alllistappRead_search'])->name('alllistappRead_search');
            Route::get('/change/applicant/campus/search/ajax', [AdChangeCampusController::class, 'getalllistappRead_search'])->name('getalllistappRead_search');
            Route::post('/change/applicant/campus/search/update', [AdChangeCampusController::class, 'alllistappUpdate'])->name('alllistappUpdate');
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
            Route::get('/acceptedReports/PDF', [AdPrntController::class, 'acceptedPDF_reports'])->name('acceptedPDF_reports');

            Route::get('/course/pref', [AdCoursePreferenceController::class, 'indexcoursepref'])->name('indexcoursepref');
            Route::get('/course/pref/search', [AdCoursePreferenceController::class, 'indexcoursepref_search'])->name('indexcoursepref_search');
            Route::get('/course/pref/search/ajax', [AdCoursePreferenceController::class, 'getindexcourseprefAll'])->name('getindexcourseprefAll');

            Route::get('/billing', [AdBillingController::class, 'adbillingRead'])->name('adbillingRead');
            Route::get('/billing/search', [AdBillingController::class, 'adbillingRead_search'])->name('adbillingRead_search');
            Route::get('/billing/search/ajax', [AdBillingController::class, 'getadbillingRead_search'])->name('getadbillingRead_search');
        });
    });

    Route::prefix('enmod/enrollment')->group(function () {
        
        Route::get('/', [EnrollmentController::class, 'index'])->name('enrollment-index');

        Route::prefix('addnew')->group(function () {
            Route::get('/student', [EnStudAddController::class, 'studentCreate'])->name('studentCreate');
            Route::post('/student/add', [EnStudAddController::class, 'studentStore'])->name('studentStore');
        });

        Route::prefix('search')->group(function () {
            Route::get('/student', [EnrollmentController::class, 'searchStud'])->name('searchStud');
            Route::get('/student/enroll', [EnrollmentController::class, 'searchStudEnroll'])->name('searchStudEnroll');
            Route::get('/student/enroll/check-enrollment', [EnrollmentController::class, 'checkEnrollment'])->name('checkEnrollment');
            Route::get('/student/enroll/RF', [EnrollmentController::class, 'studrfprint'])->name('studrfprint');
            Route::get('/fetch-subjects', [EnrollmentController::class, 'fetchSubjects'])->name('fetchSubjects');
            Route::get('/editfetch-subjects', [EnrollmentController::class, 'editfetchSubjects'])->name('editfetchSubjects');
            Route::get('/get-sub-title', [EnrollmentController::class, 'coursefetchSubjects'])->name('coursefetchSubjects');
            Route::get('/get-sub-fee', [EnrollmentController::class, 'fetchFeeSubjects'])->name('fetchFeeSubjects');
            Route::post('/student/enroll/submit', [EnrollmentController::class, 'studEnrollmentCreate'])->name('studEnrollmentCreate');

            Route::delete('/student/enroll/submit', [EnrollmentController::class, 'deleteAllRecords'])->name('deleteAllRecords');

            Route::post('/queue/next', [EnrollmentController::class, 'getNextQueue'])->name('queue.next');
        });

        Route::prefix('eval')->group(function () {
            Route::get('/load/subject/stud', [EnProgStudEvalController::class, 'loadstudsub'])->name('loadstudsub');
            Route::get('/load/subject/stud/view/search', [EnProgStudEvalController::class, 'loadstudsub_searchview'])->name('loadstudsub_searchview');
            Route::post('/student/enroll/eval/submit', [EnProgStudEvalController::class, 'studEvalEnrollmentCreate'])->name('studEvalEnrollmentCreate');
            Route::get('/student/enroll/viewPrereg', [EnProgStudEvalController::class, 'studevalrfprint'])->name('studevalrfprint');
        });

        Route::prefix('edit')->group(function () {
            Route::get('/student/enroll', [EnrollmentController::class, 'editsearchStud'])->name('editsearchStud');
            Route::get('/student/enroll/view', [EnrollmentController::class, 'editsearchStudRead'])->name('editsearchStudRead');
            Route::post('/student/enroll/update', [EnrollmentController::class, 'studEnrollmentUpdate'])->name('studEnrollmentUpdate');
            Route::get('/student/enroll/viewRF', [EnrollmentController::class, 'studrfprint'])->name('studrfprint');
        });

        Route::prefix('history')->group(function () {
            Route::get('/listsearch/student', [EnStudHistoryController::class, 'studentEnHistory'])->name('studentEnHistory');
            Route::get('/listsearch/student/view', [EnStudHistoryController::class, 'viewsearchenStudHistory'])->name('viewsearchenStudHistory');
            Route::get('/listsearch/student/ajax', [EnStudHistoryController::class, 'searchenStudHistory'])->name('searchenStudHistory');
            Route::get('/listsearch/student/historyajax', [EnStudHistoryController::class, 'fetchStudEnrollmentHistory'])->name('fetchStudEnrollmentHistory');
        });

        Route::prefix('gradesheet')->group(function () {
            Route::get('/search', [EnstudgradeController::class, 'studgrade_search'])->name('studgrade_search');
            Route::get('/search/list', [EnstudgradeController::class, 'studgrade_searchlist'])->name('studgrade_searchlist');
            Route::get('/search/grad', [EnstudgradeController::class, 'studgrade_gradsearch'])->name('studgrade_gradsearch');
            Route::get('/search/list/grad', [EnstudgradeController::class, 'studgradegrad_searchlist'])->name('studgradegrad_searchlist');
            Route::get('/search/list/ajax', [EnstudgradeController::class, 'studgrade_searchlistajax'])->name('studgrade_searchlistajax');
            Route::get('/search/list/grad/ajax', [EnstudgradeController::class, 'studgradegrad_searchlistajax'])->name('studgradegrad_searchlistajax');
            Route::get('/search/list/studentsGrade/{id}', [EnstudgradeController::class, 'geneStudent1'])->name('geneStudent1');
            Route::post('/list/view/studgrde/save', [EnstudgradeController::class, 'registrarsave_grades'])->name('registrarsave_grades');
            Route::post('/list/view/studgrdeComp/save', [EnstudgradeController::class, 'registrarsave_gradesComp'])->name('registrarsave_gradesComp');
            Route::post('/list/view/studgrde/submit/{subjID}', [EnstudgradeController::class, 'registrarupdateStatus_gradessubmit'])->name('registrarupdateStatus_gradessubmit');
            Route::post('/list/view/studgrde/edit/{id}', [EnstudgradeController::class, 'editGrade'])->name('editGrade');
            Route::post('/list/view/studgrde/editcompletion/{id}', [EnstudgradeController::class, 'editCompletion'])->name('editCompletion');
            Route::post('/check-grade-password', [EnstudgradeController::class, 'checkPassword'])->name('checkPassword');
        });

        Route::prefix('subjects')->group(function () {
            Route::get('/list', [EnSubjectsController::class, 'subjectsRead'])->name('subjectsRead');
            Route::get('/ajaxsublist', [EnSubjectsController::class, 'getsubjectsRead'])->name('getsubjectsRead');
            Route::get('/ajaxsubcode', [EnSubjectsController::class, 'getNextSubjectNumber'])->name('getNextSubjectNumber');
            Route::post('/list/add', [EnSubjectsController::class, 'subjectsCreate'])->name('subjectsCreate');
        });

        Route::prefix('report')->group(function () {
            Route::get('/info/students', [EnreportsController::class, 'studInfo'])->name('studInfo');
            Route::get('/info/students/searchList', [EnreportsController::class, 'studInfo_search'])->name('studInfo_search');
            Route::get('/info/students/searchListajax', [EnreportsController::class, 'getstudInfo_search'])->name('getstudInfo_search');
            Route::get('/info/students/view/{id}', [EnreportsController::class, 'studInfo_view'])->name('studInfo_view');
            Route::post('/info/students/view/update', [EnreportsController::class, 'studInfoUpdate'])->name('studInfoUpdate');

            Route::get('/info/students/graduated', [EnreportsController::class, 'studInfograduated'])->name('studInfograduated');
            Route::get('/info/students/graduated/search/list', [EnreportsController::class, 'studInfograduated_search'])->name('studInfograduated_search');
            Route::get('/info/students/graduated/search/listajax', [EnreportsController::class, 'getstudInfograduated_search'])->name('getstudInfograduated_search');

            Route::get('/info/students/curriculum', [EnStudentPerCurriculumController::class, 'studCurr'])->name('studCurr');
            Route::get('/info/students/curriculum/search', [EnStudentPerCurriculumController::class, 'studCurrsearch'])->name('studCurrsearch');
            Route::get('/info/students/curriculum/searchajax', [EnStudentPerCurriculumController::class, 'getstudCurrSearch'])->name('getstudCurrSearch');
            Route::get('/info/students/curriculum/searchajax/grad', [EnStudentPerCurriculumController::class, 'getstudCurrSearchGradSchool'])->name('getstudCurrSearchGradSchool');
            Route::get('/info/students/curriculum/viewenroll/searchajax', [EnStudentPerCurriculumController::class, 'fetchStudEnrollmentlist'])->name('fetchStudEnrollmentlist');
            Route::get('/info/students/curriculum/viewenroll/pdfajax', [EnStudentPerCurriculumController::class, 'exportEnrollmentPDF'])->name('exportEnrollmentPDF');

            Route::get('/info/students/subjects', [EnStudentPerSubjectController::class, 'studsubjectsRead'])->name('studsubjectsRead');
            Route::get('/info/students/subjects/search', [EnStudentPerSubjectController::class, 'listsearch_studsubjectsRead'])->name('listsearch_studsubjectsRead');
            Route::get('/info/students/subjects/search/ajax', [EnStudentPerSubjectController::class, 'getlistsearch_studsubjectsRead'])->name('getlistsearch_studsubjectsRead');
            Route::get('/info/students/subjects/search/grad/ajax', [EnStudentPerSubjectController::class, 'gradschoolgetlistsearch_studsubjectsRead'])->name('gradschoolgetlistsearch_studsubjectsRead');
            Route::get('/info/students/subjects/search/view/{id}', [EnStudentPerSubjectController::class, 'listsearchview_studsubjectsRead'])->name('listsearchview_studsubjectsRead');
            Route::get('/info/students/subjects/search/view/pdf/{id}', [EnStudentPerSubjectController::class, 'studsubjectsReadPDF'])->name('studsubjectsReadPDF');

            Route::get('/info/stud/view/stud/grades', [EnStudGrdeViewController::class, 'studviewgradeRead'])->name('studviewgradeRead');
            Route::get('/info/stud/view/stud/grades/search', [EnStudGrdeViewController::class, 'search_studviewgradeRead'])->name('search_studviewgradeRead');
            Route::get('/info/stud/view/stud/grades/search/grad', [EnStudGrdeViewController::class, 'searchgradschool_studviewgradeRead'])->name('searchgradschool_studviewgradeRead');

            Route::get('/info/stud/reportcard', [EnStudReportCardController::class, 'reportCard_list'])->name('reportCard_list');
            Route::get('/info/stud/reportcard/search', [EnStudReportCardController::class, 'reportCard_listsearch'])->name('reportCard_listsearch');
            Route::get('/info/stud/reportcard/searchPDF', [EnStudReportCardController::class, 'reportCard_listsearchpdf'])->name('reportCard_listsearchpdf');

            Route::get('/info/enrollmentList', [EnStudELPLController::class, 'elpl_list'])->name('elpl_list');
            Route::get('/info/getcourse/ajax', [EnStudELPLController::class, 'getCourses'])->name('getCourses');
            Route::get('/info/enrollmentList/search', [EnStudELPLController::class, 'elpl_listsearch'])->name('elpl_listsearch');
            Route::get('/info/enrollmentList/searchajax', [EnStudELPLController::class, 'elplajax_listsearch'])->name('elplajax_listsearch');

            Route::get('/info/number/enrollees', [EnStudNoEnrolleeController::class, 'studnoenrollee'])->name('studnoenrollee');
            Route::post('/info/number/enrollees', [EnStudNoEnrolleeController::class, 'studnoenrollee_searchList'])->name('studnoenrollee_searchList');

            Route::get('/info/number/enrollees/nstp', [EnStudNoEnrolleeController::class, 'studnoNSTPenrollee'])->name('studnoNSTPenrollee');
            Route::post('/info/number/enrollees/nstp', [EnStudNoEnrolleeController::class, 'studnoenrolleenstp_searchList'])->name('studnoenrolleenstp_searchList');

            Route::get('/info/stud/record/eval', [EnStudReportCardController::class, 'studevalRead'])->name('studevalRead');
            Route::get('/info/stud/record/eval/search', [EnStudReportCardController::class, 'studevalRead_listsearch'])->name('studevalRead_listsearch');
            Route::get('/info/stud/record/eval/search/grad', [EnStudReportCardController::class, 'studevalReadgradschool_listsearch'])->name('studevalReadgradschool_listsearch');
            Route::get('/info/stud/record/eval/searchPDF', [EnStudReportCardController::class, 'studevalRead_listsearchpdf'])->name('studevalRead_listsearchpdf');

            Route::get('/logbook/facultygradesheet/list/submission', [EnGradesheetLogbookController::class, 'logbookindex'])->name('logbookindex');
            Route::get('/logbook/facultygradesheet/list/submission/view', [EnGradesheetLogbookController::class, 'logbook_search'])->name('logbook_search');
            Route::get('/logbook/facultygradesheet/list/submission/view/ajax', [EnGradesheetLogbookController::class, 'getlogbook_search'])->name('getlogbook_search');

            Route::get('/stud/enrolled/list/all/per/sem', [EnStudEnrolledController::class, 'studenrollRead'])->name('studenrollRead');
            Route::get('/stud/enrolled/list/all/per/sem/search', [EnStudEnrolledController::class, 'search_studenrollRead'])->name('search_studenrollRead');
            Route::get('/stud/enrolled/list/all/per/sem/search/ajax', [EnStudEnrolledController::class, 'getsearchstudenrollRead'])->name('getsearchstudenrollRead');

            Route::get('/updated/enrollment/logs', [DeletedLogEnrollmentController::class, 'updateEnrlmntlogsRead'])->name('updateEnrlmntlogsRead');
            Route::get('/updated/enrollment/logs/search', [DeletedLogEnrollmentController::class, 'search_uptadeEnrlmntlogsRead'])->name('search_uptadeEnrlmntlogsRead');

            Route::get('/encoded/enrollment/grades/logs', [EnStudEncodeGradesLogController::class, 'searchEncode_grade'])->name('searchEncode_grade');
            Route::get('/encoded/enrollment/grades/logs/search', [EnStudEncodeGradesLogController::class, 'searchEncode_gradeRead'])->name('searchEncode_gradeRead');
            Route::get('/encoded/enrollment/grades/logs/search/ajaxlogs', [EnStudEncodeGradesLogController::class, 'getsearchEncode_gradeRead'])->name('getsearchEncode_gradeRead');

            Route::get('/deleted/enrollment/logs', [DeletedLogEnrollmentController::class, 'delenrlmntlogsRead'])->name('delenrlmntlogsRead');
            Route::get('/deleted/enrollment/logs/search', [DeletedLogEnrollmentController::class, 'search_delenrlmntlogsRead'])->name('search_delenrlmntlogsRead');
            Route::get('/deleted/enrollment/logs/search/ajax', [DeletedLogEnrollmentController::class, 'getdelenrlmntlogsRead'])->name('getdelenrlmntlogsRead');

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
            Route::post('/list/roomlist/add', [SchedClassRoomsController::class, 'roomsCreate'])->name('roomsCreate');
            Route::post('/roomlist/view/update', [SchedClassRoomsController::class, 'roomsUpdate'])->name('roomsUpdate');
            Route::get('/roomlist/view/delete{id}', [SchedClassRoomsController::class, 'roomsDelete'])->name('roomsDelete');
        });

        Route::prefix('class')->group(function () {
            Route::get('/list', [SchedClassEnrollController::class, 'courseEnroll_list'])->name('courseEnroll_list');
            Route::get('/list/search', [SchedClassEnrollController::class, 'courseEnroll_list_search'])->name('courseEnroll_list_search');
            Route::get('/list/search/ajaxviewclass', [SchedClassEnrollController::class, 'getclassEnRead'])->name('getclassEnRead');
            Route::get('/list/search/grad/ajaxviewclass', [SchedClassEnrollController::class, 'getGradclassEnRead'])->name('getGradclassEnRead');
            Route::post('/list/Add', [SchedClassEnrollController::class, 'classEnrollCreate'])->name('classEnrollCreate');
            Route::post('/list/update', [SchedClassEnrollController::class, 'classEnrolledUpdate'])->name('classEnrolledUpdate');
            Route::get('/list/delete{id}', [SchedClassEnrollController::class, 'classEnrolledDelete'])->name('classEnrolledDelete');
        });

        Route::prefix('subjectOff')->group(function () {
            Route::get('/list/', [SchedSubOfferController::class, 'subjectsOffered'])->name('subjectsOffered');
            Route::get('/list/search', [SchedSubOfferController::class, 'subjectsOffered_search'])->name('subjectsOffered_search');
            Route::get('/list/search/ajaxsuboff', [SchedSubOfferController::class, 'getsubjectsOfferedRead'])->name('getsubjectsOfferedRead');
            Route::get('/list/search/grad/ajaxsuboff', [SchedSubOfferController::class, 'getGradsubjectsOfferedRead'])->name('getGradsubjectsOfferedRead');
            Route::get('/get-subname-subcode', [SchedSubOfferController::class, 'fetchSubjectName'])->name('fetchSubjectName');
            Route::post('/list/search/add', [SchedSubOfferController::class, 'subjectsOfferedCreate'])->name('subjectsOfferedCreate');
            Route::post('/list/search/suboff/update', [SchedSubOfferController::class, 'subjectsOfferedUpdate'])->name('subjectsOfferedUpdate');
            Route::get('/list/search/suboff/delete{id}', [SchedSubOfferController::class, 'subjectsOfferedDelete'])->name('subjectsOfferedDelete');
        });

        Route::prefix('faculty')->group(function () {
            Route::get('/list', [SchedFacultyListController::class, 'faculty_list'])->name('faculty_list');
            Route::get('/flist/search/ajax', [SchedFacultyListController::class, 'getfacultylistRead'])->name('getfacultylistRead');
            Route::post('/flist/search/add', [SchedFacultyListController::class, 'facultyCreate'])->name('facultyCreate');
            Route::post('/flist/search/update', [SchedFacultyListController::class, 'facultyUpdate'])->name('facultyUpdate');
            Route::get('/flist/search/delete{id}', [SchedFacultyListController::class, 'facultyDelete'])->name('facultyDelete');
        });

        Route::prefix('designation')->group(function () {
            Route::get('/list', [SchedFacultyDesignationController::class, 'faculty_design'])->name('faculty_design');
            Route::get('/fdlist/search', [SchedFacultyDesignationController::class, 'faculty_design_search'])->name('faculty_design_search');
            Route::get('/fdlist/search/ajax', [SchedFacultyDesignationController::class, 'getfacultyDesigRead'])->name('getfacultyDesigRead');
            Route::post('/fdlist/Add', [SchedFacultyDesignationController::class, 'facdesignationCreate'])->name('facdesignationCreate');
            Route::post('/fdlist/update', [SchedFacultyDesignationController::class, 'facdesignationUpdate'])->name('facdesignationUpdate');
            Route::post('/fdlist/search/delete{id}', [SchedFacultyDesignationController::class, 'designationDelete'])->name('designationDelete');
            Route::get('/getProgramId/{progAcronym}', [SchedFacultyDesignationController::class, 'getProgramId'])->name('getProgramId');
        });

        Route::prefix('schedule')->group(function () {
            Route::get('/class', [SchedClassController::class, 'classSchedRead'])->name('classSchedRead');
            Route::get('/info/getcourseyrsec/ajax', [SchedClassController::class, 'getCoursesyearsec'])->name('getCoursesyearsec');
            Route::get('/class/set', [SchedClassController::class, 'classSchedSetRead'])->name('classSchedSetRead');
            Route::get('/class/set/suboff/class/ajax', [SchedClassController::class, 'getSubjectsClassSched'])->name('getSubjectsClassSched');
            Route::get('/class/set/faculty/class/ajax', [SchedClassController::class, 'getFacultyClassSched'])->name('getFacultyClassSched');
            Route::get('/class/set/room/class/ajax', [SchedClassController::class, 'getRoomClassSched'])->name('getRoomClassSched');
            Route::post('/class/set/class/add', [SchedClassController::class, 'classSchedCreate'])->name('classSchedCreate');
            Route::get('/class/set/class/fetch', [SchedClassController::class, 'fetchSchedule'])->name('fetchSchedule');
            Route::post('/print-schedule', [SchedClassController::class, 'printSchedule'])->name('printSchedule');
            Route::get('/class/plotted/class/ajaxfetch', [SchedClassController::class, 'getschedclassplotted'])->name('getschedclassplotted');
            Route::get('/class/plotted/list/delete{id}', [SchedClassController::class, 'schedclassplottedDelete'])->name('schedclassplottedDelete');


            Route::get('/faculty', [SchedFacultyController::class, 'facultySchedRead'])->name('facultySchedRead');
            Route::get('/faculty/set', [SchedFacultyController::class, 'facultySchedSetRead'])->name('facultySchedSetRead');
            Route::get('/faculty/info/getcourseyrsec/ajax', [SchedFacultyController::class, 'getCoursesyearsecFac'])->name('getCoursesyearsecFac');
            Route::get('/faculty/set/suboff/class/ajax', [SchedFacultyController::class, 'getSubjectsClassSchedFac'])->name('getSubjectsClassSchedFac');
            Route::post('/faculty/set/class/add', [SchedFacultyController::class, 'facultySchedCreate'])->name('facultySchedCreate');
            Route::get('/faculty/set/class/fetch', [SchedFacultyController::class, 'fetchFacultySchedule'])->name('fetchFacultySchedule');
            Route::get('/faculty/set/view/load', [SchedFacultyController::class, 'facultyloadPDFTemplate'])->name('facultyloadPDFTemplate');
            Route::post('/print/faculty/schedule', [SchedFacultyController::class, 'printFacultySchedule'])->name('printFacultySchedule');

            Route::get('/room', [SchedRoomController::class, 'roomSchedRead'])->name('roomSchedRead');
            Route::get('/room/set', [SchedRoomController::class, 'roomSchedSetRead'])->name('roomSchedSetRead');
            Route::get('/room/set/class/fetch', [SchedRoomController::class, 'fetchRoomSchedule'])->name('fetchRoomSchedule');
            Route::post('/print/room/schedule', [SchedRoomController::class, 'printRoomSchedule'])->name('printRoomSchedule');
        });

        Route::prefix('reports')->group(function () {
            Route::get('/list/facultyload', [SchedReportsController::class, 'facultyloadRead'])->name('facultyloadRead');
            Route::get('/list/facultyload/search', [SchedReportsController::class, 'facultyload_search'])->name('facultyload_search');

            Route::get('/list/subjectoffer', [SchedReportsController::class, 'reportsuboffer'])->name('reportsuboffer');
            Route::get('/list/subjectoffer/search', [SchedReportsController::class, 'reportsuboffer_search'])->name('reportsuboffer_search');
            Route::get('/list/subjectoffer/search/ajax', [SchedReportsController::class, 'getreportsuboffer_search'])->name('getreportsuboffer_search');
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

            Route::get('/fetch-student-fees', [StudFeeAssessmentController::class, 'fetchStudentFees'])->name('fetch-student-fees');
            Route::get('/fetch-student-fees-grad', [StudFeeAssessmentController::class, 'fetchStudentFeesgrad'])->name('fetch-student-fees-grad');

        });

        Route::prefix('studfees/template')->group(function () {
            Route::get('/search', [StudFeeTemplateController::class, 'searchStudfeeTemplate'])->name('searchStudfeeTemplate');
            Route::get('/search/list/temp', [StudFeeTemplateController::class, 'list_searchStudfeetemplate'])->name('list_searchStudfeetemplate');
            Route::get('/search/list/temp/ajax', [StudFeeTemplateController::class, 'getstudFeetemplateRead'])->name('getstudFeetemplateRead');
            Route::post('/search/list/temp/ajax/add', [StudFeeTemplateController::class, 'studFeeTemplateCreate'])->name('studFeeTemplateCreate');
        });

        Route::prefix('reports')->group(function () {
            Route::get('/statement/per/sem', [StudStateAccntAssessmentController::class, 'stateaccntpersem'])->name('stateaccntpersem');
            Route::get('/statement/per/search', [StudStateAccntAssessmentController::class, 'stateaccntpersem_search'])->name('stateaccntpersem_search');
            Route::get('/statement/per/search/pdf', [StudStateAccntAssessmentController::class, 'stateaccntpersem_searchpdf'])->name('stateaccntpersem_searchpdf');

            Route::get('/statement/per/student', [StudStateAccntAssessmentController::class, 'stateaccntperstudent'])->name('stateaccntperstudent');
            Route::get('/statement/per/student/search', [StudStateAccntAssessmentController::class, 'stateaccntperstudentid_search'])->name('stateaccntperstudentid_search');
            Route::get('/statement/per/student/search/name', [StudStateAccntAssessmentController::class, 'stateaccntperstudentname_search'])->name('stateaccntperstudentname_search');
            Route::get('/statement/per/student/search/pdf', [StudStateAccntAssessmentController::class, 'stateaccntperstudent_searchpdf'])->name('stateaccntperstudent_searchpdf');
            Route::get('/statement/per/student/searchname/pdf', [StudStateAccntAssessmentController::class, 'stateaccntperstudentname_searchpdf'])->name('stateaccntperstudentname_searchpdf');

            Route::get('/statement/summary', [StudStateAccntAssessmentController::class, 'stateaccntpersum'])->name('stateaccntpersum');
            Route::get('/statement/summary/search', [StudStateAccntAssessmentController::class, 'stateaccntpersum_search'])->name('stateaccntpersum_search');
            Route::get('/statement/summary/searchajax', [StudStateAccntAssessmentController::class, 'getstateaccntpersum_search'])->name('getstateaccntpersum_search');

            Route::get('/he/billing', [StudHEBillingController::class, 'hebillingRead'])->name('hebillingRead');
            Route::get('/he/billing/search', [StudHEBillingController::class, 'hebillingRead_search'])->name('hebillingRead_search');
        });
    });

    Route::prefix('cash/collection')->group(function () {
        
        Route::get('/', [CashieringORController::class, 'index'])->name('cashiering-index');

        Route::prefix('official')->group(function () {
            Route::get('/receipt', [CashieringORController::class, 'list_orRead'])->name('list_orRead');
            Route::get('/receipt/search', [CashieringORController::class, 'listsearch_orRead'])->name('listsearch_orRead');
            Route::get('/receipt/search/list/ajaxorfee', [CashieringORController::class, 'getorpaymentRead'])->name('getorpaymentRead');
            Route::post('/receipt/search/list/add', [CashieringORController::class, 'orCreate'])->name('orCreate');
            Route::post('/receipt/search/list/update', [CashieringORController::class, 'orUpdate'])->name('orUpdate');
            Route::get('/receipt/search/list/delete{id}', [CashieringORController::class, 'orDelete'])->name('orDelete');
            Route::get('/receipt/search/viewor/print', [CashieringORController::class, 'orprint'])->name('orprint');
            Route::get('/receipt/search/viewor/print/edit', [CashieringORController::class, 'orprintedit'])->name('orprintedit');
            Route::post('/receipt/search/list/comments/add', [CashieringORController::class, 'orCommentsCreate'])->name('orCommentsCreate');
            Route::post('/receipt/search/list/comments/update', [CashieringORController::class, 'orCommentsUpdate'])->name('orCommentsUpdate');

            Route::get('/receipt/edit', [CashieringORController::class, 'listedit_orRead'])->name('listedit_orRead');
            Route::get('/receipt/search/edit', [CashieringORController::class, 'listsearchedit_orRead'])->name('listsearchedit_orRead');
            Route::get('/delete-payment', [CashieringORController::class, 'deletePayment'])->name('deletePayment');


            Route::get('/receipt/per/date', [CashieringORController::class, 'listorperdayRead'])->name('listorperdayRead');
            Route::get('/receipt/per/date/search', [CashieringORController::class, 'listsearch_orperdayRead'])->name('listsearch_orperdayRead');

            Route::get('/receipt/per/month', [CashieringORController::class, 'listorpermonthRead'])->name('listorpermonthRead');
            Route::get('/receipt/per/month/search', [CashieringORController::class, 'listsearch_orpermonthRead'])->name('listsearch_orpermonthRead');
            Route::get('/receipt/per/month/search/ajax', [CashieringORController::class, 'getlistsearch_orpermonthRead'])->name('getlistsearch_orpermonthRead');

            Route::get('/receipt/all/or/list/ajaxorstudfee', [CashieringORController::class, 'getlistallorRead'])->name('getlistallorRead');
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
            Route::get('/list/search/student/historyajax', [ScholarshipController::class, 'fetchEnrollmentHistory'])->name('fetchEnrollmentHistory');

            Route::get('/list/search/student/scholar/report', [ScholarshipController::class, 'studenscholarreportRead'])->name('studenscholarreportRead');
            Route::get('/list/search/student/scholar/report/search', [ScholarshipController::class, 'studenscholarreport_searchRead'])->name('studenscholarreport_searchRead');
            Route::get('/list/search/student/scholar/report/search/ajax', [ScholarshipController::class, 'getStudScholarReportRead'])->name('getStudScholarReportRead');

            Route::get('/list/search/student/numberenroll', [ScholarshipController::class, 'countstudnoenrollee'])->name('countstudnoenrollee');

            Route::get('/list/search/student/registrationform', [ScholarshipController::class, 'studregformRead'])->name('studregformRead');
            Route::get('/list/search/student/registrationform/search', [ScholarshipController::class, 'listsearch_studregformRead'])->name('listsearch_studregformRead');
            Route::get('/list/search/student/registrationform/search/pdf', [ScholarshipController::class, 'listsearchpdf_studregformRead'])->name('listsearchpdf_studregformRead');
        });
    });

    // Route::prefix('estudgrdmod/grades/faculty')->group(function () {
        
    //     Route::get('/', [GradingFacultyController::class, 'index'])->name('grading-index');

    //     Route::prefix('stud/attendance')->group(function () {
    //         Route::get('/list/current/sem', [GradingFacultyController::class, 'attendancefac'])->name('attendancefac');
    //         Route::get('/list/current/sem/search', [GradingFacultyController::class, 'attendance_searchfac'])->name('attendance_searchfac');
    //         Route::get('/list/current/sem/search/pdf', [GradingFacultyController::class, 'attendance_searchfacpdfpage'])->name('attendance_searchfacpdfpage');
    //         Route::get('/list/current/sem/search/view/pdf/{id}', [GradingFacultyController::class, 'studsubjectsReadPDFfacattendance'])->name('studsubjectsReadPDFfacattendance');
    //     });

    //     Route::prefix('studGrade')->group(function () {
    //         Route::get('/list/semester', [GradingFacultyController::class, 'semesterfac'])->name('semesterfac');
    //         Route::get('/list/virtualroom', [GradingFacultyController::class, 'virtualfaculty_class'])->name('virtualfaculty_class');
    //         Route::get('/list/virtualsubjectroom/{id}', [GradingFacultyController::class, 'virtual_facultysubjectclass'])->name('virtual_facultysubjectclass');
    //         Route::post('/list/view/studgrde/save', [GradingFacultyController::class, 'save_grades'])->name('save_grades');
    //         Route::post('/list/view/studgrdeComp/save', [GradingFacultyController::class, 'save_gradesComp'])->name('save_gradesComp');
    //         Route::post('/list/view/studgrde/submit/{subjID}', [GradingFacultyController::class, 'updateStatus_gradessubmit'])->name('updateStatus_gradessubmit');
    //         Route::get('/list/view/studgrde/gradesheetPDF/{subjID}', [GradingFacultyController::class, 'PDFgradesheetnew'])->name('PDFgradesheetnew');
    //     });

    //     // Route::prefix('studGrade')->group(function () {
    //     //     Route::get('/list', [GradingFacultyController::class, 'grades'])->name('grades');
    //     //     Route::get('/list/view/studgrde/{subjID}', [GradingFacultyController::class, 'gradesstud'])->name('gradesstud');
    //     //     Route::get('/list/viewsearch', [GradingFacultyController::class, 'gradesstud_search'])->name('gradesstud_search');
    //     //     Route::post('/list/view/studgrde/save', [GradingFacultyController::class, 'save_grades'])->name('save_grades');
    //     //     Route::post('/list/view/studgrdeComp/save', [GradingFacultyController::class, 'save_gradesComp'])->name('save_gradesComp');
    //     //     Route::post('/list/view/studgrde/submit/{subjID}', [GradingFacultyController::class, 'updateStatus_gradessubmit'])->name('updateStatus_gradessubmit');
    //     //     Route::get('/list/view/studgrde/gradesheetPDF/{subjID}', [GradingFacultyController::class, 'PDFgradesheetnew'])->name('PDFgradesheetnew');
    //     // });

    // });

    Route::prefix('kioskstud')->group(function () {
        Route::get('/admin/kiosk/user/view', [KioskAdminController::class, 'adminkioskRead'])->name('adminkioskRead');
        Route::get('/student/{id}', [KioskAdminController::class, 'getStudentById'])->name('getStudentById');
        Route::get('/admin/kiosk/user/view/ajax', [KioskAdminController::class, 'getadminkioskRead'])->name('getadminkioskRead');
        Route::post('/admin/kiosk/user/view/add', [KioskAdminController::class, 'adminkioskCreate'])->name('adminkioskCreate');
        Route::post('/admin/kiosk/user/view/update', [KioskAdminController::class, 'adminkioskUpdate'])->name('adminkioskUpdate');
        Route::get('/admin/kiosk/user/view/delete{id}', [KioskAdminController::class, 'adminkioskDelete'])->name('adminkioskDelete');
    });

    Route::prefix('conf/queue/settings')->group(function () {
        
        Route::get('/', [QueueingSettingController::class, 'index'])->name('queue-index');
        Route::get('/getcounterajax', [QueueingSettingController::class, 'getcounterRead'])->name('getcounterRead');
        Route::post('/counter/add', [QueueingSettingController::class, 'counterCreate'])->name('counterCreate');

        Route::get('/numbers', [QueueingSettingController::class, 'numberRead'])->name('numberRead');
        Route::get('/getnumberajax', [QueueingSettingController::class, 'getnumberRead'])->name('getnumberRead');
        Route::post('/number/add', [QueueingSettingController::class, 'storeQueueNumbers'])->name('storeQueueNumbers');

        Route::get('/queue/set/on/off', [QueueingSettingController::class, 'queueonoff'])->name('queueonoff');
        Route::post('/toggle-queue', [QueueingSettingController::class, 'toggleQueue'])->name('toggle.queue');
    });

    Route::prefix('adempset/settings')->group(function () {
        
        Route::get('/', [SettingController::class, 'index'])->name('settings-index');

        Route::prefix('usersAccount')->group(function () {
            Route::get('/list/all/users', [SettingController::class, 'usersRead'])->name('usersRead');
            Route::get('/list/all/users/getajax', [SettingController::class, 'getusersRead'])->name('getusersRead');
            Route::post('/users/list/add',[SettingController::class,'userCreate'])->name('userCreate');
            Route::get('/list/info', [SettingController::class, 'accountRead'])->name('accountRead');
            Route::get('/list/all/users/edit{id}', [SettingController::class, 'edit_user'])->name('edit_user');
            Route::post('/filter_buttons', [SettingController::class, 'filterButtons'])->name('filterButtons');
            Route::post('/filter_buttons/update', [SettingController::class, 'userbuttonUpdate'])->name('userbuttonUpdate');
            Route::post('/list/update', [SettingController::class, 'updateUser'])->name('updateUser');
            Route::post('/list/users/updatePass', [SettingController::class, 'userUpdatePassword'])->name('userUpdatePassword');

            Route::get('/list/all/faculty/accounts', [SettingController::class, 'facultiesRead'])->name('facultiesRead');

            Route::get('/conf', [SettingController::class, 'setconfigure'])->name('setconfigure');
            Route::get('/conf/ajax', [SettingController::class, 'getsetconfigure'])->name('getsetconfigure');
            Route::post('/conf/add',[SettingController::class,'setconfCreate'])->name('setconfCreate');
            Route::post('/conf/update',[SettingController::class,'setconfUpdate'])->name('setconfUpdate');

            Route::get('/confGradeAuthSet', [SettingController::class, 'setgradepassconfigure'])->name('setgradepassconfigure');
            Route::post('/confGradeAuthSet/add',[SettingController::class,'setgradepassconfCreate'])->name('setgradepassconfCreate');
            Route::post('/confGradeAuthSet/update/{id}', [SettingController::class, 'updateGradepass'])->name('updateGradepass');

            Route::get('/setting/server/zeus', [SettingController::class, 'serverMaintenance'])->name('serverMaintenance');
            Route::post('/setting/server/zeus/admin/maintenance', [SettingController::class, 'toggleMaintenance'])->name('toggleMaintenance');
        });

    });

    

});




