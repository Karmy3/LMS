<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\CourseAiController;
use App\Http\Controllers\PaymentWebhookController;

Route::prefix('v1')->group(function () {
    // --- PARTIE PUBLIQUE (Auth ? Non) --- 
    // Tout le monde peut y accéder sans token
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::get('courses', [CourseController::class, 'index']);
    Route::get('courses/{id}', [CourseController::class, 'show']);
    Route::get('/courses/{id}/description', [CourseAiController::class, 'showDescription']);
    Route::get('instructors', [InstructorController::class, 'index']);
    Route::post('/webhooks/payment', [PaymentWebhookController::class, 'handle']);


    // --- PARTIE PROTÉGÉE (Auth ? Oui) ---
    // Laravel vérifiera la présence du Token JWT avant de laisser passer la requête
    Route::middleware('auth:api')->group(function () {

        Route::get('students', [StudentController::class, 'index']);
        Route::post('students', [StudentController::class, 'store']);
        Route::get('students/{id}', [StudentController::class, 'show']);
        Route::put('students/{id}', [StudentController::class, 'update']);
        Route::delete('students/{id}', [StudentController::class, 'destroy']);

        Route::post('courses', [CourseController::class, 'store']);
        Route::put('courses/{id}', [CourseController::class, 'update']);
        Route::delete('courses/{id}', [CourseController::class, 'destroy']);
        Route::post('/courses/{id}/generate-description', [CourseAiController::class, 'generate']);

        Route::post('instructors', [InstructorController::class, 'store']);
        Route::put('instructors/{id}', [InstructorController::class, 'update']);
        Route::delete('instructors/{id}', [InstructorController::class, 'destroy']);

        Route::get('enrollments', [EnrollmentController::class, 'index']);
        Route::post('enrollments', [EnrollmentController::class, 'store']);
        Route::patch('enrollments/{id}/complete', [EnrollmentController::class, 'update']);
        Route::delete('enrollments/{id}', [EnrollmentController::class, 'destroy']);
    });
});