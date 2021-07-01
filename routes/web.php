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

Route::get('login', function () {
    $returnURL = Session::get('returnURL', Request::url().'/../');

    return RCAuth::redirectToLogin($returnURL);
});

Route::get('logout', function () {
    RCAuth::logout();
    $returnURL = Request::url().'/../';

    return RCAuth::redirectToLogout($returnURL);
});

Route::middleware('force_login')->group(function () {
    Route::get('guardian/{id}/confirm', 'StudentInformationController@getGuardianVerification');
    Route::get('ap', 'StudentInformationController@getAPExams');
    Route::get('ib', 'StudentInformationController@getIBCourses');

    Route::get('academic_integrity', 'StudentInformationController@showAcademicIntegrityStatement');
    Route::post('academic_integrity', 'StudentInformationController@completeAcademicIntegrityStatement');

    Route::get('financial', 'StudentInformationController@showFinancialAcceptance');
    Route::post('financial', 'StudentInformationController@completeFinancialAcceptance');

    Route::get('sexual_misconduct_policy', 'StudentInformationController@showTitleIXAcceptance');
    Route::post('sexual_misconduct_policy', 'StudentInformationController@completeTitleIXAcceptance');

    Route::get('covid_pledge', 'StudentInformationController@showCovidForm');
    Route::post('covid_pladge', 'StudentInformationController@completeCovidForm');
});

Route::middleware('force_login')->get('/', function () {
    $user = RCAuth::user();
    $universal = (RCAuth::check('Universal') || RCAuth::attempt('Universal'));
    if ($universal) {
        return redirect()->action('AdminController@index');
    } else {
        return redirect()->action('StudentInformationController@index');
    }
});

Route::prefix('admin')->middleware('rsi_admin')->group(function () {
    Route::get('/', 'AdminController@index');
    Route::get('changes', 'AdminController@changedStudents');
    Route::get('parents', 'AdminController@changedParentInfo');
    Route::post('completed/students', 'AdminController@markStudentsProcessed');
    Route::post('completed/parents', 'AdminController@markParentsProcessed');
    Route::post('lookup', 'AdminController@lookupMissingInfo');
    // Route::get("academic_achievement/export", "AdminController@exportAcademicAchievementCSV"); - Deprecated
});

Route::prefix('orientation')->middleware(['force_login'])->group(function () {
    Route::middleware('populate_dependencies')->group(function () {
        Route::get('registration/studentinfo', 'SIMSRegistrationController@studentInfoPage');
        Route::post('registration/studentinfo/store', 'SIMSRegistrationController@studentInfo');
        Route::get('registration/parentsguests', 'SIMSRegistrationController@parentsGuestsPage');
        Route::post('registration/parentsguests/store', 'SIMSRegistrationController@parentsGuests');
        Route::get('registration/modeoftravel', 'SIMSRegistrationController@modeOfTravelPage');
        Route::post('registration/modeoftravel/store', 'SIMSRegistrationController@modeOfTravel');
        Route::get('registration/confirmation', 'SIMSRegistrationController@confirmationPage');
        Route::post('registration/confirmation/store', 'SIMSRegistrationController@confirmation');
        Route::get('registration/success/{id}/{err?}', 'SIMSRegistrationController@endingPage');

        Route::get('registration', 'SIMSRegistrationController@index');
        Route::post('registration', 'SIMSRegistrationController@store');
        Route::get('confirm', 'SIMSRegistrationController@stage1Confirmation');
    });

    Route::prefix('admin')->middleware(['sims_admin'])->group(function () {
        Route::get('/', 'SIMSRegistrationController@adminIndex');
        //Reservation
        Route::get('student/lookup', 'SIMSRegistrationController@adminRegistrationLookup');
        Route::get('student/lookup/search', 'SIMSRegistrationController@adminRegistrationTypeahead');
        Route::post('student/edit', 'SIMSRegistrationController@adminRegistrationPullRegistration');
        Route::post('student/edit/save', 'SIMSRegistrationController@adminRegistrationStore');
        Route::get('report', 'SIMSRegistrationController@adminReservationReport');
        Route::get('report/xls', 'SIMSRegistrationController@adminReservationReportExcel');
        //Registration
        Route::get('registration/student/lookup', 'SIMSRegistrationController@adminRegistrationPage');
        Route::post('registration/student/edit', 'SIMSRegistrationController@adminRegistrationProcess');
        Route::get('registration/report', 'SIMSRegistrationController@adminRegistrationReport');
        Route::get('registration/report/xls', 'SIMSRegistrationController@adminRegistrationReportExcel');
    });
});

