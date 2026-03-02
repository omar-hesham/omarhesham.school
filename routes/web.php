<?php

use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\ParentConsentController;
use App\Http\Controllers\Auth\AccountController;

// Public
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PolicyController;

// Student
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\ProgressController;
use App\Http\Controllers\Student\EnrollmentController;

// Teacher
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Teacher\StudentController as TeacherStudentController;
use App\Http\Controllers\Teacher\ContentUploadController;

// Admin
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ContentModerationController;
use App\Http\Controllers\Admin\ProgramAdminController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ReportController;

// ══════════════════════════════════════════════
//  PUBLIC / GUEST ROUTES
// ══════════════════════════════════════════════

Route::get('/', [LandingController::class, 'index'])->name('home');

Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
Route::get('/programs/{program:slug}', [ProgramController::class, 'show'])->name('programs.show');
Route::get('/programs/{program:slug}/lessons/{lesson}', [LessonController::class, 'show'])
    ->name('lessons.show')
    ->middleware('auth');

Route::get('/pricing', [SubscriptionController::class, 'pricingPage'])->name('pricing');
Route::get('/donate', [DonationController::class, 'index'])->name('donations.index');

Route::prefix('policies')->name('policies.')->group(function () {
    Route::get('/privacy',      [PolicyController::class, 'privacy'])->name('privacy');
    Route::get('/terms',        [PolicyController::class, 'terms'])->name('terms');
    Route::get('/child-safety', [PolicyController::class, 'childSafety'])->name('child-safety');
    Route::get('/cookies',      [PolicyController::class, 'cookies'])->name('cookies');
});

// ══════════════════════════════════════════════
//  AUTHENTICATION ROUTES
// ══════════════════════════════════════════════

