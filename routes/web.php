<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\WorkflowController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminReportsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\AttachmentController;

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

Route::get('/', function () {
    return redirect()->route('login');
});

// Simple login route
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Authentication test route
Route::get('/auth-test', function () {
    return view('auth-test');
});

// Login test route
Route::get('/test-login', function () {
    return view('test-login');
});

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Admin Dashboard Routes (require admin authentication)
    Route::middleware(['admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        Route::get('profile', [DashboardController::class, 'adminProfile'])->name('profile');
        Route::patch('profile', [DashboardController::class, 'updateAdminProfile'])->name('profile.update');
        Route::put('password', [DashboardController::class, 'updateAdminPassword'])->name('password.update');
    });
});

// Special admin access route
Route::get('/admin', function () {
    if (auth()->check() && auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('admin.login')->with('message', 'Please login with admin credentials to access the admin panel.');
})->name('admin');

// Dashboard Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Student Routes
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Student\StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [App\Http\Controllers\Student\StudentController::class, 'profile'])->name('profile');
        Route::patch('/profile', [App\Http\Controllers\Student\StudentController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [App\Http\Controllers\Student\StudentController::class, 'updatePassword'])->name('password.update');
        Route::get('/activities', [App\Http\Controllers\Student\StudentController::class, 'activities'])->name('activities');
        Route::get('/activities/create', [App\Http\Controllers\Student\StudentController::class, 'createActivity'])->name('create-activity');
        Route::post('/activities', [App\Http\Controllers\Student\StudentController::class, 'storeActivity'])->name('store-activity');
        Route::get('/activities/{activity}', [App\Http\Controllers\Student\StudentController::class, 'showActivity'])->name('show-activity');
        Route::get('/activities/{activity}/edit', [App\Http\Controllers\Student\StudentController::class, 'editActivity'])->name('edit-activity');
        Route::put('/activities/{activity}', [App\Http\Controllers\Student\StudentController::class, 'updateActivity'])->name('update-activity');
        Route::delete('/activities/{activity}', [App\Http\Controllers\Student\StudentController::class, 'deleteActivity'])->name('delete-activity');
    });

    // Adviser Routes
    Route::prefix('adviser')->name('adviser.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Adviser\AdviserController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [App\Http\Controllers\Adviser\AdviserController::class, 'profile'])->name('profile');
        Route::patch('/profile', [App\Http\Controllers\Adviser\AdviserController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [App\Http\Controllers\Adviser\AdviserController::class, 'updatePassword'])->name('password.update');
        // Route disabled: Adviser activities listing page
        // Route::get('/activities', [App\Http\Controllers\Adviser\AdviserController::class, 'activities'])->name('activities');
        Route::get('/pending-approvals', [App\Http\Controllers\Adviser\AdviserController::class, 'pendingApprovals'])->name('pending-approvals');
        Route::get('/activities/{activity}', [App\Http\Controllers\Adviser\AdviserController::class, 'showActivity'])->name('show-activity');
        Route::post('/activities/{activity}/approve', [App\Http\Controllers\Adviser\AdviserController::class, 'approveActivity'])->name('approve-activity');
        Route::post('/activities/{activity}/note', [App\Http\Controllers\Adviser\AdviserController::class, 'noteActivity'])->name('note-activity');
    });

    // Dean Routes
    Route::prefix('dean')->name('dean.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Dean\DeanController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [App\Http\Controllers\Dean\DeanController::class, 'profile'])->name('profile');
        Route::patch('/profile', [App\Http\Controllers\Dean\DeanController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [App\Http\Controllers\Dean\DeanController::class, 'updatePassword'])->name('password.update');
        // Route disabled: Dean activities listing page
        // Route::get('/activities', [App\Http\Controllers\Dean\DeanController::class, 'activities'])->name('activities');
        Route::get('/pending-reviews', [App\Http\Controllers\Dean\DeanController::class, 'pendingReviews'])->name('pending-reviews');
        Route::get('/activities/{activity}', [App\Http\Controllers\Dean\DeanController::class, 'showActivity'])->name('show-activity');
        Route::post('/activities/{activity}/review', [App\Http\Controllers\Dean\DeanController::class, 'reviewActivity'])->name('review-activity');
    });

    // PSG Routes
    Route::prefix('psg')->name('psg.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Psg\PsgController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [App\Http\Controllers\Psg\PsgController::class, 'profile'])->name('profile');
        Route::patch('/profile', [App\Http\Controllers\Psg\PsgController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [App\Http\Controllers\Psg\PsgController::class, 'updatePassword'])->name('password.update');
        // Route removed: /psg/activities
        Route::get('/pending-reviews', [App\Http\Controllers\Psg\PsgController::class, 'pendingReviews'])->name('pending-reviews');
        Route::get('/activities/{activity}', [App\Http\Controllers\Psg\PsgController::class, 'showActivity'])->name('show-activity');
        Route::post('/activities/{activity}/review', [App\Http\Controllers\Psg\PsgController::class, 'reviewActivity'])->name('review-activity');
    });

    // Director Routes
    Route::prefix('director')->name('director.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Director\DirectorController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [App\Http\Controllers\Director\DirectorController::class, 'profile'])->name('profile');
        Route::patch('/profile', [App\Http\Controllers\Director\DirectorController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [App\Http\Controllers\Director\DirectorController::class, 'updatePassword'])->name('password.update');
        Route::get('/pending-endorsements', [App\Http\Controllers\Director\DirectorController::class, 'pendingEndorsements'])->name('pending-endorsements');
        Route::get('/activities/{activity}', [App\Http\Controllers\Director\DirectorController::class, 'showActivity'])->name('show-activity');
        Route::post('/activities/{activity}/endorse', [App\Http\Controllers\Director\DirectorController::class, 'endorseActivity'])->name('endorse-activity');
    });

    // VP Routes
    Route::prefix('vp')->name('vp.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Vp\VpController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [App\Http\Controllers\Vp\VpController::class, 'profile'])->name('profile');
        Route::patch('/profile', [App\Http\Controllers\Vp\VpController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [App\Http\Controllers\Vp\VpController::class, 'updatePassword'])->name('password.update');
        // Route disabled: VP activities listing page
        // Route::get('/activities', [App\Http\Controllers\Vp\VpController::class, 'activities'])->name('activities');
        Route::get('/pending-approvals', [App\Http\Controllers\Vp\VpController::class, 'pendingApprovals'])->name('pending-approvals');
        Route::get('/activities/{activity}', [App\Http\Controllers\Vp\VpController::class, 'showActivity'])->name('show-activity');
        Route::post('/activities/{activity}/approve', [App\Http\Controllers\Vp\VpController::class, 'approveActivity'])->name('approve-activity');
    });

    // OSA Routes
    Route::prefix('osa')->name('osa.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Osa\OsaController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [App\Http\Controllers\Osa\OsaController::class, 'profile'])->name('profile');
        Route::patch('/profile', [App\Http\Controllers\Osa\OsaController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [App\Http\Controllers\Osa\OsaController::class, 'updatePassword'])->name('password.update');
        Route::get('/activities', [App\Http\Controllers\Osa\OsaController::class, 'activities'])->name('activities');
        Route::get('/activities/{activity}', [App\Http\Controllers\Osa\OsaController::class, 'showActivity'])->name('show-activity');
    });

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');




});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // File serving routes
    Route::post('/attachments/upload', [App\Http\Controllers\FileController::class, 'uploadAttachment'])
        ->name('attachments.upload');
    Route::delete('/attachments/{filename}', [App\Http\Controllers\FileController::class, 'deleteAttachment'])
        ->name('attachments.delete');

    // Attachment routes for viewing and downloading files
    Route::get('/attachments/view/{filename}', [AttachmentController::class, 'view'])->name('attachments.view');
    Route::get('/attachments/stream-docx/{filename}', [AttachmentController::class, 'streamDocx'])->name('attachments.streamDocx');
    Route::get('/attachments/docx-viewer/{filename}', [AttachmentController::class, 'showDocxViewer'])->name('attachments.docx-viewer');
    Route::get('/attachments/download/{type}/{filename}', [AttachmentController::class, 'download'])->name('activity.download');
    Route::get('/attachments/info/{filename}', [AttachmentController::class, 'info'])->name('attachments.info');
});

