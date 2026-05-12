<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(){
        try{
            $students = Student::all();
            if($students->isEmpty()){
                return response()->json([
                    'success' => false,
                    'message' => ' Aucun étudiant trouvé dans la base de données ',
                    'data' => null
                ], 404);
            }
            return response()->json([
                'success' => true,
                'message' => "Listes des étudiants",
                'data' =>  $students
            ],200);
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
            $student = Student::find($id);
            if (!$student){
                return response()->json([
                    'success' => false,
                    'message' => 'Etudiant non trouvé ',
                    'data' => null
                ],404);
            }   
            return response()->json([
                'success' => true,
                 'message' => 'Détails de l\'étudiant récupérés avec succès',
                 'data' => $student
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
    public function store(Request $request){
        try{  
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email'=> 'required|email|unique:students',
                'phone' => 'required|digits:10',
                'enrolled_at' => 'nullable|date'
            ]);
            $student = Student::create($validated);
            return response()->json([
                'success' => true,
                'message' => 'Etudiant crée avec succès !',
                'data' => $student
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
            $student = Student::find($id);

            if(!$student){
                return response()->json([
                    'success' => false,
                    'message' => 'Inexistant',
                    'data' => null
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:students,email,' . $id,
                'phone' => 'sometimes|digits:10',
            ]);

            $student->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Etudiant mise à jour avec succès !',
                'data' => $student
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

            $student = Student::find($id);

            if(!$student){
                return response()->json([
                    'success' => false,
                    'message' => 'Etudiant introuvable',
                    'data' => null
                ], 404);
            }

            $student->delete();

            return response()->json([
                'success' => true,
                'message' => 'Etudiant supprimé avec succès',
                'data' => $student,
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
