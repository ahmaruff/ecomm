<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (empty($request->json()->all())) {

            $res = [
                'status' => 'fail',
                'code' => Response::HTTP_BAD_REQUEST,
                'data' => [
                    'errors' => 'The request is not valid JSON'
                ],
            ];

            return response()->json($res, Response::HTTP_BAD_REQUEST);
        }

        return $next($request);
    }
}
