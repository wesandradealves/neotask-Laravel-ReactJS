<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\AuthenticationException;

class Authenticate extends Middleware
{
    protected function unauthenticated($request, array $guards)
    {
        if ($request->expectsJson()) {
            throw new AuthenticationException('Token de autenticação ausente ou inválido.');
        }

        parent::unauthenticated($request, $guards);
    }
}