// Approval Workflow Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/activities/{activity}/adviser-note', [App\Http\Controllers\ApprovalWorkflowController::class, 'adviserNote'])
        ->name('workflow.adviser-note');

    // Specific approval workflow routes
    Route::get('/activities/{activity}/dean-note', [WorkflowController::class, 'showApprovalForm'])->name('workflow.dean-note');
    Route::post('/activities/{activity}/dean-note', [WorkflowController::class, 'processApproval'])->name('workflow.dean-note.submit');
    Route::get('/activities/{activity}/psg-review', [WorkflowController::class, 'showApprovalForm'])->name('workflow.psg-review');
    Route::post('/activities/{activity}/psg-review', [WorkflowController::class, 'processApproval'])->name('workflow.psg-review.submit');
    Route::get('/activities/{activity}/director-endorse', [WorkflowController::class, 'showApprovalForm'])->name('workflow.director-endorse');
    Route::post('/activities/{activity}/director-endorse', [WorkflowController::class, 'processApproval'])->name('workflow.director-endorse.submit');
    Route::get('/activities/{activity}/vp-approve', [WorkflowController::class, 'showApprovalForm'])->name('workflow.vp-approve');
    Route::post('/activities/{activity}/vp-approve', [WorkflowController::class, 'processApproval'])->name('workflow.vp-approve.submit');
});

