<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Signature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Capture the signature from the headers
        $signature = $request->header('signature');

        // Validate the signature
        if (!isValidSignature($signature)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        return $next($request);  // Proceed to the next middleware or controller
    }
}
