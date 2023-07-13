<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
        'v4/396d6585-16ae-4d04-9549-c499e52b75ea/auth/register',
        'v3/396d6585-16ae-4d04-9549-c499e52b75ea/surat-masuk/create',
        'v4/396d6585-16ae-4d04-9549-c499e52b75ea/auth/login'
    ];
}
