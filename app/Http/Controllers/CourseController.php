<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        try {
            $courses = Course::with('instructor')->get();

            if ($courses->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun cours trouvé',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Liste des cours récupérée avec succès',
                'data' => $courses
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
           $course = Course::with('instructor', 'enrollments')->find($id);

            if (!$course) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cours introuvable',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Détails du cours récupérés avec succès',
                'data' => $course
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
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'duration_hours' => 'required|numeric',
                'instructor_id' => 'required|exists:instructors,id'
            ]);

            $course = Course::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Cours créé avec succès',
                'data' => $course
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
            $course = Course::find($id);

            if (!$course) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cours introuvable',
                    'data' => null
                ], 404);
            }

            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'price' => 'sometimes|numeric',
                'duration_hours' => 'sometimes|numeric'
            ]);

            $course->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Cours mis à jour avec succès',
                'data' => $course
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
            $course = Course::find($id);

            if (!$course) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cours introuvable',
                    'data' => null
                ], 404);
            }

            $course->delete();

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
