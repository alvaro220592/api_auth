<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
Use Illuminate\Routing\ResponseFactory;

class JsonResponseMiddleware
{
    public function __construct(ResponseFactory $responseFactory)
    {
       $this->responseFactory = $responseFactory; 
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');

        $response = $next($request);

        if(! $response instanceof JsonResponse){
            $response = $this->responseFactory->json(
                $response->content(),
                $response->status(),
                $response->headers->all()
            );
        }

        return $next($request);
    }
}
