<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class StudentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/students",
     *     operationId="getStudentList",
     *     tags={"Students"},
     *     summary="Liste des étudiants",
     *     description="Retourne la liste de tous les étudiants",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Opération réussie",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="phone", type="string"),
     *                 @OA\Property(property="enrolled_at", type="string", format="date")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
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
    }

    /**
     * @OA\Get(
     *     path="/api/students/{id}",
     *     tags={"Students"},
     *     summary="Détails d'un étudiant",
     *     security={{"bearerAuth":{}}},
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
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="enrolled_at", type="string")
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
    }

    /**
     * @OA\Post(
     *     path="/api/students",
     *     tags={"Students"},
     *     summary="Créer un étudiant",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name","email","phone"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="enrolled_at", type="string", format="date")
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
    }

    /**
     * @OA\Put(
     *     path="/api/students/{id}",
     *     tags={"Students"},
     *     summary="Modifier un étudiant",
     *     security={{"bearerAuth":{}}},
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
     *         description="Non trouvé"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Étudiant introuvable',
                'data' => null
            ], 404);
        }

        $student->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Étudiant mis à jour avec succès',
            'data' => $student
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/students/{id}",
     *     tags={"Students"},
     *     summary="Supprimer un étudiant",
     *     security={{"bearerAuth":{}}},
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
    }
}