<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SIMSRegistrationController;
use App\Http\Controllers\StudentForms;
use App\Http\Controllers\StudentInformationController;
use Illuminate\Support\Facades\Route;

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
    Route::get('guardian/{id}/confirm', [StudentInformationController::class, 'getGuardianVerification']);
    Route::get('ap', [StudentInformationController::class, 'getAPExams']);
    Route::get('ib', [StudentInformationController::class, 'getIBCourses']);

    Route::get('academic_integrity', [StudentInformationController::class, 'showAcademicIntegrityStatement']);
    Route::post('academic_integrity', [StudentInformationController::class, 'completeAcademicIntegrityStatement']);

    Route::get('financial', [StudentInformationController::class, 'showFinancialAcceptance']);
    Route::post('financial', [StudentInformationController::class, 'completeFinancialAcceptance']);

    Route::get('sexual_misconduct_policy', [StudentInformationController::class, 'showTitleIXAcceptance']);
    Route::post('sexual_misconduct_policy', [StudentInformationController::class, 'completeTitleIXAcceptance']);

    Route::get('covid_pledge', [StudentInformationController::class, 'showCovidForm']);
    Route::post('covid_pladge', [StudentInformationController::class, 'completeCovidForm']);
});

Route::middleware('force_login')->get('/', function () {
    $user = RCAuth::user();
    $universal = (RCAuth::check('Universal') || RCAuth::attempt('Universal'));
    if ($universal) {
        return redirect()->action([AdminController::class, 'index']);
    } else {
        return redirect()->action([StudentInformationController::class, 'index']);
    }
});

Route::prefix('admin')->middleware('rsi_admin')->group(function () {
    Route::get('/', [AdminController::class, 'index']);
    Route::get('changes', [AdminController::class, 'changedStudents']);
    Route::get('parents', [AdminController::class, 'changedParentInfo']);
    Route::post('completed/students', [AdminController::class, 'markStudentsProcessed']);
    Route::post('completed/parents', [AdminController::class, 'markParentsProcessed']);
    Route::post('lookup', [AdminController::class, 'lookupMissingInfo']);
    // Route::get("academic_achievement/export", [AdminController::class, 'exportAcademicAchievementCSV']); - Deprecated
});

Route::prefix('orientation')->middleware(['force_login'])->group(function () {
    Route::middleware('populate_dependencies')->group(function () {
        Route::get('registration/studentinfo', [SIMSRegistrationController::class, 'studentInfoPage']);
        Route::post('registration/studentinfo/store', [SIMSRegistrationController::class, 'studentInfo']);
        Route::get('registration/parentsguests', [SIMSRegistrationController::class, 'parentsGuestsPage']);
        Route::post('registration/parentsguests/store', [SIMSRegistrationController::class, 'parentsGuests']);
        Route::get('registration/modeoftravel', [SIMSRegistrationController::class, 'modeOfTravelPage']);
        Route::post('registration/modeoftravel/store', [SIMSRegistrationController::class, 'modeOfTravel']);
        Route::get('registration/confirmation', [SIMSRegistrationController::class, 'confirmationPage']);
        Route::post('registration/confirmation/store', [SIMSRegistrationController::class, 'confirmation']);
        Route::get('registration/success/{id}/{err?}', [SIMSRegistrationController::class, 'endingPage']);

        Route::get('registration', [SIMSRegistrationController::class, 'index']);
        Route::post('registration', [SIMSRegistrationController::class, 'store']);
        Route::get('confirm', [SIMSRegistrationController::class, 'stage1Confirmation']);
    });

    Route::prefix('admin')->middleware(['sims_admin'])->group(function () {
        Route::get('/', [SIMSRegistrationController::class, 'adminIndex']);
        //Reservation
        Route::get('student/lookup', [SIMSRegistrationController::class, 'adminRegistrationLookup']);
        Route::get('student/lookup/search', [SIMSRegistrationController::class, 'adminRegistrationTypeahead']);
        Route::post('student/edit', [SIMSRegistrationController::class, 'adminRegistrationPullRegistration']);
        Route::post('student/edit/save', [SIMSRegistrationController::class, 'adminRegistrationStore']);
        Route::get('report', [SIMSRegistrationController::class, 'adminReservationReport']);
        Route::get('report/xls', [SIMSRegistrationController::class, 'adminReservationReportExcel']);
        //Registration
        Route::get('registration/student/lookup', [SIMSRegistrationController::class, 'adminRegistrationPage']);
        Route::post('registration/student/edit', [SIMSRegistrationController::class, 'adminRegistrationProcess']);
        Route::get('registration/report', [SIMSRegistrationController::class, 'adminRegistrationReport']);
        Route::get('registration/report/xls', [SIMSRegistrationController::class, 'adminRegistrationReportExcel']);
    });
});

