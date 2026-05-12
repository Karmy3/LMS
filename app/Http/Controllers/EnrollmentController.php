<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(){
        try{
            $enrollements = Enrollment::all();
            if($enrollements->isEmpty()){
                return response()->json([
                    'success' => false,
                    'message' => ' Aucun inscription trouvé dans la base de données ',
                    'data' => null
                ], 404);
            }
            return response()->json([
                'success' => true,
                'message' => "Liste de toutes les inscriptions",
                'data' => $enrollements
            ]);
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Une erreur interne est survenue',
                'data' => null,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function show($id){
        try{
            $enrollement = Enrollment::find($id);
            if (!$enrollement){
                return response()->json([
                    'success' => false,
                    'message' => 'Inscription non trouvé ',
                    'data' => null
                ],404);
            }   
            return response()->json([
                'success' => true,
                'message' => "Détails de l\'étudiant récupérés avec succès",
                'data' => $enrollement
            ]); 
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Une erreur interne est survenue',
                'data' => null,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request){
        try{  
            $validated = $request->validate([
                'student_id' => 'required|exists:students,id',
                'course_id' => 'required|exists:courses,id',
                'status'=> 'required|in:pending,active,completed',
                'payment_status' => 'required|in:unpaid,paid'
            ]);
            $enrollement = Enrollment::create($validated);
            return response()->json([
                'success' => true,
                'message' => 'Inscription crée avec succès !',
                'data' => $enrollement
            ], 201); 
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Une erreur interne est survenue',
                'data' => null,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request , $id){
        try{  
            $enrollement = Enrollment::find($id);

            if(!$enrollement){
                return response()->json([
                    'success' => false,
                    'message' => 'Inexistant',
                    'data' => null
                ], 404);
            }

            $validated = $request->validate([
                 'status'=> 'sometimes|in:pending,active,completed',
                 'payment_status' => 'sometimes|in:unpaid,paid'
            ]);

            $enrollement->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Inscription mise à jour avec succès !',
                'data' => $enrollement
            ], 200);

        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Une erreur interne est survenue',
                'data' => null,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id){
        try{

            $enrollement = Enrollment::find($id);

            if(!$enrollement){
                return response()->json([
                    'success' => false,
                    'message' => 'Inscription introuvable',
                    'data' => null,
                ], 404);
            }

            $enrollement->delete();

            return response()->json([
                'success' => true,
                'message' => 'Inscription supprimé avec succès',
                'data' => $enrollement
            ], 200);

        }catch(\Exception $e){

            return response()->json([
                'success' => false,
                'message' => 'Une erreur interne est survenue',
                'data' => null,
                'error' => $e->getMessage()
            ], 500);

        }
    }
}
