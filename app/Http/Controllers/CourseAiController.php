<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Services\AiService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class CourseAiController extends Controller
{
    protected $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * @OA\Post(
     *     path="/api/courses/{id}/generate",
     *     tags={"AI Service"},
     *     summary="Générer une description marketing via IA",
     *     description="Utilise l'IA pour créer une description si elle est vide",
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
     *         description="Description générée ou déjà existante",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="summary", type="string")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Cours non trouvé",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cours non trouvé"),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=503,
     *         description="IA indisponible",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     )
     * )
     */
    public function generate($id)
    {
        $course = Course::with('instructor')->find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Cours non trouvé',
                'data' => null
            ], 404);
        }

        if (!empty($course->description)) {
            return response()->json([
                'success' => true,
                'message' => 'Description existante',
                'data' => [
                    'description' => $course->description,
                    'summary' => $course->description
                ]
            ], 200);
        }

        $description = $this->aiService->generateMarketingDescription($course);

        if (!$description) {
            return response()->json([
                'success' => false,
                'message' => 'IA indisponible',
                'data' => null
            ], 503);
        }

        $course->description = $description;
        $course->save();

        return response()->json([
            'success' => true,
            'message' => 'Description générée avec succès',
            'data' => [
                'description' => $description,
                'summary' => $description
            ]
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/courses/{id}/description",
     *     tags={"AI Service"},
     *     summary="Récupérer la description d'un cours",
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
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="description", type="string")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Cours ou description absente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     )
     * )
     */
    public function showDescription($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Cours non trouvé',
                'data' => null
            ], 404);
        }

        if (!$course->description) {
            return response()->json([
                'success' => false,
                'message' => 'Description absente',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Description récupérée',
            'data' => [
                'description' => $course->description
            ]
        ], 200);
    }
}