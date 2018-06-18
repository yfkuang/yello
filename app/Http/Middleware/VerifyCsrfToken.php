<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        "*lead"
    ];
	
	/**
     * Determine if the session and input CSRF tokens match.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
}
