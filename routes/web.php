<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Teacher\TeacherMarkController;
use App\Http\Controllers\ClassTeacher\ClassTeacherController;
use App\Http\Controllers\Report\ReportController;

// Redirect home route
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Debug / Test Routes
Route::get('/test-db', function () {
    try {
        \DB::connection()->getPdo();
        return "Database Connection OK! Database name: " . \DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return "DB CONNECTION ERROR: " . $e->getMessage();
    }
});

Route::get('/test-session', function () {
    try {
        session()->put('test_key', 'test_value');
        return "Session Write OK! test_key=" . session()->get('test_key');
    } catch (\Exception $e) {
        return "SESSION ERROR: " . $e->getMessage();
    }
});

Route::get('/test-login', function () {
    try {
        return view('auth.login');
    } catch (\Exception $e) {
        return "VIEW ERROR: " . $e->getMessage() . "\nFILE: " . $e->getFile() . "\nLINE: " . $e->getLine() . "\nTRACE:\n" . $e->getTraceAsString();
    }
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/classes', [AdminController::class, 'classes'])->name('classes.index');
        Route::post('/classes', [AdminController::class, 'storeClass'])->name('classes.store');

        Route::get('/subjects', [AdminController::class, 'subjects'])->name('subjects.index');
        Route::post('/subjects', [AdminController::class, 'storeSubject'])->name('subjects.store');

        Route::get('/students', [AdminController::class, 'students'])->name('students.index');
        Route::post('/students', [AdminController::class, 'storeStudent'])->name('students.store');
        Route::get('/students/{id}/edit', [AdminController::class, 'editStudent'])->name('students.edit');
        Route::post('/students/{id}/update', [AdminController::class, 'updateStudent'])->name('students.update');
        Route::post('/students/import', [AdminController::class, 'importStudents'])->name('students.import');
        Route::delete('/students/{id}', [AdminController::class, 'destroyStudent'])->name('students.destroy');
        Route::post('/students/bulk-delete', [AdminController::class, 'bulkDestroyStudents'])->name('students.bulk_destroy');

        Route::get('/allocations', [AdminController::class, 'allocations'])->name('allocations.index');
        Route::post('/allocations', [AdminController::class, 'storeAllocation'])->name('allocations.store');

        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::post('/users/import', [AdminController::class, 'importUsers'])->name('users.import');
        Route::post('/users/{id}/toggle-active', [AdminController::class, 'toggleUserActive'])->name('users.toggle');
        Route::post('/users/{id}/update-role', [AdminController::class, 'updateUserRole'])->name('users.update_role');
        Route::post('/users/{id}/allocations', [AdminController::class, 'updateUserAllocations'])->name('users.allocations');
        Route::post('/users/allocations', [AdminController::class, 'storeAllocationsRequest'])->name('users.store_allocations');
        Route::post('/users/{id}/reset-password', [AdminController::class, 'resetUserPassword'])->name('users.reset_password');
        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::post('/users/bulk-delete', [AdminController::class, 'bulkDestroyUsers'])->name('users.bulk_destroy');

        // Optional Subjects Registrations
        Route::get('/optional-subjects', [AdminController::class, 'optionalSubjects'])->name('optional_subjects.index');
        Route::post('/optional-subjects', [AdminController::class, 'storeOptionalSubjects'])->name('optional_subjects.store');

        Route::get('/settings', [AdminController::class, 'settings'])->name('settings.index');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

        // Academic Year & Term Management
        Route::get('/years', [AdminController::class, 'years'])->name('years.index');
        Route::post('/years', [AdminController::class, 'storeYear'])->name('years.store');
        Route::delete('/years/{id}', [AdminController::class, 'destroyYear'])->name('years.destroy');

        Route::post('/terms', [AdminController::class, 'storeTerm'])->name('terms.store');
        Route::post('/terms/{id}/activate', [AdminController::class, 'activateTerm'])->name('terms.activate');
        Route::delete('/terms/{id}', [AdminController::class, 'destroyTerm'])->name('terms.destroy');
    });

    // Teacher Routes
    Route::middleware('role:teacher,class_teacher,admin')->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/marks', [TeacherMarkController::class, 'index'])->name('marks.index');
        Route::post('/marks', [TeacherMarkController::class, 'store'])->name('marks.store');
    });

    // Class Teacher Routes
    Route::middleware('role:class_teacher,admin')->prefix('classteacher')->name('classteacher.')->group(function () {
        Route::get('/comments', [ClassTeacherController::class, 'index'])->name('comments.index');
        Route::post('/comments', [ClassTeacherController::class, 'store'])->name('comments.store');
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/{id}', [ReportController::class, 'show'])->name('show');
        Route::get('/stream/{streamId}', [ReportController::class, 'printStream'])->name('print_stream');
    });

    Route::post('/students/{id}/photo', [ReportController::class, 'uploadStudentPhoto'])->name('students.photo');

    // Profile & Password Change
    Route::get('/profile/password', [AuthController::class, 'showChangePassword'])->name('profile.password');
    Route::post('/profile/password', [AuthController::class, 'changePassword']);
});
