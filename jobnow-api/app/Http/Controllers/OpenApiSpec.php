<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "JobNow API",
    description: "API documentation for JobNow - Moroccan Job Portal Platform. This API provides endpoints for job seekers and companies to manage job offers, applications, and user profiles.",
    contact: new OA\Contact(
        name: "JobNow Support",
        email: "support@jobnow.ma"
    )
)]
#[OA\Server(
    url: "http://localhost:8000",
    description: "Local Development Server"
)]
#[OA\Server(
    url: "https://api.jobnow.ma",
    description: "Production Server"
)]
#[OA\SecurityScheme(
    securityScheme: "sanctum",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT",
    description: "Laravel Sanctum Bearer Token. Format: Bearer {token}"
)]
#[OA\Tag(
    name: "Authentication",
    description: "User authentication and registration endpoints"
)]
#[OA\Tag(
    name: "Job Offers",
    description: "Job offer listing, creation, and management"
)]
#[OA\Tag(
    name: "Applications",
    description: "Job application submission and management"
)]
#[OA\Tag(
    name: "Notifications",
    description: "User notification management"
)]
#[OA\Tag(
    name: "Interviews",
    description: "Interview scheduling and management"
)]
class OpenApiSpec
{
    // This class exists solely for OpenAPI documentation
}
