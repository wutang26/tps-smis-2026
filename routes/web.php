<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttendenceController;
use App\Http\Controllers\BeatController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseWorkController;
use App\Http\Controllers\CourseworkResultController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ExcuseTypeController;
use App\Http\Controllers\FinalResultController;
use App\Http\Controllers\GradeMappingController;
use App\Http\Controllers\GradingSystemController;
use App\Http\Controllers\GuardAreaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IntakeHistoryController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\MPSController;
use App\Http\Controllers\MPSVisitorController;
use App\Http\Controllers\NotificationAudienceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationTypesController;
use App\Http\Controllers\OptionalCourseEnrollmentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatrolAreaController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\ProgrammeCourseSemesterController;
use App\Http\Controllers\RefereeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SafariStudentController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SemesterExamController;
use App\Http\Controllers\SemesterExamResultController;
use App\Http\Controllers\SessionProgrammeController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffProgrammeCourseController;
use App\Http\Controllers\StaffSummaryController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentPostController;
use App\Http\Controllers\TeacherOnDutyController;
use App\Http\Controllers\TerminationReasonController;
use App\Http\Controllers\TimeSheetController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VitengoController;
use App\Http\Controllers\WeaponController;
use App\Http\Controllers\WeaponModelController;
use App\Http\Controllers\WeaponBorrowerController;
use App\Http\Controllers\BorrowedWeaponController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;





require __DIR__.'/auth.php';

Auth::routes();

Route::get('/tps_smis', [HomeController::class, 'index'])->name('home');
Route::get('/imagick-test', function () {
    return extension_loaded('imagick') ? 'Imagick is working!' : 'Imagick not loaded.';
});




// Route::get('/', function () {
//     return view('dashboard.default_dashboard');
// });

Route::get('/dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');

Route::group(['middleware' => ['auth', 'verified', 'check_active_session']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/content', [DashboardController::class, 'getContent'])->name('dashboard.content');
    // Route::get('/dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');
});

Route::middleware(['auth', 'checkCourseInstructor'])->group(function () {});

// Route::middleware(['auth', 'checkCourseInstructor'])->group(function () {
//     Route::get('/course/{course}', [CourseController::class, 'show'])->name('course.show');
//     Route::get('/course/{course}/coursework', [CourseworkController::class, 'index'])->name('coursework.index');
//     Route::post('/course/{course}/coursework', [CourseworkController::class, 'store'])->name('coursework.store');
//     Route::put('/course/{course}/coursework/{id}', [CourseworkController::class, 'update'])->name('coursework.update');

// });

Route::get('/students/registration', [StudentController::class, 'createPage'])->name('students.createPage');
Route::post('/students/registration', [StudentController::class, 'register'])->name('students.register');

Route::middleware(['auth', 'check.student.status'])->group(function () {
    Route::get('/students/courses', [StudentController::class, 'myCourses'])->name('students.myCourses');
    Route::get('/student/home', [StudentController::class, 'dashboard'])->name('students.dashboard');
    Route::get('/students/courseworks', [CourseworkResultController::class, 'coursework'])->name('students.coursework');
        Route::get('/courseworks/student/{studentId}', [CourseworkResultController::class, 'studentCoursework'])->name('student.courseworks');

    Route::get('/students/exam-results', [FinalResultController::class, 'showExamResults'])->name('students.final_results');
    // Route::resource('students', StudentController::class);

});

Route::post('/tps-smis/broadcasting/auth', function (Request $request) {
    return Broadcast::auth($request);
})->middleware(['web', 'auth']);

