<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Enrollment::with(['student', 'course']);

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('payment_status')) {
                $query->where('payment_status', $request->payment_status);
            }

            $enrollments = $query->get();

            if ($enrollments->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune inscription trouvée',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Liste des inscriptions récupérée avec succès',
                'data' => $enrollments
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur interne est survenue',
                'data' => null,
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $enrollment = Enrollment::find($id);

            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inscription non trouvée',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Détails de l\'inscription récupérés avec succès',
                'data' => $enrollment
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur interne est survenue',
                'data' => null,
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'student_id' => 'required|exists:students,id',
                'course_id' => 'required|exists:courses,id',
                'status' => 'required|in:pending,active,completed',
                'payment_status' => 'required|in:unpaid,paid'
            ]);

            $enrollment = Enrollment::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Inscription créée avec succès',
                'data' => $enrollment
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation échouée',
                'data' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur interne est survenue',
                'data' => null,
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $enrollment = Enrollment::find($id);

            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inscription introuvable',
                    'data' => null
                ], 404);
            }

            $validated = $request->validate([
                'status' => 'sometimes|in:pending,active,completed',
                'payment_status' => 'sometimes|in:unpaid,paid'
            ]);

            $enrollment->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Inscription mise à jour avec succès',
                'data' => $enrollment
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation échouée',
                'data' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur interne est survenue',
                'data' => null,
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $enrollment = Enrollment::find($id);

            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inscription introuvable',
                    'data' => null
                ], 404);
            }

            $enrollment->delete();

            return response()->json(null, 204);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur interne est survenue',
                'data' => null,
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);

        }
    }
}
