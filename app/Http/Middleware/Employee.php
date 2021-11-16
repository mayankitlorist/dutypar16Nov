<?php


namespace App\Http\Middleware;


use App\Traits\GovermentOfMaharashtraToken;
use Closure;
use Illuminate\Http\Request;

class Employee
{
    use GovermentOfMaharashtraToken;

    public function handle(Request $request, Closure $next)
    {
        if (!$request->header('token')) {
            return response()->json(['token' => $this->getTokenHash($request->token), 'status' => false,'message' =>  'token expired','data' => []], 403);
        }
        return $next($request);
    }

}