Route::prefix('academic_achievement')->group(function () {
    Route::get('/', 'StudentInformationController@showAcademicAchievement');
    Route::post('/', 'StudentInformationController@storeAcademicAchievement');
});

Route::get('/', 'StudentInformationController@index');

Route::prefix('personal_info')->group(function () {
    Route::get('/', 'StudentForms\PersonalInformationController@show');
    Route::post('/', 'StudentForms\PersonalInformationController@store');
});

Route::prefix('address_info')->group(function () {
    Route::get('/', 'StudentForms\AddressInformationController@show');
    Route::post('/', 'StudentForms\AddressInformationController@store');
});

Route::prefix('residence_info')->group(function () {
    Route::get('/', 'StudentForms\ResidenceInformationController@show');
    Route::post('/', 'StudentForms\ResidenceInformationController@store');
});

Route::prefix('citizen_info')->group(function () {
    Route::get('/', 'StudentForms\CitizenshipInformationController@show');
    Route::post('/', 'StudentForms\CitizenshipInformationController@store');
});

Route::prefix('allergy_info')->group(function () {
    Route::get('/', 'StudentForms\AllergyInformationController@show');
    Route::post('/', 'StudentForms\AllergyInformationController@store');
});

Route::prefix('medical_info')->group(function () {
    Route::get('/', 'StudentForms\MedicalInformationController@show');
    Route::post('/', 'StudentForms\MedicalInformationController@store');
});

Route::prefix('missing_person')->group(function () {
    Route::get('/', 'StudentForms\MissingPersonController@show');
    Route::post('/', 'StudentForms\MissingPersonController@store');
});

Route::prefix('emergency_contact')->group(function () {
    Route::get('/', 'StudentForms\EmergencyContactController@show');
    Route::post('/', 'StudentForms\EmergencyContactController@store');

    Route::get('/edit/{id?}', 'StudentForms\EmergencyContactController@showEmergencyContact');
    Route::post('/edit/{id?}', 'StudentForms\EmergencyContactController@storeEmergencyContact');
    Route::delete('/edit/{id}', 'StudentForms\EmergencyContactController@deleteContact');

    Route::get('/double_check', 'StudentForms\EmergencyContactController@emergencyDoubleCheck');
});

Route::get('non_emergency', 'StudentInformationController@nonEmergency');
Route::post('non_emergency/update', 'StudentInformationController@nonEmergencyUpdate');

Route::get('independent_student', 'StudentInformationController@independentStudent');
Route::post('independent_student/update', 'StudentInformationController@independentStudentUpdate');

route::prefix('guardians')->group(function () {
    Route::get('/', "StudentForms\GuardianInformationController@show");
    Route::get('/{id}/verify', "StudentForms\GuardianInformationController@getGuardianVerification");
    Route::delete('/{id?}', "StudentForms\GuardianInformationController@deleteGuardian");

    Route::get('/personal/{id?}', "StudentForms\GuardianInformationController@guardianPersonalShow");
    Route::post('/personal/{id?}', "StudentForms\GuardianInformationController@guardianPersonalStore");

    Route::get('/employment/{id?}', "StudentForms\GuardianInformationController@guardianEmploymentShow");
    Route::post('/employment/{id?}', "StudentForms\GuardianInformationController@guardianEmploymentStore");
});
Route::get('missing', 'StudentInformationController@getMissingMessages');
Route::post('confirmation/update', 'StudentInformationController@confirmationUpdate');
