<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Récupérer la signature envoyée par Postman
        $signatureHeader = $request->header('X-Webhook-Signature');

        // 2. Vérifier si la signature est présente
        if (!$signatureHeader) {
            return response()->json([
                'success' => false, 
                'message' => 'Signature absente',
                'data' => null
            ], 401);
        }

        // 3. Calculer la signature locale pour comparer
        $secret = env('WEBHOOK_SECRET');
        $payload = $request->getContent(); 
        $computedSignature = 'sha256=' . hash_hmac('sha256', $payload, $secret);

        // 4.Vérification de la signature
        if (!hash_equals($computedSignature, $signatureHeader)) {
            return response()->json([
                'success' => false, 
                'message' => 'Signature invalide',
                'data' => null
            ], 401);
        }

        // 5. Récupérer les données
        $data = $request->input('data');
        $event = $request->input('event');

        if ($event === 'payment.succeeded') {
            // 6.Vérifier si l'id existe
            $enrollment = Enrollment::find($data['enrollment_id']);

            if (!$enrollment) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Enrollment introuvable',
                    'data' => null
                ], 404);
            }

            // 7.Mise à jour
            $enrollment->update([
                'payment_status' => 'paid',
                'status' => 'active'
            ]);

            return response()->json([
                'success' => true,
                'received' => true,
                'data' => $enrollment
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Evenement ignoré'
        ], 200);
    }
}