<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class EnrollmentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/enrollments",
     *     tags={"Enrollments"},
     *     summary="Liste des inscriptions",
     *     description="Peut être filtré par status ou payment_status",
     *
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         @OA\Schema(type="string", enum={"pending", "active", "completed"})
     *     ),
     *
     *     @OA\Parameter(
     *         name="payment_status",
     *         in="query",
     *         @OA\Schema(type="string", enum={"unpaid", "paid"})
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="student_id", type="integer"),
     *                 @OA\Property(property="course_id", type="integer"),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="payment_status", type="string", example="unpaid")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
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
    }

    /**
     * @OA\Get(
     *     path="/api/enrollments/{id}",
     *     tags={"Enrollments"},
     *     summary="Détails d'une inscription",
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
     *             @OA\Property(property="student_id", type="integer"),
     *             @OA\Property(property="course_id", type="integer"),
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="payment_status", type="string")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Non trouvé",
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
    }

    /**
     * @OA\Post(
     *     path="/api/enrollments",
     *     tags={"Enrollments"},
     *     summary="Créer une nouvelle inscription",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"student_id","course_id","status","payment_status"},
     *             @OA\Property(property="student_id", type="integer"),
     *             @OA\Property(property="course_id", type="integer"),
     *             @OA\Property(property="status", type="string", example="pending"),
     *             @OA\Property(property="payment_status", type="string", example="unpaid")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Inscription réussie"
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
    }

    /**
     * @OA\Put(
     *     path="/api/enrollments/{id}",
     *     tags={"Enrollments"},
     *     summary="Modifier une inscription",
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
     *         @OA\JsonContent(
     *             type="object"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Mis à jour"
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Non trouvé"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $enrollment = Enrollment::find($id);

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'Inscription introuvable',
                'data' => null
            ], 404);
        }

        $enrollment->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Inscription mise à jour avec succès',
            'data' => $enrollment
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/enrollments/{id}",
     *     tags={"Enrollments"},
     *     summary="Supprimer une inscription",
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
    }
}