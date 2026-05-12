<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(){
        try{
            $courses = Course::all();
            if($courses->isEmpty()){
                return response()->json([
                    'success' => false,
                    'message' => ' Aucun cours trouvé dans la base de données ',
                    'data' => null
                ], 404);
            }
            return response()->json([
                'success' => true,
                'message' => "Listes de toutes les cours",
                'data' => $courses
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
            $course = Course::find($id);
            if (!$course){
                return response()->json([
                    'success' => false,
                    'message' => 'Cours non trouvé ',
                    'data' => null
                ],404);
            }   
            return response()->json([
                'success' => true,
                'message' => "Details du cours",
                'data' => $course
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
    public function store(Request $request){
        try{  
            $validated = $request->validate([
                'title'=> 'required|string|max:255',
                'description' => 'required|string|max:255',
                'price'=> 'required|numeric',
                'duration_hours'=> 'required|numeric',
                'instructor_id' => 'required|exists:instructors,id'
            ]);
            $course = Course::create($validated);
            return response()->json([
                'success' => true,
                'message' => 'Cours crée avec succès !',
                'data' => $course
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
            $course = Course::find($id);

            if(!$course){
                return response()->json([
                    'success' => false,
                    'message' => 'Inexistant',
                    'data' => false
                ], 404);
            }

            $validated = $request->validate([
                'title'=> 'sometimes|string|max:255',
                'description' => 'sometimes|string|max:255',
                'price'=> 'sometimes|numeric',
                'duration_hours'=> 'sometimes|numeric'
            ]);

            $course->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Cours mise à jour avec succès !',
                'data' => $course
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

            $course = Course::find($id);

            if(!$course){
                return response()->json([
                    'success' => false,
                    'message' => 'Cours introuvable',
                    'data' => null
                ], 404);
            }

            $course->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cours supprimé avec succès',
                'data' => null
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