// New Comprehensive Workflow Routes
Route::middleware(['auth', 'verified', 'approval'])->group(function () {
    // Generic approval routes
    Route::get('/approval/{activity}', [WorkflowController::class, 'showApprovalForm'])->name('workflow.approval.form');
    Route::post('/approval/{activity}', [WorkflowController::class, 'processApproval'])->name('workflow.approval.process');
    Route::get('/approval/{activity}/details', [WorkflowController::class, 'showActivityDetails'])->name('workflow.activity.details');

    // Role-specific approval routes
    Route::middleware(['role:dean'])->group(function () {
        // Route disabled: Dean activities listing page (workflow)
        // Route::get('/dean/activities', [WorkflowController::class, 'getPendingActivities'])->name('dean.activities');
    });

    // PSG activities route removed

    Route::middleware(['role:director'])->group(function () {
        // Route removed: /director/activities
    });

    Route::middleware(['role:vp'])->group(function () {
        // Route disabled: VP activities listing page (workflow)
        // Route::get('/vp/activities', [WorkflowController::class, 'getPendingActivities'])->name('vp.activities');
    });
});

// Activity Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('activities', ActivityController::class);

    // Real-time conflict checking for activity creation
    Route::post('activities/check-conflicts', [ActivityController::class, 'checkConflicts'])->name('activities.check-conflicts');
});

// Approval Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Adviser approval routes
    Route::middleware(['role:adviser'])->group(function () {
        Route::get('/approval/adviser/{activity}', [ApprovalController::class, 'showAdviserForm'])->name('approval.adviser.form');
        Route::post('/approval/adviser/{activity}', [ApprovalController::class, 'adviserRecommend'])->name('approval.adviser.submit');
    });

    // OSA approval routes
    Route::middleware(['role:osa'])->group(function () {
        Route::get('/approval/osa/{activity}', [ApprovalController::class, 'showOsaForm'])->name('approval.osa.form');
        Route::post('/approval/osa/{activity}', [ApprovalController::class, 'osaDecision'])->name('approval.osa.submit');
    });

    // Conflict check route (for all authenticated users)
    Route::get('/activities/{activity}/conflicts', [ApprovalController::class, 'checkConflicts'])->name('activities.conflicts');
    // AJAX conflict checker used by create-activity form
    Route::post('/activities/check-conflicts', [ActivityController::class, 'checkConflicts'])->name('activities.check-conflicts');
});

// Admin Routes (require admin authentication)
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::patch('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');

    // Organization management
    Route::get('/organizations', [AdminController::class, 'organizations'])->name('organizations');
    Route::get('/organizations/create', [AdminController::class, 'createOrganization'])->name('organizations.create');
    Route::post('/organizations', [AdminController::class, 'storeOrganization'])->name('organizations.store');
    Route::get('/organizations/{organization}/edit', [AdminController::class, 'editOrganization'])->name('organizations.edit');
    Route::patch('/organizations/{organization}', [AdminController::class, 'updateOrganization'])->name('organizations.update');
    Route::delete('/organizations/{organization}', [AdminController::class, 'deleteOrganization'])->name('organizations.delete');

    // Activity logs
    Route::get('/logs', [AdminController::class, 'activityLogs'])->name('logs');

    // Activity management (admin can only edit status)
    Route::get('/activities', [AdminController::class, 'activities'])->name('activities');
    Route::get('/activities/{activity}', [AdminController::class, 'showActivity'])->name('activities.show');
    Route::get('/activities/{activity}/edit-status', [AdminController::class, 'editActivityStatus'])->name('activities.edit-status');
    Route::patch('/activities/{activity}/status', [AdminController::class, 'updateActivityStatus'])->name('activities.update-status');
    Route::delete('/activities/{activity}', [AdminController::class, 'deleteActivity'])->name('activities.delete');

    // Admin file download routes
    Route::get('/activities/{activity}/download/{fileType}', [AdminController::class, 'downloadActivityFile'])
        ->where('fileType', 'budget_file|permit_file|supporting_documents')
        ->name('activities.download');

    // Reports routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminReportsController::class, 'index'])->name('index');
        Route::get('/filter-options', [AdminReportsController::class, 'getFilterOptions'])->name('filter-options');

        // Activities reports
        Route::get('/activities', function () {
            return view('admin.reports.activities');
        })->name('activities.view');
        Route::post('/activities', [AdminReportsController::class, 'activitiesReport'])->name('activities.generate');

        // Users reports
        Route::get('/users', function () {
            return view('admin.reports.users');
        })->name('users.view');
        Route::post('/users', [AdminReportsController::class, 'usersReport'])->name('users.generate');

        // Statistics reports
        Route::get('/statistics', function () {
            return view('admin.reports.statistics');
        })->name('statistics.view');
        Route::post('/statistics', [AdminReportsController::class, 'statisticsReport'])->name('statistics.generate');
    });
});

require __DIR__.'/auth.php';
