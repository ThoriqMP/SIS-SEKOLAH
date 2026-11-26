<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComproController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SchoolProfileController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;

// ----------------------------------------------------
// Global / Language Switcher
// ----------------------------------------------------
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
    }
    if (request()->expectsJson()) {
        return response()->json(['success' => true]);
    }
    return redirect()->back();
})->name('lang.switch');

// Database Setup & Migration Endpoint (Dilindungi APP_KEY)
Route::get('/setup-database', function (\Illuminate\Http\Request $request) {
    $expectedKey = (string) config('app.key');
    $providedKey = (string) $request->query('key');

    if ($expectedKey === '' || !hash_equals($expectedKey, $providedKey)) {
        abort(403, 'Akses ditolak: key tidak valid.');
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrateOut = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        $seedOut = \Illuminate\Support\Facades\Artisan::output();

        return response()->json([
            'status' => 'success',
            'migrate' => trim($migrateOut),
            'seed' => trim($seedOut),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
})->name('setup.database');


// =========================================================================
// 1. PUBLIC ROUTES (Company Profile & Pendaftaran Online)
// =========================================================================
Route::get('/', [ComproController::class, 'index'])->name('compro.index');

// Formulir & Pelacakan Pendaftaran Siswa Baru
Route::prefix('pendaftaran')->name('registration.')->group(function () {
    Route::get('/', [RegistrationController::class, 'create'])->name('create');
    Route::post('/', [RegistrationController::class, 'store'])->name('store');
    Route::get('/sukses/{nomor}', [RegistrationController::class, 'success'])->name('success');
    Route::get('/lacak/{nomor}', [RegistrationController::class, 'track'])->name('track');
});


// =========================================================================
// 2. AUTHENTICATION ROUTES (Guru, Staf & Admin)
// =========================================================================
Route::middleware(['guest:web'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    // Alias routes for teacher login compatibility
    Route::get('/teacher', [AuthController::class, 'showLoginForm'])->name('teacher.index');
    Route::get('/teacher/login', [AuthController::class, 'showLoginForm'])->name('teacher.login');
    Route::post('/teacher/login', [AuthController::class, 'login'])->name('teacher.login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::post('/teacher/logout', [AuthController::class, 'logout'])->name('teacher.logout')->middleware('auth');


// =========================================================================
// 3. STUDENT PORTAL (/student)
// =========================================================================
Route::prefix('student')->name('student.')->group(function () {

    // Language Switcher for Student Portal
    Route::get('/lang/{locale}', function ($locale) {
        if (in_array($locale, ['en', 'id'])) {
            session(['locale' => $locale]);
        }
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->back();
    })->name('lang.switch');

    // Guest Student Routes
    Route::middleware(['guest:web'])->group(function () {
        Route::get('/', [AuthController::class, 'showLoginForm'])->name('index');
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/', [AuthController::class, 'login']);
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
        Route::get('/aktivasi-akun', [RegistrationController::class, 'showActivationForm'])->name('activate-form');
        Route::post('/aktivasi-akun', [RegistrationController::class, 'activate'])->name('activate');
    });

    // Protected Student Portal Routes
    Route::middleware(['auth', 'student'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/dashboard', [\App\Http\Controllers\Student\StudentPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/complete-profile', [\App\Http\Controllers\Student\StudentPortalController::class, 'showCompleteProfileForm'])->name('complete-profile');
        Route::post('/complete-profile', [\App\Http\Controllers\Student\StudentPortalController::class, 'storeCompleteProfile'])->name('complete-profile.store');

        // Invoices
        Route::get('/invoices', [\App\Http\Controllers\Student\StudentPortalController::class, 'invoices'])->name('invoices');
        Route::get('/invoices/{invoice}', [\App\Http\Controllers\Student\StudentPortalController::class, 'invoiceDetail'])->name('invoices.show');
        Route::post('/invoices/{invoice}/payment-proof', [\App\Http\Controllers\Student\StudentPortalController::class, 'uploadPaymentProof'])->name('invoices.upload-payment-proof');
        Route::get('/invoices/{invoice}/pdf', [\App\Http\Controllers\Student\StudentPortalController::class, 'downloadInvoicePdf'])->name('invoices.pdf');
        Route::get('/invoices/{invoice}/kwitansi', [\App\Http\Controllers\Student\StudentPortalController::class, 'downloadKwitansiPdf'])->name('invoices.kwitansi');

        // Reports (Rapor Siswa)
        Route::get('/reports', [\App\Http\Controllers\Student\StudentPortalController::class, 'reports'])->name('reports');
        Route::get('/reports/{report}', [\App\Http\Controllers\Student\StudentPortalController::class, 'reportDetail'])->name('reports.show');
        Route::get('/reports/{report}/pdf', [\App\Http\Controllers\Student\StudentPortalController::class, 'downloadReportPdf'])->name('reports.pdf');

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/logout-device', [ProfileController::class, 'logoutDevice'])->name('profile.logout-device');

        // Chat
        Route::get('/chat/contacts', [ChatController::class, 'getContacts'])->name('chat.contacts');
        Route::get('/chat/messages/{userId}', [ChatController::class, 'getMessages'])->name('chat.messages');
        Route::post('/chat/messages', [ChatController::class, 'store'])->name('chat.store');
        Route::delete('/chat/messages/{id}', [ChatController::class, 'destroy'])->name('chat.destroy');
        Route::post('/chat/mark-read', [ChatController::class, 'markAsRead'])->name('chat.mark-read');
        Route::post('/chat/typing', [ChatController::class, 'typing'])->name('chat.typing');

        // Student Assignments & Exams
        Route::get('/activities', [\App\Http\Controllers\Student\PortalActivityController::class, 'index'])->name('activities.index');
        Route::get('/activities/assignment/{assignment}', [\App\Http\Controllers\Student\PortalActivityController::class, 'showAssignment'])->name('activities.assignment.show');
        Route::post('/activities/assignment/{assignment}/submit', [\App\Http\Controllers\Student\PortalActivityController::class, 'submitAssignment'])->name('activities.assignment.submit');
        Route::get('/activities/exam/{exam}', [\App\Http\Controllers\Student\PortalActivityController::class, 'showExam'])->name('activities.exam.show');
        Route::get('/activities/exam/{exam}/take', [\App\Http\Controllers\Student\PortalActivityController::class, 'takeExam'])->name('activities.exam.take');
        Route::post('/activities/exam/{exam}/submit', [\App\Http\Controllers\Student\PortalActivityController::class, 'submitExam'])->name('activities.exam.submit');
        Route::get('/activities/exam/{exam}/result', [\App\Http\Controllers\Student\PortalActivityController::class, 'examResult'])->name('activities.exam.result');

        // Student Video Conference
        Route::get('/video-conferences', [\App\Http\Controllers\Student\VideoConferenceController::class, 'index'])->name('video-conferences.index');
        Route::get('/video-conference/{room}', [\App\Http\Controllers\Student\VideoConferenceController::class, 'join'])->name('video-conference.join');

        // Student Attendance
        Route::get('/absensi', [\App\Http\Controllers\AttendanceController::class, 'studentIndex'])->name('attendances.index');

        // WebRTC API fallback
        Route::get('/api/room/ice-servers', function () {
            return response()->json(['iceServers' => []]);
        })->name('api.room.ice-servers');
        Route::post('/api/room/log-error', function () {
            return response()->json(['status' => 'logged']);
        })->name('api.room.log-error');

        // Ruang Belajar (Lessons & Discussions)
        Route::get('/ruang-belajar', [\App\Http\Controllers\Student\LessonController::class, 'index'])->name('lessons.index');
        Route::get('/ruang-belajar/{lesson}', [\App\Http\Controllers\Student\LessonController::class, 'show'])->name('lessons.show');
        Route::post('/ruang-belajar/{lesson}/progress', [\App\Http\Controllers\Student\LessonController::class, 'toggleProgress'])->name('lessons.progress');
        Route::get('/ruang-belajar/{lesson}/comments', [\App\Http\Controllers\Student\LessonController::class, 'getComments'])->name('lessons.comments.index');
        Route::post('/ruang-belajar/{lesson}/comments', [\App\Http\Controllers\Student\LessonController::class, 'storeComment'])->name('lessons.comments.store');
        Route::delete('/ruang-belajar/comments/{comment}', [\App\Http\Controllers\Student\LessonController::class, 'destroyComment'])->name('lessons.comments.destroy');
        Route::post('/ruang-belajar/{lesson}/read', [\App\Http\Controllers\Student\LessonController::class, 'markAsRead'])->name('lessons.read');
    });
});


// =========================================================================
// 4. TEACHER PORTAL (/teacher)
// =========================================================================
Route::middleware(['auth', 'staff'])->prefix('teacher')->name('teacher.')->group(function () {

    Route::get('/lang/{locale}', function ($locale) {
        if (in_array($locale, ['en', 'id'])) {
            session(['locale' => $locale]);
        }
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->back();
    })->name('lang.switch');

    // Activities, Assignments & Exams
    Route::get('/activities', [\App\Http\Controllers\Teacher\TeacherActivityController::class, 'index'])->name('activities.index');
    Route::get('/assignments/create', [\App\Http\Controllers\Teacher\TeacherActivityController::class, 'createAssignment'])->name('assignments.create');
    Route::post('/assignments', [\App\Http\Controllers\Teacher\TeacherActivityController::class, 'storeAssignment'])->name('assignments.store');
    Route::get('/assignments/{assignment}/edit', [\App\Http\Controllers\Teacher\TeacherActivityController::class, 'editAssignment'])->name('assignments.edit');
    Route::put('/assignments/{assignment}', [\App\Http\Controllers\Teacher\TeacherActivityController::class, 'updateAssignment'])->name('assignments.update');
    Route::delete('/assignments/{assignment}', [\App\Http\Controllers\Teacher\TeacherActivityController::class, 'destroyAssignment'])->name('assignments.destroy');
    Route::get('/assignments/{assignment}/submissions', [\App\Http\Controllers\Teacher\TeacherActivityController::class, 'listSubmissions'])->name('assignments.submissions');
    Route::post('/submissions/{submission}/grade', [\App\Http\Controllers\Teacher\TeacherActivityController::class, 'gradeSubmission'])->name('submissions.grade');

    Route::get('/exams/create', [\App\Http\Controllers\Teacher\TeacherActivityController::class, 'createExam'])->name('exams.create');
    Route::post('/exams', [\App\Http\Controllers\Teacher\TeacherActivityController::class, 'storeExam'])->name('exams.store');
    Route::get('/exams/{exam}/edit', [\App\Http\Controllers\Teacher\TeacherActivityController::class, 'editExam'])->name('exams.edit');
    Route::put('/exams/{exam}', [\App\Http\Controllers\Teacher\TeacherActivityController::class, 'updateExam'])->name('exams.update');
    Route::delete('/exams/{exam}', [\App\Http\Controllers\Teacher\TeacherActivityController::class, 'destroyExam'])->name('exams.destroy');
    Route::get('/exams/{exam}/attempts', [\App\Http\Controllers\Teacher\TeacherActivityController::class, 'listAttempts'])->name('exams.attempts');
    Route::post('/attempts/{attempt}/grade', [\App\Http\Controllers\Teacher\TeacherActivityController::class, 'gradeAttemptEssay'])->name('attempts.grade');
    Route::post('/exams/draft/save', [\App\Http\Controllers\Teacher\TeacherActivityController::class, 'saveExamDraft'])->name('exams.draft.save');
    Route::post('/exams/draft/discard', [\App\Http\Controllers\Teacher\TeacherActivityController::class, 'discardExamDraft'])->name('exams.draft.discard');

    // Video Conferences
    Route::get('/video-conferences', [\App\Http\Controllers\Teacher\VideoConferenceController::class, 'index'])->name('video-conferences.index');
    Route::post('/video-conferences', [\App\Http\Controllers\Teacher\VideoConferenceController::class, 'store'])->name('video-conferences.store');
    Route::delete('/video-conferences/{videoConference}', [\App\Http\Controllers\Teacher\VideoConferenceController::class, 'destroy'])->name('video-conferences.destroy');
    Route::get('/video-conference/{room}', [\App\Http\Controllers\Teacher\VideoConferenceController::class, 'join'])->name('video-conference.join');
    Route::post('/video-conference/{room}/leave', [\App\Http\Controllers\Teacher\VideoConferenceController::class, 'leaveRoom'])->name('video-conference.leave');
    Route::post('/video-conference/{room}/end', [\App\Http\Controllers\Teacher\VideoConferenceController::class, 'endMeeting'])->name('video-conference.end');
    Route::post('/video-conference/start-server', [\App\Http\Controllers\Teacher\VideoConferenceController::class, 'startServer'])->name('video-conference.start-server');

    // Ruang Belajar (Lessons Management & Preview)
    Route::get('/ruang-belajar', [\App\Http\Controllers\Teacher\LessonManagementController::class, 'index'])->name('lessons.index');
    Route::get('/ruang-belajar/preview/{lesson}', [\App\Http\Controllers\Student\LessonController::class, 'show'])->name('lessons.show');
    Route::post('/ruang-belajar/preview/{lesson}/progress', [\App\Http\Controllers\Student\LessonController::class, 'toggleProgress'])->name('lessons.progress');
    Route::get('/ruang-belajar/preview/{lesson}/comments', [\App\Http\Controllers\Student\LessonController::class, 'getComments'])->name('lessons.comments.index');
    Route::post('/ruang-belajar/preview/{lesson}/comments', [\App\Http\Controllers\Student\LessonController::class, 'storeComment'])->name('lessons.comments.store');
    Route::delete('/ruang-belajar/preview/comments/{comment}', [\App\Http\Controllers\Student\LessonController::class, 'destroyComment'])->name('lessons.comments.destroy');
    Route::post('/ruang-belajar/preview/{lesson}/read', [\App\Http\Controllers\Student\LessonController::class, 'markAsRead'])->name('lessons.read');

    // Courses CRUD
    Route::get('/ruang-belajar/courses/create', [\App\Http\Controllers\Teacher\LessonManagementController::class, 'createCourse'])->name('courses.create');
    Route::post('/ruang-belajar/courses', [\App\Http\Controllers\Teacher\LessonManagementController::class, 'storeCourse'])->name('courses.store');
    Route::get('/ruang-belajar/courses/{course}/edit', [\App\Http\Controllers\Teacher\LessonManagementController::class, 'editCourse'])->name('courses.edit');
    Route::put('/ruang-belajar/courses/{course}', [\App\Http\Controllers\Teacher\LessonManagementController::class, 'updateCourse'])->name('courses.update');
    Route::delete('/ruang-belajar/courses/{course}', [\App\Http\Controllers\Teacher\LessonManagementController::class, 'destroyCourse'])->name('courses.destroy');

    // Lessons CRUD
    Route::get('/ruang-belajar/courses/{course}/lessons/create', [\App\Http\Controllers\Teacher\LessonManagementController::class, 'createLesson'])->name('lessons.create');
    Route::post('/ruang-belajar/courses/{course}/lessons', [\App\Http\Controllers\Teacher\LessonManagementController::class, 'storeLesson'])->name('lessons.store');
    Route::get('/ruang-belajar/courses/{course}/lessons/{lesson}/edit', [\App\Http\Controllers\Teacher\LessonManagementController::class, 'editLesson'])->name('lessons.edit');
    Route::put('/ruang-belajar/courses/{course}/lessons/{lesson}', [\App\Http\Controllers\Teacher\LessonManagementController::class, 'updateLesson'])->name('lessons.update');
    Route::delete('/ruang-belajar/courses/{course}/lessons/{lesson}', [\App\Http\Controllers\Teacher\LessonManagementController::class, 'destroyLesson'])->name('lessons.destroy');
});


// =========================================================================
// 5. ADMIN & MANAGEMENT ROUTES (Dashboard, Invoices, Siswa, Rapor, dll.)
// =========================================================================
Route::middleware(['auth', 'staff'])->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export');
    Route::post('/dashboard/wipe-all-data', [DashboardController::class, 'wipeAllData'])->name('dashboard.wipe-all-data');

    // Chat Staf & Guru
    Route::get('/chat/contacts', [ChatController::class, 'getContacts'])->name('chat.contacts');
    Route::get('/chat/messages/{userId}', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/messages', [ChatController::class, 'store'])->name('chat.store');
    Route::delete('/chat/messages/{id}', [ChatController::class, 'destroy'])->name('chat.destroy');
    Route::post('/chat/mark-read', [ChatController::class, 'markAsRead'])->name('chat.mark-read');
    Route::post('/chat/typing', [ChatController::class, 'typing'])->name('chat.typing');

    // Profile Staf & Guru
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/logout-device', [ProfileController::class, 'logoutDevice'])->name('profile.logout-device');

    // Tagihan & Kwitansi (Invoices)
    Route::get('/students/search', [InvoiceController::class, 'searchStudents'])->name('students.search');
    Route::get('/invoices/export', [InvoiceController::class, 'export'])->name('invoices.export');
    Route::get('/invoices/automation', [InvoiceController::class, 'automationIndex'])->name('invoices.automation');
    Route::post('/invoices/automation/run', [InvoiceController::class, 'automationRun'])->name('invoices.automation.run');
    Route::post('/invoices/automation/mapping', [InvoiceController::class, 'storeMapping'])->name('invoices.automation.store-mapping');
    Route::delete('/invoices/automation/mapping/{studentService}', [InvoiceController::class, 'deleteMapping'])->name('invoices.automation.delete-mapping');
    Route::post('/invoices/automation/settings', [InvoiceController::class, 'saveSettings'])->name('invoices.automation.save-settings');
    Route::resource('invoices', InvoiceController::class);
    Route::patch('/invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.update-status');
    Route::post('/invoices/{invoice}/pay-partial', [InvoiceController::class, 'payPartial'])->name('invoices.pay-partial');
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    Route::get('/invoices/{invoice}/preview', [InvoiceController::class, 'previewPdf'])->name('invoices.preview');
    Route::get('/invoices/{invoice}/kwitansi', [InvoiceController::class, 'downloadKwitansi'])->name('invoices.kwitansi');
    Route::get('/invoices/{invoice}/kwitansi-preview', [InvoiceController::class, 'previewKwitansi'])->name('invoices.kwitansi-preview');

    Route::get('/kwitansi-list', [InvoiceController::class, 'kwitansiIndex'])->name('invoices.kwitansi-index');
    Route::get('/kwitansi-create', [InvoiceController::class, 'kwitansiCreate'])->name('invoices.kwitansi-create');
    Route::post('/kwitansi-store', [InvoiceController::class, 'kwitansiStore'])->name('invoices.kwitansi-store');

    // Master Siswa (Students)
    Route::resource('students', \App\Http\Controllers\Admin\StudentManagementController::class);

    // Absensi Kehadiran Siswa
    Route::get('/absensi', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('attendances.index');
    Route::post('/absensi', [\App\Http\Controllers\AttendanceController::class, 'store'])->name('attendances.store');

    // Master Mata Pelajaran (Subjects)
    Route::resource('subjects', SubjectController::class);

    // Master Layanan Sekolah (Services)
    Route::resource('services', ServiceController::class);

    // Profil Sekolah (School Profile)
    Route::get('/school-profile', [SchoolProfileController::class, 'edit'])->name('school-profile.edit');
    Route::put('/school-profile', [SchoolProfileController::class, 'update'])->name('school-profile.update');
    Route::post('/school-profile/repair-storage', [SchoolProfileController::class, 'repairStorage'])->name('school-profile.repair-storage');

    // Pengeluaran / Expense Management
    Route::post('expense-categories/parse', [ExpenseCategoryController::class, 'parseText'])->name('expense-categories.parse');
    Route::resource('expense-categories', ExpenseCategoryController::class);
    Route::post('expense-categories/{expense_category}/expenses', [ExpenseController::class, 'store'])->name('expense-categories.expenses.store');
    Route::delete('expense-categories/{expense_category}/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expense-categories.expenses.destroy');

    // Master Template Proyek Bulanan
    Route::resource('project-templates', \App\Http\Controllers\Admin\ProjectTemplateController::class);

    // Surat Keterangan (SK CIBI & SK ABK)
    Route::prefix('surat-keterangan')->name('surat-keterangan.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SuratKeteranganController::class, 'index'])->name('index');
        Route::get('/settings', [\App\Http\Controllers\Admin\SuratKeteranganController::class, 'settings'])->name('settings');
        Route::put('/settings', [\App\Http\Controllers\Admin\SuratKeteranganController::class, 'updateSettings'])->name('settings.update');
        Route::get('/create', [\App\Http\Controllers\Admin\SuratKeteranganController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\SuratKeteranganController::class, 'store'])->name('store');
        Route::get('/{suratKeterangan}', [\App\Http\Controllers\Admin\SuratKeteranganController::class, 'show'])->name('show');
        Route::get('/{suratKeterangan}/edit', [\App\Http\Controllers\Admin\SuratKeteranganController::class, 'edit'])->name('edit');
        Route::put('/{suratKeterangan}', [\App\Http\Controllers\Admin\SuratKeteranganController::class, 'update'])->name('update');
        Route::delete('/{suratKeterangan}', [\App\Http\Controllers\Admin\SuratKeteranganController::class, 'destroy'])->name('destroy');
        Route::get('/{suratKeterangan}/pdf', [\App\Http\Controllers\Admin\SuratKeteranganController::class, 'pdf'])->name('pdf');
    });

    // Sub-Admin Routes (Rapor, Akademik, Registrasi Siswa, CMS Compro)
    Route::prefix('admin')->name('admin.')->group(function () {
        // Setting Rapor & PBL
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/setting-rapor', [\App\Http\Controllers\Admin\RaportSettingController::class, 'index'])->name('settings.index');
            Route::post('/setting-rapor', [\App\Http\Controllers\Admin\RaportSettingController::class, 'store'])->name('settings.store');

            // Bulk PBL
            Route::get('/bulk-pbl', [\App\Http\Controllers\Admin\BulkPblController::class, 'index'])->name('bulk-pbl.index');
            Route::get('/bulk-pbl/create', [\App\Http\Controllers\Admin\BulkPblController::class, 'create'])->name('bulk-pbl.create');
            Route::post('/bulk-pbl', [\App\Http\Controllers\Admin\BulkPblController::class, 'store'])->name('bulk-pbl.store');
            Route::get('/bulk-pbl/{project}', [\App\Http\Controllers\Admin\BulkPblController::class, 'show'])->name('bulk-pbl.show');
            Route::post('/bulk-pbl/{project}/finalize', [\App\Http\Controllers\Admin\BulkPblController::class, 'finalize'])->name('bulk-pbl.finalize');
            Route::delete('/bulk-pbl/{project}', [\App\Http\Controllers\Admin\BulkPblController::class, 'destroy'])->name('bulk-pbl.destroy');

            // Manajemen Rapor
            Route::get('/', [\App\Http\Controllers\Admin\ReportManagementController::class, 'index'])->name('index');
            Route::get('/probing-guide', [\App\Http\Controllers\Admin\ReportManagementController::class, 'probingGuide'])->name('probing-guide');
            Route::get('/student/{siswa}', [\App\Http\Controllers\Admin\ReportManagementController::class, 'studentReports'])->name('student-list');
            Route::get('/search-students', [\App\Http\Controllers\Admin\ReportManagementController::class, 'searchStudents'])->name('search-students');
            Route::get('/create', [\App\Http\Controllers\Admin\ReportManagementController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\ReportManagementController::class, 'store'])->name('store');
            Route::post('/import-grades', [\App\Http\Controllers\Admin\ReportManagementController::class, 'importGrades'])->name('import-grades');
            Route::post('/import-pbl', [\App\Http\Controllers\Admin\ReportManagementController::class, 'importPbl'])->name('import-pbl');
            Route::get('/{report}', [\App\Http\Controllers\Admin\ReportManagementController::class, 'show'])->name('show');
            Route::get('/{report}/edit', [\App\Http\Controllers\Admin\ReportManagementController::class, 'edit'])->name('edit');
            Route::put('/{report}', [\App\Http\Controllers\Admin\ReportManagementController::class, 'update'])->name('update');
            Route::delete('/{report}', [\App\Http\Controllers\Admin\ReportManagementController::class, 'destroy'])->name('destroy');
            Route::get('/{report}/pdf', [\App\Http\Controllers\Admin\ReportManagementController::class, 'downloadPdf'])->name('pdf');
            Route::get('/{report}/preview', [\App\Http\Controllers\Admin\ReportManagementController::class, 'previewPdf'])->name('preview');
            Route::post('/{report}/finalize', [\App\Http\Controllers\Admin\ReportManagementController::class, 'finalize'])->name('finalize');
            Route::post('/finalize-all', [\App\Http\Controllers\Admin\ReportManagementController::class, 'finalizeAll'])->name('finalize-all');
        });

        // Semester Projects (Proyek Bulanan per Semester)
        Route::prefix('semester-projects')->name('semester-projects.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\SemesterProjectController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\SemesterProjectController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\SemesterProjectController::class, 'store'])->name('store');
            Route::get('/{semesterProject}/edit', [\App\Http\Controllers\Admin\SemesterProjectController::class, 'edit'])->name('edit');
            Route::put('/{semesterProject}', [\App\Http\Controllers\Admin\SemesterProjectController::class, 'update'])->name('update');
            Route::delete('/{semesterProject}', [\App\Http\Controllers\Admin\SemesterProjectController::class, 'destroy'])->name('destroy');
            Route::get('/api/by-academic-year', [\App\Http\Controllers\Admin\SemesterProjectController::class, 'byAcademicYear'])->name('api.by-academic-year');
        });

        // Report Categories
        Route::prefix('report-categories')->name('report-categories.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ReportCategoryController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\ReportCategoryController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\ReportCategoryController::class, 'store'])->name('store');
            Route::get('/{reportCategory}/edit', [\App\Http\Controllers\Admin\ReportCategoryController::class, 'edit'])->name('edit');
            Route::put('/{reportCategory}', [\App\Http\Controllers\Admin\ReportCategoryController::class, 'update'])->name('update');
            Route::delete('/{reportCategory}', [\App\Http\Controllers\Admin\ReportCategoryController::class, 'destroy'])->name('destroy');
        });

        // Academic Settings (KKM, Mutu, Saran)
        Route::prefix('academic-settings')->name('academic-settings.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AcademicSettingController::class, 'index'])->name('index');
            Route::post('/kkm', [\App\Http\Controllers\Admin\AcademicSettingController::class, 'updateKkm'])->name('kkm.update');
            Route::post('/grade-ranges', [\App\Http\Controllers\Admin\AcademicSettingController::class, 'updateGradeRanges'])->name('grade-ranges.update');
            Route::post('/mutu-labels', [\App\Http\Controllers\Admin\AcademicSettingController::class, 'updateMutuLabels'])->name('mutu-labels.update');
            Route::resource('suggestions', \App\Http\Controllers\Admin\TeacherNoteSuggestionController::class)->except(['create', 'show', 'edit']);
        });

        // Registrasi Siswa Admin
        Route::prefix('registrations')->name('registrations.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\RegistrationAdminController::class, 'index'])->name('index');
            Route::get('/{registration}', [\App\Http\Controllers\Admin\RegistrationAdminController::class, 'show'])->name('show');
            Route::patch('/{registration}/status', [\App\Http\Controllers\Admin\RegistrationAdminController::class, 'updateStatus'])->name('update-status');
            Route::get('/{registration}/pdf', [\App\Http\Controllers\Admin\RegistrationAdminController::class, 'downloadPdf'])->name('pdf');
        });

        // Company Profile CMS
        Route::prefix('compro-cms')->name('compro-cms.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ComproAdminController::class, 'index'])->name('index');
            Route::post('/settings', [\App\Http\Controllers\Admin\ComproAdminController::class, 'updateSettings'])->name('settings.update');

            // Pillars
            Route::post('/pillars', [\App\Http\Controllers\Admin\ComproAdminController::class, 'pillarStore'])->name('pillars.store');
            Route::put('/pillars/{pillar}', [\App\Http\Controllers\Admin\ComproAdminController::class, 'pillarUpdate'])->name('pillars.update');
            Route::delete('/pillars/{pillar}', [\App\Http\Controllers\Admin\ComproAdminController::class, 'pillarDestroy'])->name('pillars.destroy');

            // Programs
            Route::post('/programs', [\App\Http\Controllers\Admin\ComproAdminController::class, 'programStore'])->name('programs.store');
            Route::put('/programs/{program}', [\App\Http\Controllers\Admin\ComproAdminController::class, 'programUpdate'])->name('programs.update');
            Route::delete('/programs/{program}', [\App\Http\Controllers\Admin\ComproAdminController::class, 'programDestroy'])->name('programs.destroy');

            // Curriculum Loads
            Route::put('/curriculum-loads/{load}', [\App\Http\Controllers\Admin\ComproAdminController::class, 'curriculumLoadUpdate'])->name('curriculum-loads.update');

            // Curriculum Activities
            Route::post('/activities', [\App\Http\Controllers\Admin\ComproAdminController::class, 'activityStore'])->name('activities.store');
            Route::put('/activities/{activity}', [\App\Http\Controllers\Admin\ComproAdminController::class, 'activityUpdate'])->name('activities.update');
            Route::delete('/activities/{activity}', [\App\Http\Controllers\Admin\ComproAdminController::class, 'activityDestroy'])->name('activities.destroy');

            // Investment
            Route::post('/investments', [\App\Http\Controllers\Admin\ComproAdminController::class, 'investmentStore'])->name('investments.store');
            Route::put('/investments/{item}', [\App\Http\Controllers\Admin\ComproAdminController::class, 'investmentUpdate'])->name('investments.update');
            Route::delete('/investments/{item}', [\App\Http\Controllers\Admin\ComproAdminController::class, 'investmentDestroy'])->name('investments.destroy');

            // Promos & Discounts
            Route::post('/promos', [\App\Http\Controllers\Admin\ComproAdminController::class, 'promoStore'])->name('promos.store');
            Route::put('/promos/{promo}', [\App\Http\Controllers\Admin\ComproAdminController::class, 'promoUpdate'])->name('promos.update');
            Route::delete('/promos/{promo}', [\App\Http\Controllers\Admin\ComproAdminController::class, 'promoDestroy'])->name('promos.destroy');

            // Gallery Albums
            Route::post('/albums', [\App\Http\Controllers\Admin\ComproAdminController::class, 'albumStore'])->name('albums.store');
            Route::put('/albums/{album}', [\App\Http\Controllers\Admin\ComproAdminController::class, 'albumUpdate'])->name('albums.update');
            Route::delete('/albums/{album}', [\App\Http\Controllers\Admin\ComproAdminController::class, 'albumDestroy'])->name('albums.destroy');

            // Gallery Photos
            Route::post('/photos', [\App\Http\Controllers\Admin\ComproAdminController::class, 'photoStore'])->name('photos.store');
            Route::delete('/photos/{photo}', [\App\Http\Controllers\Admin\ComproAdminController::class, 'photoDestroy'])->name('photos.destroy');

            // Highlights
            Route::post('/highlights', [\App\Http\Controllers\Admin\ComproAdminController::class, 'highlightStore'])->name('highlights.store');
            Route::put('/highlights/{highlight}', [\App\Http\Controllers\Admin\ComproAdminController::class, 'highlightUpdate'])->name('highlights.update');
            Route::delete('/highlights/{highlight}', [\App\Http\Controllers\Admin\ComproAdminController::class, 'highlightDestroy'])->name('highlights.destroy');
        });
    });
});
