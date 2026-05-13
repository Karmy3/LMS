<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Student::query();

            if ($request->name) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            $students = $query->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'message' => 'Liste des étudiants récupérée avec succès',
                'data' => $students->items(),
                'meta' => [
                    'total' => $students->total(),
                    'current_page' => $students->currentPage(),
                    'last_page' => $students->lastPage(),
                    'per_page' => $students->perPage()
                ]
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
            $student = Student::with('enrollments')->find($id);

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Étudiant non trouvé',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Détails de l\'étudiant récupérés avec succès',
                'data' => $student
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
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:students',
                'phone' => 'required|digits:10',
                'enrolled_at' => 'nullable|date'
            ]);

            $student = Student::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Étudiant créé avec succès',
                'data' => $student
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
            $student = Student::find($id);

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Étudiant introuvable',
                    'data' => null
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:students,email,' . $id,
                'phone' => 'sometimes|digits:10',
                'enrolled_at' => 'sometimes|nullable|date'
            ]);

            $student->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Étudiant mis à jour avec succès',
                'data' => $student
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
            $student = Student::find($id);

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Étudiant introuvable',
                    'data' => null
                ], 404);
            }

            $student->delete();

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
