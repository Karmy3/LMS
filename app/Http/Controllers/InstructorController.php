<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class InstructorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/instructors",
     *     tags={"Instructors"},
     *     summary="Liste des formateurs",
     *     @OA\Response(
     *         response=200,
     *         description="Liste récupérée",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="specialty", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Aucun trouvé",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     )
     * )
     */
    public function index()
    {
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
    }

    /**
     * @OA\Get(
     *     path="/api/instructors/{id}",
     *     tags={"Instructors"},
     *     summary="Détails d'un formateur",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="specialty", type="string")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Formateur non trouvé",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     )
     * )
     */
    public function show($id)
    {
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
    }

    /**
     * @OA\Post(
     *     path="/api/instructors",
     *     tags={"Instructors"},
     *     summary="Créer un formateur",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name","email","specialty"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="specialty", type="string")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Créé"
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Erreur validation"
     *     )
     * )
     */
    public function store(Request $request)
    {
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
    }

    /**
     * @OA\Put(
     *     path="/api/instructors/{id}",
     *     tags={"Instructors"},
     *     summary="Modifier un formateur",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(type="object")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Mis à jour"
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Formateur introuvable"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $instructor = Instructor::find($id);

        if (!$instructor) {
            return response()->json([
                'success' => false,
                'message' => 'Formateur introuvable',
                'data' => null
            ], 404);
        }

        $instructor->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Formateur mis à jour avec succès',
            'data' => $instructor
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/instructors/{id}",
     *     tags={"Instructors"},
     *     summary="Supprimer un formateur",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Supprimé"
     *     )
     * )
     */
    public function destroy($id)
    {
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
    }
}