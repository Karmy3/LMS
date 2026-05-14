<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API de Gestion Scolaire",
 *     description="Documentation interactive"
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Serveur Local"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class SwaggerInfo {
    
}