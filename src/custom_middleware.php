<?php
/**
 * Author: MD Mehedi Hasan
 * Designaton: Senior Software Engineer
 * Company: ASL Systems Ltd.
 */

class Request
{
    private array $data = [];

    public function has(string $key):bool
    {
        return array_key_exists($key,$this->data);
    }
}

interface MiddlewareInterface
{
    public function handle(Request $request,Closure $next):mixed;
}

class MiddlewareHandler
{
    
    private array $middlewares = [];

    public function add(MiddlewareInterface $middleware):void
    {
        $this->middlewares[] = $middleware;
    }

    public function execute(Request $request):mixed
    {
        $next = static function (mixed $value) {
            return $value;
        };

        foreach($this->middlewares as $middleware)
        {
            $next = static fn() => $middleware->handle($request, $next);
        }

        return $next($request);
    }

}

	 
class VerificationMiddleware implements MiddlewareInterface
{
 
    #[\Override] 
    public function handle(Request $request, Closure $next): mixed
    {
        if(!$request->has("token")){
            throw new Exception("Token Mismatch");
        }
        return $next($request);
    }
}
	 
class AuthMiddleware implements MiddlewareInterface
{
 
    #[\Override] 
    public function handle(Request $request, Closure $next): mixed
    {
        return $next($request);
    }
}

$handler = new MiddlewareHandler();
$handler->add(new VerificationMiddleware());
$handler->add(new AuthMiddleware());
$handler->execute(new Request());