Route::middleware('guest')->group(function () {
    Route::get('/register',               [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register',              [RegisterController::class, 'register'])->middleware('throttle:5,1');

    Route::get('/login',                  [LoginController::class, 'showForm'])->name('login');
    Route::post('/login',                 [LoginController::class, 'login'])->middleware('throttle:10,1');

    Route::get('/forgot-password',        [PasswordResetController::class, 'showLinkForm'])->name('password.request');
    Route::post('/forgot-password',       [PasswordResetController::class, 'sendResetLink'])->name('password.email')->middleware('throttle:3,1');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password',        [PasswordResetController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Parent consent (token-based, no auth)
Route::prefix('consent')->name('consent.')->group(function () {
    Route::get('/pending',          fn () => view('auth.consent-pending'))->middleware('auth')->name('pending');
    Route::get('/{token}/approve',  [ParentConsentController::class, 'approve'])->name('approve');
    Route::get('/{token}/deny',     [ParentConsentController::class, 'deny'])->name('deny');
    Route::get('/{token}/info',     [ParentConsentController::class, 'info'])->name('info');
});

// Account self-service
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/settings',    [AccountController::class, 'settings'])->name('settings');
    Route::put('/settings',    [AccountController::class, 'updateSettings'])->name('settings.update');
    Route::put('/password',    [AccountController::class, 'changePassword'])->name('password.change');
    Route::get('/export',      [AccountController::class, 'exportData'])->name('export');
    Route::delete('/delete',   [AccountController::class, 'deleteAccount'])->name('delete');
});

// ══════════════════════════════════════════════
//  STUDENT ROUTES
// ══════════════════════════════════════════════

Route::middleware(['auth', 'role:student', 'minor.protection', 'verified.ban'])
    ->prefix('student')->name('student.')->group(function () {

    Route::get('/dashboard',                 [StudentDashboard::class, 'index'])->name('dashboard');
    Route::get('/progress',                  [ProgressController::class, 'index'])->name('progress.index');
    Route::post('/progress',                 [ProgressController::class, 'store'])->name('progress.store');
    Route::get('/progress/report',           [ProgressController::class, 'weeklyReport'])->name('progress.report');
    Route::delete('/progress/{log}',         [ProgressController::class, 'destroy'])->name('progress.destroy');
    Route::post('/enroll/{program}',         [EnrollmentController::class, 'enroll'])->name('enroll');
    Route::delete('/unenroll/{program}',     [EnrollmentController::class, 'unenroll'])->name('unenroll');
    Route::get('/content',                   [ContentController::class, 'library'])->name('content.library');
    Route::get('/content/{item}/stream',     [ContentController::class, 'stream'])->name('content.stream');
});

// ══════════════════════════════════════════════
//  TEACHER ROUTES
// ══════════════════════════════════════════════

Route::middleware(['auth', 'role:teacher,center_admin', 'verified.ban'])
    ->prefix('teacher')->name('teacher.')->group(function () {

    Route::get('/dashboard',                         [TeacherDashboard::class, 'index'])->name('dashboard');
    Route::get('/students',                          [TeacherStudentController::class, 'index'])->name('students.index');
    Route::post('/students/{student}/assign',        [TeacherStudentController::class, 'assign'])->name('students.assign');
    Route::delete('/students/{student}/remove',      [TeacherStudentController::class, 'remove'])->name('students.remove');
    Route::get('/students/{student}/progress',       [TeacherStudentController::class, 'viewProgress'])->name('students.progress');
    Route::post('/progress/{log}/approve',           [TeacherStudentController::class, 'approveLog'])->name('progress.approve');
    Route::post('/progress/{log}/reject',            [TeacherStudentController::class, 'rejectLog'])->name('progress.reject');
    Route::get('/content/upload',                    [ContentUploadController::class, 'showForm'])->name('content.upload.form');
    Route::post('/content/upload',                   [ContentUploadController::class, 'upload'])->name('content.upload');
    Route::post('/content/youtube',                  [ContentUploadController::class, 'addYoutube'])->name('content.youtube');
    Route::delete('/content/{item}',                 [ContentUploadController::class, 'destroy'])->name('content.destroy');
});

// ══════════════════════════════════════════════
//  ADMIN ROUTES
// ══════════════════════════════════════════════

Route::middleware(['auth', 'role:admin,center_admin', 'verified.ban'])
    ->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard',                       [ReportController::class, 'adminDashboard'])->name('dashboard');
    Route::get('/users',                           [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}',                    [UserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/ban',               [UserController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/unban',             [UserController::class, 'unban'])->name('users.unban');
    Route::put('/users/{user}/role',               [UserController::class, 'changeRole'])->name('users.role');
    Route::delete('/users/{user}',                 [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/moderation',                      [ContentModerationController::class, 'index'])->name('moderation.index');
    Route::post('/moderation/{item}/approve',      [ContentModerationController::class, 'approve'])->name('moderation.approve');
    Route::post('/moderation/{item}/reject',       [ContentModerationController::class, 'reject'])->name('moderation.reject');
    Route::get('/moderation/{item}/preview',       [ContentModerationController::class, 'preview'])->name('moderation.preview');

    Route::get('/programs',                        [ProgramAdminController::class, 'index'])->name('programs.index');
    Route::get('/programs/create',                 [ProgramAdminController::class, 'create'])->name('programs.create');
    Route::post('/programs',                       [ProgramAdminController::class, 'store'])->name('programs.store');
    Route::get('/programs/{program}/edit',         [ProgramAdminController::class, 'edit'])->name('programs.edit');
    Route::put('/programs/{program}',              [ProgramAdminController::class, 'update'])->name('programs.update');
    Route::delete('/programs/{program}',           [ProgramAdminController::class, 'destroy'])->name('programs.destroy');
    Route::post('/programs/{program}/publish',     [ProgramAdminController::class, 'publish'])->name('programs.publish');

    Route::get('/settings',                        [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings',                        [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/reports/audit',                   [ReportController::class, 'auditLog'])->name('reports.audit');
    Route::get('/reports/donations',               [ReportController::class, 'donations'])->name('reports.donations');
    Route::get('/reports/consents',                [ReportController::class, 'consents'])->name('reports.consents');
});

// ══════════════════════════════════════════════
//  PAYMENT ROUTES
// ══════════════════════════════════════════════

Route::post('/stripe/webhook', [SubscriptionController::class, 'webhook'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('stripe.webhook');

Route::middleware(['auth', 'verified.ban'])->group(function () {
    Route::post('/donate/charge',      [DonationController::class, 'charge'])->name('donations.charge');
    Route::get('/donate/success',      [DonationController::class, 'success'])->name('donations.success');
    Route::get('/donate/cancel',       [DonationController::class, 'cancel'])->name('donations.cancel');
    Route::post('/subscribe',          [SubscriptionController::class, 'subscribe'])->name('subscribe');
    Route::get('/subscribe/success',   [SubscriptionController::class, 'success'])->name('subscribe.success');
    Route::post('/subscribe/cancel',   [SubscriptionController::class, 'cancelSubscription'])->name('subscribe.cancel');
    Route::get('/billing/portal',      [SubscriptionController::class, 'billingPortal'])->name('billing.portal');
});