Route::group(['middleware' => ['auth']], function () {

    Route::get('/student/generate-certificate', [StudentController::class, 'generateCertificate']);

    Route::get('/student/intake_summary', [IntakeHistoryController::class, 'index']);
    Route::get('students/filter', [IntakeHistoryController::class, 'filterStudents'])->name('students.filter');
    Route::get('/regions/by-session', [StudentController::class, 'getRegionsBySession'])->name('regions.bySession');

    Route::get('/default', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/print-certificates', [FinalResultController::class, 'studentList'])->name('studentList');
    Route::get('/print-certificates/summary', [FinalResultController::class, 'summary'])->name('studentList');
    Route::get('/students', [StudentController::class, 'index'])->name(name: 'students.index');
    Route::post('students/search', [StudentController::class, 'search'])->name('students.search');
    Route::get('students/search_certificate/{companyId}', [FinalResultController::class, 'search'])->name('students.search_certificate');

    Route::get('/staff/cv/{staffId}', [StaffController::class, 'generateResume'])->name('staff.cv');
    Route::post('/staff/work_experience/delete/{experienceId}', [StaffController::class, 'deleteWorkExprience'])->name('staff.delete_experience');
    Route::post('/staff/school/delete/{schoolId}', [StaffController::class, 'deleteSchool'])->name('staff.delete_school');
    Route::get('/semesters/{semesterId}/courses', [CourseworkResultController::class, 'index'])->name('semesters.index');

    Route::get('courseworks/{semesterId}/{courseId}', [CourseworkController::class, 'getCourseworks']);

    // Route::get('/coursework_results/course/{course}', [CourseworkResultController::class, 'getResultsByCourse']);
    Route::get('/coursework_results/coursework/{coursework}', [CourseworkResultController::class, 'getResultsByCourse']);

    Route::get('/coursework/summary/{id}', [CourseworkResultController::class, 'summary'])->name('coursework.summary');
    Route::get('/coursework/upload_cw/{courseId}', [CourseworkResultController::class, 'create_import'])->name('coursework.upload_explanation');
    Route::post('/coursework/upload/{courseId}', [CourseworkResultController::class, 'import'])->name('coursework.upload');
    Route::get('/update-fasting-status/{studentId}/{fastingStatus}', [StudentController::class, 'updateFastStatus'])->name('updateFastingStatus');
    Route::get('/update-beat-status-to-safari/{studentId}', [StudentController::class, 'toSafari'])->name('students.toSafari');
    Route::get('/update-beat-status-back-from-safari/{studentId}', [StudentController::class, 'BackFromsafari'])->name('students.BackFromsafari');
    Route::get('/students/approve/{studentId}', [StudentController::class, 'approve'])->name('students.approve');
    Route::post('/students/update-passwords', [StudentController::class, 'updatePasswords'])->name('students.update.passwords');

    // Route::get('/coursework/upload_explanation/{courseId}', [CourseworkResultController::class, 'create_import'])->name('coursework.upload_explanation');

    Route::post('/beats/{id}', [BeatController::class, 'update'])->name('beat.update');
    Route::get('/beats/generate', [BeatController::class, 'beatCreate'])->name('beats.beatCreate');
    Route::get('/beats', [BeatController::class, 'beatsByDate'])->name('beats.byDate');
    Route::delete('/beats/{id}', [BeatController::class, 'destroy'])->name('beats.destroy');
    Route::get('/beats/{id}/edit', [BeatController::class, 'edit'])->name('beats.edit');
    Route::post('/fill-beats', [BeatController::class, 'fillBeats'])->name('beats.fillBeats');
    // Route::get('/beats', [BeatController::class, 'showBeats'])->name('beats.index');
    Route::get('/beats/{beat}', [BeatController::class, 'showBeat'])->name('beats.show');

    Route::get('/beats/pdf/{company}', [BeatController::class, 'generatePDF'])->name('beats.generatePDF');
    Route::post('/generate-transcript', [FinalResultController::class, 'generateTranscript'])->name('final.generateTranscript');
    Route::post('/generate-certificate', [FinalResultController::class, 'generateCertificate'])->name('final.generateCertificate');
    

//Additional Rputes   Kujua walio Baki
Route::get('/test-beats', [BeatController::class, 'testGenerateBeats']);
Route::get('/test-beats', [BeatController::class, 'assignedOnBeats'])->name('beats.test_beats');
Route::get('/beats/guards/skipped', [BeatController::class, 'skippedStudents'])
    ->name('beats.guards.skipped');

//Logging beat assignments
// Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/beat_assignment_logs', [BeatController::class, 'viewBeatLogs'])->name('beats.beat_assignment_logs');
// });



    // Route to generate and display the report
    Route::get('/report/generate', [BeatController::class, 'showReport'])->name('report.generate');
    // Route to download the report as a PDF
    Route::get('/report/history/{companyId}', [BeatController::class, 'downloadHistoryPdf'])->name('report.history');

    Route::get('/beats/reserves/{companyId}/{date}', [BeatController::class, 'beatReserves'])->name('beats.reserves');
    Route::get('/beats/approve-reserve/{studentId}', [BeatController::class, 'approveReserve'])->name('beats.approve-reserve');
    Route::get('/beats/reserve-replacement/{reserveId}/{date}/{beatReserveId}', [BeatController::class, 'beatReplacementStudent'])->name('beats.reserve-replacement');
    Route::post('/beats/replace-reserve/{reserveId}/{studentId}/{date}/{beatReserveId}', [BeatController::class, 'beatReserveReplace'])->name('beats.replace-reserve');
    Route::get('/beats/create-exchange/{beat}', [BeatController::class, 'createExchange'])->name(name: 'beats.create-exchange');
    Route::post('/beats/exchange/{beat}', [BeatController::class, 'exchange'])->name(name: 'beats.exchange');

    Route::get('/students/downloadSample', [StudentController::class, 'downloadSample'])->name('studentDownloadSample');
    Route::get('/staff/downloadSample', [StaffController::class, 'downloadSample'])->name('staffDownloadSample');
    Route::get('/courseworkResult/downloadSample', [CourseworkResultController::class, 'downloadSample'])->name('courseworkResultDownloadSample');
    Route::get('students/upload-students', function () {
        return view('students.bulk_upload_explanation');
    })->name('uploadStudents');

    Route::get('students/update-students', function () {
        return view('students.bulk_update_student');
    })->name('updateStudents');

    Route::get('staff/upload-staff', function () {return view('staffs.bulk_upload_explanation');})->name('uploadStaff');
    Route::get('staff/update-staff', function () {return view('staffs.bulk_update_staff');})->name('updateStaffs');

    Route::get('/staffs/summary', [StaffSummaryController::class, 'index'])->name('staffs.summary.index');
    Route::get('staff/filter', [StaffSummaryController::class, 'filterStaff'])->name('staff.filter');
    Route::post('staff/change-status/', [StaffController::class, 'change_status'])->name('staff.change_status');
    Route::get('staff/search', [StaffController::class, 'search'])->name('staff.search');
    Route::get('/staff/create-cv/{staffId}', [StaffController::class, 'create_cv'])->name('staff.create-cv');
    Route::post('/staff/update-cv/{staffId}', [StaffController::class, 'update_cv'])->name('staff.update-cv');
    Route::post('/staff/update-school-cv/{staffId}', [StaffController::class, 'update_school_cv'])->name('staff.update_school-cv');
    Route::post('/staff/update_other_courses-cv/{staffId}', [StaffController::class, 'update_school_cv'])->name('staff.update_other_courses-cv');
    Route::post('/staff/update_work_experiences-cv/{staffId}', [StaffController::class, 'update_work_experience'])->name('staff.update_work_experience-cv');
    Route::get('/staff/generateResumeePdf/{staffId}', [StaffController::class, 'generateResumeePdf'])->name('staff.generateResumeePdf');
    Route::get('/assigned-instructors/{courseId}', [StaffProgrammeCourseController::class, 'showAssignInstructorsForm'])->name('assign.instructors.form');
    Route::post('/assign-instructors', [StaffProgrammeCourseController::class, 'assignInstructors'])->name('assign.instructors');
    Route::post('/unassign-instructors/{courseInstructorId}', [StaffProgrammeCourseController::class, 'unAssignInstructor'])->name('unassign.course');
    Route::put('/students/{id}/dismiss', [StudentController::class, 'dismiss'])->name('students.dismiss');

    Route::controller(StudentController::class)->prefix('students')->group(function () {
        Route::post('activate_beat_status/{studentId}', 'activate_beat_status')->name('students.activate_beat_status');
        Route::post('deactivate_beat_status/{studentId}', 'deactivate_beat_status')->name('students.deactivate_beat_status');
        /**
         *  Wizard route for student registration
         */
        Route::prefix('create')->group(function () {
            Route::get('step-two/{type}', 'createStepTwo');
            Route::get('step-three/{type}', [StudentController::class, 'createStepThree']);

            Route::get('step-three', 'createStepTwo');
            Route::post('post-step-one/{type}', 'postStepOne');
            Route::post('post-step-two/{type}', 'postStepTwo');
            Route::post('post-step-three/{type}', 'postStepThree');
        });
        /**
         * End of wizard for student registration
         */
        Route::get('dashboard', 'dashboard');
        Route::post('store', 'store');
        Route::post('{id}/update', 'update');
        Route::post('{id}/delete', 'destroy');

        Route::post('bulkimport', 'import');

    });

    Route::resource('students', StudentController::class);

});

Route::group(['middleware' => ['auth']], function () {

    Route::controller(TeacherOnDutyController::class)->prefix('teacher-on-duty')->group(function () {
        Route::get('', 'index')->name('teacher-on-duty');
        Route::get('search', 'search')->name('teacher_on_duty.search');
        Route::get('store/{staffId}', 'store')->name('teacher_on_duty.store');
        Route::get('unassign/{teacherId}', 'unassign')->name('teacher_on_duty.unassign');
    });
    // Define the custom route first
    Route::get('platoons/{companyName}', [AttendenceController::class, 'getPlatoons']);
    Route::get('assign-courses/{id}', [ProgrammeCourseSemesterController::class, 'assignCourse'])->name('assign-courses.assignCourse');

    // Define the custom route first
    Route::post(
        '/programmes/{programmeId}/semesters/{semesterId}/session/{sessionProgrammeId}/assign-courses',
        [ProgrammeController::class, 'assignCoursesToSemester']
    );

    Route::controller(MPSController::class)->prefix('mps')->group(function () {
        Route::get('/all', 'all')->name('mps.all');
        Route::post('search', 'search');
        Route::post('store/{id}', 'store');
        Route::get('show/{studentId}', 'show')->name('mps.show');
        Route::put('release/{id}', 'release')->name('mps.release');
        Route::get('{company}/company', 'company');
    });

    Route::controller(MPSVisitorController::class)->prefix('visitors')->group(function () {
        Route::post('index', 'index')->name('visitors.index');
        Route::post('store/{studentId}', 'store')->name('visitors.store');
        Route::get('show/{studentId}', 'show')->name('visitors.show');
        Route::post('update/{studentId}', 'update')->name('visitors.update');
        Route::post('search-student', 'searchStudent')->name('visitors.searchStudent');
    });
    Route::get('final_results/semester/{semesterId}/course/{courseId}', [FinalResultController::class, 'getResults'])->name('final_results');
    Route::get('final_results/create-generate', [FinalResultController::class, 'createGenerate'])->name('final_results.createGenerate');
    Route::get('final_results/generate/{sessionId}', [FinalResultController::class, 'generate'])->name('final_results.generate');
    Route::get('final_results/generate-all', [FinalResultController::class, 'generateAll'])->name('final_results.generate.all');
    Route::post('final_results/generate/{sessionProgrammeId}', [FinalResultController::class, 'generate'])->name('final_results.session.generate');
    Route::get('final_results/return/semester/{semesterId}/course/{courseId}', [FinalResultController::class, 'returnResults'])->name('final_results.return');
    Route::get('final_results/semester/{semesterId}/course/{courseId}', [FinalResultController::class, 'getResults'])->name('final_results');
    Route::get('final_results/student/{studentId}', [FinalResultController::class, 'getStudentResults'])->name('student.final_results');
    Route::post('/staff/bulkimport', [StaffController::class, 'import'])->name('staff.bulkimport');
    Route::post('/students/bulk-update-students', [StudentController::class, 'updateStudents'])->name('student.updateStudents');
    Route::post('/staffs/bulk-update-staffs', [StaffController::class, 'bulkUpdate'])->name('staff.bulkUpdate');
    Route::get('/staff/profile/{id}', [StaffController::class, 'profile'])->name('staff.profile');
    Route::get('/student/profile/{id}', [StudentController::class, 'profile'])->name('profile');
    Route::get('/profile/change-password/{id}', [UserController::class, 'changePassword'])->name('changePassword');
    Route::post('/profile/change-password/{id}', [UserController::class, 'updatePassword'])->name('updatePassword');
    Route::get('users/search', [UserController::class, 'search'])->name('users.search');

    Route::get('assign-courses/{id}', [ProgrammeCourseSemesterController::class, 'assignCourse'])->name('assign-courses.assignCourse');
    Route::post('/students/{id}/approve', [StudentController::class, 'approveStudent'])->name('students.approve');
    Route::get('/student/complete-profile/{id}', [StudentController::class, 'completeProfile'])->name('students.complete_profile');
    Route::put('/student/profile-complete/{id}', [StudentController::class, 'profileComplete'])->name('students.profile_complete');

    Route::get('patrol-areas/{patrolArea}/edit', [PatrolAreaController::class, 'edit'])->name('patrol-areas.edit');
    Route::put('patrol-areas/{patrolArea}', [PatrolAreaController::class, 'update'])->name('patrol-areas.update');
    Route::get('guard-areas/{guardArea}/edit', [GuardAreaController::class, 'edit'])->name('guard-areas.edit');
    Route::put('guard-areas/{guardArea}', [GuardAreaController::class, 'update'])->name('guard-areas.update');
    Route::put('timesheets/{timesheetId}/reject', [TimeSheetController::class, 'reject'])->name('timesheets.reject');
    Route::put('timesheets/{timesheetId}/approve', [TimeSheetController::class, 'approve'])->name('timesheets.approve');
    Route::post('timesheets/filter', [TimeSheetController::class, 'filter'])->name('timesheets.filter');

    Route::post('students/{student}/safari/', [SafariStudentController::class, 'store'])->name('storeSafariStudent');
    Route::put('students/return-safari/{safariStudent}', [SafariStudentController::class, 'updateSafari'])->name('returnSafariStudent');

    Route::prefix('vitengo ')->controller(VitengoController::class)->group(function () {
        Route::post('activate', 'activate')->name('vitengo.activate');
        Route::post('deactivate', 'deactivate')->name('vitengo.deactivate');
    });

    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('campuses', CampusController::class);
    Route::resource('session_programmes', SessionProgrammeController::class);
    Route::resource('programmes', ProgrammeController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('products', ProductController::class);
    Route::resource('attendences', AttendenceController::class);
    Route::resource('mps', MPSController::class);
    Route::resource('staffs', StaffController::class);
    Route::resource('campuses', CampusController::class);
    Route::resource('announcements', AnnouncementController::class);
    Route::resource('visitors', MPSVisitorController::class);
    Route::resource('timesheets', TimeSheetController::class);
    Route::resource('guard-areas', GuardAreaController::class);
    Route::resource('patrol-areas', PatrolAreaController::class);
    Route::resource('safari-students', SafariStudentController::class);
    Route::resource('certificates', CertificateController::class);
    Route::resource('intake_history', IntakeHistoryController::class);
    Route::resource('posts', PostController::class);

    Route::resource('companies', CompanyController::class);
    Route::resource('vitengo', VitengoController::class);

    Route::prefix('students-post')->controller(StudentPostController::class)->group(function () {
        Route::post('bulkimport', 'import')->name('students-post.bulkimport');
        Route::get('search', 'search')->name('students-post.search');
        Route::get('edit-post', 'edit_post')->name('students-post.edit_post');
        Route::get('downloadSample', 'downloadSample')->name('students-post.downloadSample');
        Route::get('updateDownloadSample', 'updateDownloadSample')->name('students-post.updateDownloadSample');
    });
    Route::prefix('posts')->controller(PostController::class)->group(function () {
        Route::post('publish/{post}', 'publish')->name('post.publish');
    });
    Route::resource('students-post', StudentPostController::class);

    // Define the custom route first

    Route::prefix('companies')->controller(CompanyController::class)->group(function () {
        Route::post('/{companyId}/store/platoon', 'create_platoon')->name('companies.platoon.store');
        Route::get('/{platoonId}/platoon/delete', 'destroy_platoon')->name('companies.platoon.destroy');
    });

    // Notification routes

    Route::prefix('/notifications')->group(function () {
        Route::prefix('/audiences')->controller(NotificationAudienceController::class)->group(function () {
            Route::get('/', 'index')->name('notifications.audiences.index');
            Route::get('/create', 'create')->name('notifications.audiences.create');
            Route::get('/edit/{notificationAudience}', 'edit')->name('notifications.audiences.edit');
            Route::delete('/destroy/{notificationAudience}', 'destroy')->name('notifications.audiences.destroy');
            Route::get('/{notificationAudience}', 'show')->name('notifications.audiences.show');
            Route::post('/store', 'store')->name('notifications.audiences.store');
            Route::put('/update/{notificationAudience}', 'update')->name('notifications.audiences.update');
        });

        Route::prefix('/types')->controller(NotificationTypesController::class)->group(function () {
            Route::get('/', 'index')->name('notifications.types.index');
            Route::get('/create', 'create')->name('notifications.types.create');
            Route::get('/edit/{notificationType}', 'edit')->name('notifications.types.edit');
            Route::delete('/destroy/{notificationType}', 'destroy')->name('notifications.types.destroy');
            Route::get('/{notificationType}', 'show')->name('notifications.types.show');
            Route::post('/store', 'store')->name('notifications.types.store');
            Route::put('/update/{notificationType}', 'update')->name('notifications.types.update');
        });
    });

    // End of notification routes

    // routes/web.php
    Route::get('platoons/{companyName}', [AttendenceController::class, 'getPlatoons']);
    Route::get('campanies/{campusId}', [GuardAreaController::class, 'get_companies']);
    Route::get('assign-courses/{id}', [ProgrammeCourseSemesterController::class, 'assignCourse'])->name('assign-courses.assignCourse');
    Route::controller(AttendenceController::class)->prefix('attendences')->group(function () {
        Route::post('store/request', 'requestAttendance')->name('attendance.store.request');
        Route::get('show/request', 'createAttendanceRequests')->name('attendance.show.request');
        Route::post('request/update-status', 'updateRequestStatus')->name('attendance.request.update-status');

        Route::get('type-test/{type_id}', 'attendence');
        Route::get('type/{type_id}', 'attendence')->name('attendances.summary');
        // Route::post('create/{type_id}', 'create');
        Route::post('create/{attendenceType}', 'create')->name('attendences.create');
        Route::get('edit/{id}', 'edit');
        Route::post('{attendenceType_id}/{platoon_id}/store', 'store');
        Route::post('{id}/update', 'update');
        Route::get('list-absent_students/{list_type}/{attendence_id}/{date}', action: 'list');
        Route::get('list-safari_students/{list_type}/{attendence_id}', action: 'list_safari');
        Route::post('store-absents/{attendence_id}/{date}', action: 'storeAbsent');
        Route::post('store-safari/{attendence_id}', action: 'storeSafari');
        Route::get('today/{company_id}/{type}', 'today');
        Route::get('generatepdf/{companyId}/{date}/{attendenceTypeId}', 'generatePdf')->name('attendences.generatePdf');
        Route::get('changanua/{attendenceId}/', 'changanua')->name('attendences.changanua');
        Route::post('storeMchanganuo/{attendenceId}/', 'storeMchanganuo')->name('attendences.storeMchanganuo');

        Route::get('today/{company_id}/{type}/{date}/{attendenceTypeId}', 'today')->name('today');
        Route::patch('/companies/{company}/attendance/{date}', 'updateCompanyAttendance')->name('attendance.updateCompanyAttendance');
    });

    Route::controller(ReportController::class)->prefix('reports')->group(function () {
        Route::get('/', 'index')->name('reports.index');
        Route::get('attendance/generate-report', 'generateAttendanceReport')->name('reports.generateAttendanceReport');
        Route::get('hospital', 'hospital')->name('reports.hospital');
        Route::get('hospital/generate-report', 'generateHospitalReport')->name('reports.generateHospitalReport');
        Route::get('leaves', 'leaves')->name('reports.leaves');
        Route::get('leaves/generate-report', 'generateLeavesReport')->name('reports.generateLeavesReport');
        Route::get('mps', 'mps')->name('reports.mps');
        Route::get('mps/generate-report', 'generateMPSReport')->name('reports.generateMPSReport');

    });

    // Route::controller(AttendenceController::class)->prefix('attendences')->group(function () {});

    Route::get('course/courseworks/create/{courseId}', [CourseWorkController::class, 'create'])->name('course.coursework.create');
    Route::get('course/courseworks/{courseId}', [CourseWorkController::class, 'getCourse'])->name('course.coursework');
    Route::post('course/courseworks/store/{courseId}', [CourseWorkController::class, 'store'])->name('course.coursework.store');
    Route::post('enrollments/sessions', [EnrollmentController::class, 'store'])->name('enrollments.session.store');
    Route::post('enrollments/sessions/delete/{id}', [EnrollmentController::class, 'destroy'])->name('enrollments.session.delete');
    // Route::get('course/courseworks/create/{courseId}',[SemesterExamController::class,'create'])->name('course.coursework.create');
    // Route::get('course/courseworks/{courseId}',[SemesterExamController::class,'getCourse'])->name('course.coursework');
    Route::post('course/semester_exams/store/{courseId}', [SemesterExamController::class, 'store'])->name('course.semester_exams.store');
    Route::get('/tasks/{task}/assign', [TaskController::class, 'assignForm'])->name('tasks.assign');
    Route::post('/tasks/{task}/assign', [TaskController::class, 'assignStaff'])->name('tasks.assign.store');
    Route::get('/tasks/{task}/staff', [TaskController::class, 'showStaff'])->name('tasks.staff');
    Route::get('/tasks/{task}/staff/export', [TaskController::class, 'exportAssignedStaff'])->name('tasks.staff.export');


    Route::resource('grading_systems', GradingSystemController::class);
    Route::resource('grade_mappings', GradeMappingController::class);
    Route::resource('semesters', SemesterController::class);
    Route::resource('assign-courses', ProgrammeCourseSemesterController::class);
    Route::resource('enrollments', OptionalCourseEnrollmentController::class);
    Route::resource('course_works', CourseWorkController::class);
    Route::resource('coursework_results', CourseworkResultController::class);
    Route::resource('semester_exams_config', SemesterExamController::class);
    Route::resource('semester_exams',  SemesterExamResultController::class);
    Route::resource('final_results', FinalResultController::class);
    Route::resource('/settings/excuse_types', ExcuseTypeController::class);
    Route::resource('/settings/termination_reasons', TerminationReasonController::class);
    Route::resource('guard-areas', GuardAreaController::class);
    Route::resource('patrol-areas', PatrolAreaController::class);
    Route::resource('attendences', AttendenceController::class);
    Route::resource('audit-logs', AuditLogController::class);
    Route::resource('tasks', TaskController::class);


    Route::controller(RefereeController::class)->prefix('referee')->group(function () {
        Route::get('/referee/delete/{refereeId}', 'destroy')->name('referees.delete');
    });

    Route::resource('referees', RefereeController::class);

    Route::controller(SemesterExamResultController::class)->prefix('semester_exams')->group(function () {
        // Route::get('/upload_explanation/{courseId}/{semesterId}', 'getUploadExplanation')->name('exam.upload_explanation');
        // Route::post('/course_results/upload/{courseId}', 'uploadResults')->name('course_exam_results.upload');
        // Route::get('/course_results/configure/{courseId}', 'configure')->name('exam.configure');
        // Route::post('/course_results/store/{courseId}', 'store')->name('course.exam_result.store');
        Route::get('/course_results/course/{courseId}/{semesterId}', 'getExamResultsByCourse')->name('course.getExamResults');
        
        Route::get('/semester_exam_results/{courseId}/{semesterId}', 'getExamResultsByCourse')->name('course.getExamResults');
    });



    Route::controller(SemesterExamController::class)->prefix('semester_exams')->group(function () {
        Route::get('/upload_explanation/{courseId}/{semesterId}', 'getUploadExplanation')->name('exam.upload_explanation');
        Route::post('/upload/{courseId}', 'uploadResults')->name('course_exam_results.upload');
        Route::get('/configure/{courseId}', 'configure')->name('exam.configure');
        Route::post('/store/{courseId}', 'store')->name('course.store');

    });
    // Route::resource('beats', BeatController::class);

    Route::controller(StudentController::class)->prefix('students')->group(function () {
        /**
         *  Wizard route for student registration
         */
        Route::prefix('create')->group(function () {
            Route::get('step-two/{type}', 'createStepTwo');
            Route::get('step-three/{type}', [StudentController::class, 'createStepThree']);

            Route::get('step-three', 'createStepTwo');
            Route::post('post-step-one/{type}', 'postStepOne');
            Route::post('post-step-two/{type}', 'postStepTwo');
            Route::post('post-step-three/{type}', 'postStepThree');
        });
        /**
         * End of wizard for student registration
         */
        Route::get('students/search', 'search')->name('students.search');
        Route::get('dashboard', 'dashboard');
        Route::post('store', 'store');
        Route::post('{id}/update', 'update');
        Route::post('{id}/delete', 'destroy');
        Route::post('bulkimport', 'import');
        Route::get('generatePdf/{platoon}/{companyId}', 'generatePdf')->name('students.generatePdf');
    });

    Route::get('notifications/{notification_category}/{notification_type}/{notification_id}/{ids}', [NotificationController::class, 'show']);
    Route::get('notifications/showNotifications/{notificationIds}/{category}', [NotificationController::class, 'showNotifications'])->name('notifications.showNotifications');

    Route::get('announcement/download/file/{documentPath}', [AnnouncementController::class, 'downloadFile'])->name('download.file');

});

Route::get('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])
    ->name('notifications.markAsRead');

// start
Route::controller(StudentController::class)->prefix('students')->group(function () {
    /**
     *  Wizard route for student registration
     */
    Route::prefix('create')->group(function () {

        Route::get('step-two/{type}', function () {
            return view('students/wizards/stepTwo');
        });
        Route::get('step-three/{type}', function () {
            return view('students/wizards/stepThree');
        });
        Route::post('post-step-one/{type}', 'postStepOne');
        Route::post('post-step-two/{type}', 'postStepTwo');
        Route::post('post-step-three/{type}', 'postStepThree');
    });
    /**
     * End of wizard for student registration
     */
    Route::post('store', 'store');
    Route::post('{id}/update', 'update');
    Route::post('{id}/delete', 'destroy');
    Route::post('bulkimport', 'import');

});
// end

Route::get('/hospital/viewDetails/{timeframe}', [PatientController::class, 'viewDetails'])->name('hospital.viewDetails');
Route::get('/hospital/show/{id}', [PatientController::class, 'show'])->name('hospital.show');

// 🏥 Patients Routes
Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
Route::post('/patients/save', [PatientController::class, 'save'])->name('patients.save');
Route::put('/patients/{id}/update-status', [PatientController::class, 'updateStatus'])->name('update.patient.status');

Route::put('/patient/{id}/status', [PatientController::class, 'updateStatus'])->name('update.patient.status');

Route::get('/hospital', [PatientController::class, 'index'])->name('hospital.index');
Route::get('/patients/show/{studentId}', [PatientController::class, 'showPatient'])->name('patients.show');
Route::post('/patients/submit', [PatientController::class, 'submit'])->name('patients.submit');
Route::patch('/patients/approve/{id}', [PatientController::class, 'approvePatient'])->name('patients.approve');
Route::put('/patients/reject/{id}', [PatientController::class, 'reject'])->name('patients.reject');
Route::put('/patients/treat/{id}', [PatientController::class, 'treat'])->name('patients.treat');
Route::get('patients/search', [PatientController::class, 'search'])->name('patients.search');
Route::get('/dispensary', [PatientController::class, 'dispensaryPage'])->name('dispensary.page');
Route::get('/statistics/download/{timeframe}', [PatientController::class, 'downloadStatisticsReport'])->name('statistics.download');

// 🚀 Routes for Sending to Receptionist
// Route::post('/students/send-to-receptionist', [PatientController::class, 'sendToReceptionist'])->name('students.sendToReceptionist');
Route::post('/hospital/send-to-receptionist', [PatientController::class, 'sendToReceptionist'])->name('hospital.sendToReceptionist');
Route::post('/students/send-to-receptionist', [PatientController::class, 'sendToReceptionist'])->name('students.sendToReceptionist');

// 💼 Receptionist Routes
Route::get('/receptionist', [PatientController::class, 'receptionistPage'])->name('receptionist.index')->middleware('auth');
Route::post('/patients/{id}/approve', [PatientController::class, 'approvePatient'])->name('patients.approve')->middleware('auth');
Route::get('/receptionist', [PatientController::class, 'receptionistPage'])->name('receptionist.index');

// 🩺 Doctor Routes
Route::get('/doctor', [PatientController::class, 'doctorPage'])->name('doctor.page');
// Route::post('/patients/saveDetails', [PatientController::class, 'saveDetails'])->name('patients.saveDetails');
Route::post('/patients/save-details', [PatientController::class, 'saveDetails'])->name('patients.saveDetails');
Route::put('/patients/discharge/{id}', [PatientController::class, 'discharge'])->name('patients.discharge');
Route::put('/patients/{id}/discharge', [PatientController::class, 'discharge'])->name('patients.discharge');
Route::get('/doctor/admitted', [PatientController::class, 'admitted'])->name('doctor.admitted');
Route::post('/doctor/discharge/{id}', [PatientController::class, 'discharge'])->name('doctor.discharge');
// Route::post('/doctor/discharge/{id}', [DoctorController::class, 'discharge'])->name('doctor.discharge');

// 📅 Timetable Routes
Route::get('/timetable', [TimetableController::class, 'index'])->name('timetable.index');
Route::get('/timetable/create', [TimetableController::class, 'create'])->name('timetable.create');
Route::post('/timetable/store', [TimetableController::class, 'store'])->name('timetable.store');
Route::get('/timetable/{id}/edit', [TimetableController::class, 'edit'])->name('timetable.edit');
Route::put('/timetable/{id}', [TimetableController::class, 'update'])->name('timetable.update');
Route::delete('/timetable/{id}', [TimetableController::class, 'destroy'])->name('timetable.destroy');
Route::get('/timetable/export-pdf', [TimetableController::class, 'exportPDF'])->name('timetable.exportPDF');
Route::get('/generate-timetable', [TimetableController::class, 'generateTimetable'])->name('timetable.generate');

// Downloader Centre Routes

Route::middleware(['auth'])->group(function () {
    Route::get('/downloads', [DownloadController::class, 'index'])->name('downloads.index');          // List files
    Route::get('/downloads/upload', [DownloadController::class, 'create'])->name('downloads.create'); // Upload form
    Route::post('/downloads/upload', [DownloadController::class, 'store'])->name('downloads.store');  // Upload action
    Route::get('/download/{file}', [DownloadController::class, 'download'])->name('downloads.file');  // Download file
});

// Route::get('test', [BeatController::class,'beatReplacementStudent']);
Route::get('/downloads', [DownloadController::class, 'index'])->name('downloads.index');
Route::get('/downloads/upload', [DownloadController::class, 'showUploadPage'])->name('downloads.upload.page');
Route::post('/downloads/upload', [DownloadController::class, 'upload'])->name('downloads.upload');
Route::get('/downloads/{file}', [DownloadController::class, 'download'])->name('downloads.file');
Route::delete('/downloads/{id}', [DownloadController::class, 'destroy'])
    ->name('downloads.delete')
    ->middleware('auth'); // Requires login to delete

Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
Route::get('/leave-requests/search', [LeaveRequestController::class, 'search'])->name('leave-requests.search');
Route::post('/leave-requests', [LeaveRequestController::class, 'store'])->name('leave-requests.store');

Route::get('/leave-request', [LeaveRequestController::class, 'showPanel'])->name('leave-requests.panel');
Route::get('/leave-request/show/{studentId}', [LeaveRequestController::class, 'show'])->name('leave-requests.show');

Route::get('/leave-requests/oc-panel', [LeaveRequestController::class, 'ocLeaveRequests'])->name('leave-requests.oc-panel');
Route::post('/oc/leave-requests/forward/{id}', [LeaveRequestController::class, 'forwardToChiefInstructor'])->name('oc.leave-requests.forward');

Route::get('/chief_instructor/leave-requests', [LeaveRequestController::class, 'approvedLeaveRequestsForChiefInstructor'])->name('chief_instructor.leave-requests');
Route::put('/chief-instructor/leave-requests/{id}/approve', [LeaveRequestController::class, 'chiefInstructorApprove'])->name('leave-requests.chief-instructor-approve');
Route::get('/leave-requests/chief_instructor', [LeaveRequestController::class, 'chiefInstructorIndex'])->name('leave-requests.chief-instructor');

Route::put('/leave-requests/{id}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
Route::put('/leave-requests/{id}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
Route::put('/leave-requests/{id}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
Route::delete('/leave-requests/{id}/delete', [LeaveRequestController::class, 'destroy'])->name('leave-requests.delete');
Route::get('/leave-requests/statistics', [LeaveRequestController::class, 'statistics'])->name('leave-requests.statistics');
Route::post('/leave-requests/return/{leaveId}', [LeaveRequestController::class, 'return'])->name('leave_request.return');
Route::get('/leave-requests/statistics/pdf', [LeaveRequestController::class, 'exportPdf'])->name('leave-requests.statistics.pdf');

Route::get('/leave-requests/{id}/download', [LeaveRequestController::class, 'downloadPDF'])->name('leave-requests.download');
Route::get('/leave-requests/{id}/pdf', [LeaveRequestController::class, 'exportSinglePdf'])->name('leave-requests.single.pdf');

Route::get('/leave-requests/rejected', [LeaveRequestController::class, 'rejected'])->name('leave-requests.rejected');
Route::get('/leave-requests/{id}/rejected-pdf', [LeaveRequestController::class, 'downloadRejectedPdf'])->name('leave-requests.rejected.pdf');

Route::get('/staffs/{id}/resume', [StaffController::class, 'generateResume'])->name('staffs.resume');

// Show the form to students
Route::get('/leave-request', [LeaveRequestController::class, 'create'])->name('leave-requests.create');

// Store leave request
Route::post('/leave-request', [LeaveRequestController::class, 'store'])->name('leave-requests.store');


Route::get('weapons/summary', [WeaponController::class, 'summary'])->name('weapons.summary');
Route::get('weapons/uploads', [WeaponController::class, 'get_upload'])->name('weapons.uploads');
Route::post('weapons/bulkimport', [WeaponController::class, 'bulkimport'])->name('weapons.bulkimport');
Route::get('weapons/downloadSample', [WeaponController::class, 'downloadSample'])->name('weapons.downloadSample');
Route::resource('weapons', WeaponController::class);
Route::get('/weapons/{weapon}/handover', [WeaponController::class, 'handover'])->name('weapons.handover');
Route::post('/weapons/{weapon}/handover', [WeaponController::class, 'storeHandover'])->name('weapons.handover.store');
Route::post('/weapons/{weapon}/return', [WeaponController::class, 'return'])->name('weapons.return');

Route::resource('weapon-models', WeaponModelController::class);


Route::get('borrowed-weapons/{borrower}/model/{model}', [WeaponBorrowerController::class, 'show'])
    ->name('borrowed-weapons.model');
Route::patch('borrowed-weapons/approve/{weapon_borrower}', [WeaponBorrowerController::class, 'approve'])
    ->name('weapon-borrowing.approve');

Route::patch('borrowed-weapons/return/{weapon_borrower}', [WeaponBorrowerController::class, 'return'])
    ->name('weapon-borrowing.return');

Route::resource('weapon-borrowing', WeaponBorrowerController::class);
Route::resource('borrowed-weapons', BorrowedWeaponController::class);
// Mark as returned
Route::post('/handovers/{handover}/return', [WeaponController::class, 'returnWeapon'])->name('handovers.return');
Route::post('/handovers/{handover}/return', [WeaponController::class, 'returnWeapon'])->name('handovers.return');
Route::get('/weapons/{id}/handover', [WeaponController::class, 'handover'])->name('weapons.handover');
Route::post('/handover/{id}/return', [WeaponController::class, 'markAsReturned'])->name('handover.return');

Route::get('weapons/create', [WeaponController::class, 'create'])->name('weapons.create');

Route::post('weapons', [WeaponController::class, 'store'])->name('weapons.store');

