<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class CourseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/courses",
     *     tags={"Courses"},
     *     summary="Liste de tous les cours",
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="price", type="number"),
     *                 @OA\Property(property="duration_hours", type="number"),
     *                 @OA\Property(property="instructor_id", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Aucun cours trouvé",
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
    }

    /**
     * @OA\Get(
     *     path="/api/courses/{id}",
     *     tags={"Courses"},
     *     summary="Détails d'un cours",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number"),
     *             @OA\Property(property="duration_hours", type="number"),
     *             @OA\Property(property="instructor_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cours introuvable",
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
    }

    /**
     * @OA\Post(
     *      path="/api/courses",
     *      tags={"Courses"},
     *      summary="Créer un nouveau cours",
     *      security={{"bearerAuth":{}}},
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              required={"title","description","price","duration_hours","instructor_id"},
     *              @OA\Property(property="title", type="string", example="Laravel Avancé"),
     *              @OA\Property(property="description", type="string", example="Maîtriser les API REST et Swagger"),
     *              @OA\Property(property="price", type="number", example=150.00),
     *              @OA\Property(property="duration_hours", type="number", example=20),
     *              @OA\Property(property="instructor_id", type="integer", example=1)
     *          )
     *     ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Cours créé avec succès",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Cours créé avec succès")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=422,
     *          description="Erreur de validation",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="errors", type="object")
     *          )
     *      )
     * )
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title'          => 'required|string|max:255',
                'description'    => 'required|string',
                'price'          => 'required|numeric',
                'duration_hours' => 'required|numeric',
                'instructor_id'  => 'required|exists:instructors,id'
            ]);

            $course = Course::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Cours créé avec succès',
                'data'    => $course
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors'  => $e->errors()
            ], 422);
        }
    }
    /**
     * @OA\Put(
     *      path="/api/courses/{id}",
     *      tags={"Courses"},
     *      summary="Modifier un cours",
     *      security={{"bearerAuth":{}}},
     *
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID du cours",
     *          @OA\Schema(type="integer")
     *      ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="title", type="string", example="Laravel Expert"),
     *              @OA\Property(property="price", type="number", example=200.00)
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Cours mis à jour avec succès",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Cours mis à jour avec succès")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=404,
     *          description="Cours introuvable",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Cours introuvable")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=422,
     *          description="Erreur de validation",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="errors", type="object")
     *          )
     *      )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $course = Course::find($id);

            if (!$course) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cours introuvable',
                    'data'    => null
                ], 404);
            }

            $validated = $request->validate([
                'title'          => 'sometimes|string|max:255',
                'description'    => 'sometimes|string',
                'price'          => 'sometimes|numeric',
                'duration_hours' => 'sometimes|numeric',
                'instructor_id'  => 'sometimes|exists:instructors,id'
            ]);

            $course->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Cours mis à jour avec succès',
                'data'    => $course
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors'  => $e->errors()
            ], 422);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/courses/{id}",
     *     tags={"Courses"},
     *     summary="Supprimer un cours",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Supprimé"
     *     )
     * )
     */
    public function destroy($id)
    {
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
    }
}