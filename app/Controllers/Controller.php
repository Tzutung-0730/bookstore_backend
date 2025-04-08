<?php

namespace App\Controllers;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="My Bookstore API",
 *     description="這是書店的 API 文件"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
abstract class Controller
{
    //
}
