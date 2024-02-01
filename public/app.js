$(document).ready(function()
{
	$(".info_applicant").click(function (event) {
	    var newURL = '../' + event.target.id + '/edit';
	    $("#btn_edit_examinee").prop('href', newURL);
	})

	$(".info_applicant").click(function (event) {
	    var newURL = '../' + event.target.id + '/assignresult';
	    $("#btn_test_result").prop('href', newURL);
	})

	$(".info_applicant").click(function (event) {
	    var newURL = '../' + event.target.id + '/printPreEnrolment';
	    $("#btn_view_preenrolment").prop('href', newURL);
	})

	$(".info_applicant").click(function (event) {
	    var newURL = '../' + event.target.id + '/confirmResult';
	    $("#btn_confirm_pre_enrolment").prop('href', newURL);
	})

	$(".info_applicant").click(function (event) {
	    var newURL = '../' + event.target.id + '/deptInterview';
	    $("#btn_dept_interview").prop('href', newURL);
	})

	$(".info_applicant").click(function (event) {
	    var newURL = '../' + event.target.id + '/applicantAccept';
	    $("#btn_accepted_applicants").prop('href', newURL);
	})

});