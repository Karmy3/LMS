<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class PaymentWebhookController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/webhooks/payment",
     *     summary="Webhook de confirmation de paiement",
     *     tags={"Payments"},
     *
     *     @OA\Header(
     *         header="X-Webhook-Signature",
     *         description="Signature HMAC-SHA256",
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"event","data"},
     *             @OA\Property(
     *                 property="event",
     *                 type="string",
     *                 example="payment.succeeded"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="enrollment_id",
     *                     type="integer",
     *                     example=1
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="OK"),
     *             @OA\Property(property="received", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Signature invalide",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Enrollment introuvable",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     )
     * )
     */
    public function handle(Request $request)
    {
        $signatureHeader = $request->header('X-Webhook-Signature');

        if (!$signatureHeader) {
            return response()->json([
                'success' => false,
                'message' => 'Signature absente',
                'data' => null
            ], 401);
        }

        $secret = env('WEBHOOK_SECRET');
        $payload = $request->getContent();
        $computedSignature = 'sha256=' . hash_hmac('sha256', $payload, $secret);
    
        if (!hash_equals($computedSignature, $signatureHeader)) {
            return response()->json([
                'success' => false,
                'message' => 'Signature invalide',
                'data' => null
            ], 401);
        }

        $data = $request->input('data');
        $event = $request->input('event');

        if ($event === 'payment.succeeded') {

            $enrollment = Enrollment::find($data['enrollment_id']);

            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Enrollment introuvable',
                    'data' => null
                ], 404);
            }

            $enrollment->update([
                'payment_status' => 'paid',
                'status' => 'active'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Paiement confirmé',
                'received' => true,
                'data' => $enrollment
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Événement ignoré',
            'received' => true
        ], 200);
    }
}