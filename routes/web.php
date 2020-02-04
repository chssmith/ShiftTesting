<?php

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



Route::get('login', function() {
	$returnURL = Session::get('returnURL', Request::url() . '/../');

	return RCAuth::redirectToLogin($returnURL);
});

Route::get('logout', function() {
	RCAuth::logout();
	$returnURL = Request::url() . '/../';

	return RCAuth::redirectToLogout($returnURL);
});


Route::middleware('force_login')->group( function (){
	Route::get("guardian/{id}/confirm", "StudentInformationController@getGuardianVerification");
	Route::get("ap", "StudentInformationController@getAPExams");
	Route::get("ib", "StudentInformationController@getIBCourses");

	Route::get("academic_integrity", "StudentInformationController@showAcademicIntegrityStatement");
	Route::post("academic_integrity", "StudentInformationController@completeAcademicIntegrityStatement");

	Route::get("financial", "StudentInformationController@showFinancialAcceptance");
	Route::post("financial", "StudentInformationController@completeFinancialAcceptance");
});


Route::middleware('force_login')->get('/', function(){
	$user = RCAuth::user();
	$universal = (RCAuth::check("Universal") || RCAuth::attempt("Universal"));
	if($universal){
		return redirect()->action('AdminController@index');
	}else{
		return redirect()->action('StudentInformationController@index');
	}
});

Route::prefix("admin")->middleware("force_login")->group( function () {
	Route::get('/', 'AdminController@index');
	Route::get('changes', 'AdminController@changedStudents');
	Route::get('parents', 'AdminController@changedParentInfo');
	Route::get('create_student_snap', 'AdminController@createStudentSnap');
	Route::get('create_parent_snap', 'AdminController@createParentSnap');

	Route::get("academic_achievement/export", "AdminController@exportAcademicAchievementCSV");
});

Route::prefix("sims")->middleware("force_login")->group( function () {
	Route::get ("registration", "SIMSRegistrationController@sessionSelectionPage");
	Route::post("registration/submit", "SIMSRegistrationController@sessionSelection");
	Route::get ("registration/studentinfo", "SIMSRegistrationController@studentInfoPage");
	Route::post("registration/studentinfo/store", "SIMSRegistrationController@studentInfo");
	Route::get ("registration/parentsguests", "SIMSRegistrationController@parentsGuestsPage");
	Route::post("registration/parentsguests/store", "SIMSRegistrationController@parentsGuests");
	Route::get ("registration/modeoftravel", "SIMSRegistrationController@modeOfTravelPage");
	Route::post("registration/modeoftravel/store", "SIMSRegistrationController@modeOfTravel");
	Route::get ("registration/confirmation", "SIMSRegistrationController@confirmationPage");
	Route::post("registration/confirmation/store", "SIMSRegistrationController@confirmation");
});

Route::prefix("academic_achievement")->group( function () {
	Route::get("/",  "StudentInformationController@showAcademicAchievement");
	Route::post("/", "StudentInformationController@storeAcademicAchievement");
});

Route::get('/', 'StudentInformationController@index');
Route::get('personal_info', 'StudentInformationController@personalInfo');
Route::get('address_info', 'StudentInformationController@addressInfo');
Route::get('residence_info', 'StudentInformationController@residenceInfo');
Route::get('medical_info', 'StudentInformationController@medicalInfo');
Route::get('allergy_info', 'StudentInformationController@allergyInfo');
Route::get('non_emergency', 'StudentInformationController@nonEmergency');
Route::get('independent_student', 'StudentInformationController@independentStudent');
Route::get('info_release/{id?}', 'StudentInformationController@infoRelease');
Route::get('citizen_info', 'StudentInformationController@citizenInfo');
Route::get('parent_info', 'StudentInformationController@parentAndGuardianInfo');
Route::get('individual_guardian/{id?}', 'StudentInformationController@individualGuardian');
Route::get('emergency_contact', 'StudentInformationController@emergencyContact');
Route::get('emergency_contact/edit/{id?}', 'StudentInformationController@individualEmergencyContact');
Route::get('emergency_contact/double_check', 'StudentInformationController@emergencyDoubleCheck');
Route::get('missing_person', 'StudentInformationController@missingPersonContact');
Route::get('employement_info/{id?}', 'StudentInformationController@employmentInfo');
Route::delete('delete_contact/{id}', 'StudentInformationController@deleteContact');
Route::delete('guardian/{id}', 'StudentInformationController@deleteGuardian');
Route::get('confirmation', 'StudentInformationController@confirmation');

Route::post('personal_info/update', 'StudentInformationController@personalInfoUpdate');
Route::post('address_info/update', 'StudentInformationController@addressInfoUpdate');
Route::post('residence_info/update', 'StudentInformationController@residenceInfoUpdate');
Route::post('medical_info/update', 'StudentInformationController@medicalInfoUpdate');
Route::post('allergy_info/update', 'StudentInformationController@allergyInfoUpdate');
Route::post('non_emergency/update', 'StudentInformationController@nonEmergencyUpdate');
Route::post('independent_student/update', 'StudentInformationController@independentStudentUpdate');
Route::post('info_release/update/{id}', 'StudentInformationController@infoReleaseUpdate');
Route::post('citizen_info/update', 'StudentInformationController@citizenInfoUpdate');
Route::post('guardian/update/{id?}', 'StudentInformationController@parentAndGuardianInfoUpdate');
Route::post('emergency_contact/update/{id?}', 'StudentInformationController@emergencyContactUpdate');
Route::post('missing_person/update', 'StudentInformationController@missingPersonContactUpdate');
Route::post('employment/update/{id?}', 'StudentInformationController@employmentInfoUpdate');
Route::post('confirmation/update', 'StudentInformationController@confirmationUpdate');
