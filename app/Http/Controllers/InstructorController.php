<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function index()
    {
        try {
            $instructors = Instructor::all();

            if ($instructors->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun formateur trouvé',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Liste des formateurs récupérée avec succès',
                'data' => $instructors
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
            $instructor = Instructor::find($id);

            if (!$instructor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Formateur non trouvé',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Détails du formateur récupérés avec succès',
                'data' => $instructor
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
                'email' => 'required|email|unique:instructors',
                'specialty' => 'required|string|max:255'
            ]);

            $instructor = Instructor::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Formateur créé avec succès',
                'data' => $instructor
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
            $instructor = Instructor::find($id);

            if (!$instructor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Formateur introuvable',
                    'data' => null
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:instructors,email,' . $id,
                'specialty' => 'sometimes|string|max:255'
            ]);

            $instructor->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Formateur mis à jour avec succès',
                'data' => $instructor
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
            $instructor = Instructor::find($id);

            if (!$instructor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Formateur introuvable',
                    'data' => null
                ], 404);
            }

            $instructor->delete();

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
