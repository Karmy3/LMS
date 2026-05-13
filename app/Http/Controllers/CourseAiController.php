<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseAiController extends Controller
{
    protected $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function generate($id)
    {
        try {
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
                    'data' => ['description' => $course->description, 'summary' => $course->description]
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
                'data' => ['description' => $description, 'summary' => $description]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur Critique : ' . $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

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
            'data' => ['description' => $course->description]
        ], 200);
    }

}