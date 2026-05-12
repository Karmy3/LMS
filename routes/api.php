<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// --- PARTIE PUBLIQUE (Auth ? Non) ---
// Tout le monde peut y accéder sans token
Route::post('v1/auth/login', [AuthController::class, 'login']); 
Route::get('v1/courses', [CourseController::class, 'index']);
Route::get('v1/instructors', [InstructorController::class, 'index']);


// --- PARTIE PROTÉGÉE (Auth ? Oui) ---
// Laravel vérifiera la présence du Token JWT avant de laisser passer la requête
Route::middleware('auth:api')->prefix('v1')->group(function () {

    // Routes pour les étudiants
    Route::get('students', [StudentController::class, 'index']);    // Auth ? Oui
    Route::post('students', [StudentController::class, 'store']);   // Auth ? Oui
    Route::put('students/{id}', [StudentController::class, 'update']);
    Route::delete('students/{id}', [StudentController::class, 'destroy']);

    // Routes pour les inscriptions
    Route::get('enrollments', [EnrollmentController::class, 'index']);
    Route::post('enrollments', [EnrollmentController::class, 'store']);

});