Route::prefix('academic_achievement')->group(function () {
    Route::get('/', [StudentInformationController::class, 'showAcademicAchievement']);
    Route::post('/', [StudentInformationController::class, 'storeAcademicAchievement']);
});

Route::get('/', [StudentInformationController::class, 'index']);

Route::prefix('personal_info')->group(function () {
    Route::get('/', [StudentForms\PersonalInformationController::class, 'show']);
    Route::post('/', [StudentForms\PersonalInformationController::class, 'store']);
});

Route::prefix('address_info')->group(function () {
    Route::get('/', [StudentForms\AddressInformationController::class, 'show']);
    Route::post('/', [StudentForms\AddressInformationController::class, 'store']);
});

Route::prefix('residence_info')->group(function () {
    Route::get('/', [StudentForms\ResidenceInformationController::class, 'show']);
    Route::post('/', [StudentForms\ResidenceInformationController::class, 'store']);
});

Route::prefix('citizen_info')->group(function () {
    Route::get('/', [StudentForms\CitizenshipInformationController::class, 'show']);
    Route::post('/', [StudentForms\CitizenshipInformationController::class, 'store']);
});

Route::prefix('allergy_info')->group(function () {
    Route::get('/', [StudentForms\AllergyInformationController::class, 'show']);
    Route::post('/', [StudentForms\AllergyInformationController::class, 'store']);
});

Route::prefix('medical_info')->group(function () {
    Route::get('/', [StudentForms\MedicalInformationController::class, 'show']);
    Route::post('/', [StudentForms\MedicalInformationController::class, 'store']);
});

Route::prefix('missing_person')->group(function () {
    Route::get('/', [StudentForms\MissingPersonController::class, 'show']);
    Route::post('/', [StudentForms\MissingPersonController::class, 'store']);
});

Route::prefix('emergency_contact')->group(function () {
    Route::get('/', [StudentForms\EmergencyContactController::class, 'show']);
    Route::post('/', [StudentForms\EmergencyContactController::class, 'store']);

    Route::get('/edit/{id?}', [StudentForms\EmergencyContactController::class, 'showEmergencyContact']);
    Route::post('/edit/{id?}', [StudentForms\EmergencyContactController::class, 'storeEmergencyContact']);
    Route::delete('/edit/{id}', [StudentForms\EmergencyContactController::class, 'deleteContact']);

    Route::get('/double_check', [StudentForms\EmergencyContactController::class, 'emergencyDoubleCheck']);
});

Route::get('non_emergency', [StudentInformationController::class, 'nonEmergency']);
Route::post('non_emergency/update', [StudentInformationController::class, 'nonEmergencyUpdate']);

Route::get('independent_student', [StudentInformationController::class, 'independentStudent']);
Route::post('independent_student/update', [StudentInformationController::class, 'independentStudentUpdate']);

route::prefix('guardians')->group(function () {
    Route::get('/', [StudentForms\GuardianInformationController::class, 'show']);
    Route::get('/{id}/verify', [StudentForms\GuardianInformationController::class, 'getGuardianVerification']);
    Route::delete('/{id?}', [StudentForms\GuardianInformationController::class, 'deleteGuardian']);

    Route::get('/personal/{id?}', [StudentForms\GuardianInformationController::class, 'guardianPersonalShow']);
    Route::post('/personal/{id?}', [StudentForms\GuardianInformationController::class, 'guardianPersonalStore']);

    Route::get('/employment/{id?}', [StudentForms\GuardianInformationController::class, 'guardianEmploymentShow']);
    Route::post('/employment/{id?}', [StudentForms\GuardianInformationController::class, 'guardianEmploymentStore']);
});
Route::get('missing', [StudentInformationController::class, 'getMissingMessages']);
Route::post('confirmation/update', [StudentInformationController::class, 'confirmationUpdate']);
