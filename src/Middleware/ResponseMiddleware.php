<?php

class ResponseMiddleware
{
    /**
     * Container variable
     *
     */
    private $container;

    /**
     * Create a new ResponseMiddleware instance.
     *
     */
    public function __construct($container) {
        $this->container = $container;
    }

    /**
     * Middleware that forms the response
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $response = $next($request, $response);

        return $response
            ->withHeader('Access-Control-Allow-Origin', getenv('CORS_ALLOWED_ORIGINS'))
            ->withHeader('Access-Control-Max-Age', getenv('ACCESS_MAX_AGE'))
            ->withHeader('Access-Control-Allow-Headers', getenv('ACCESS_HEADERS'))
            ->withHeader('Access-Control-Allow-Methods', getenv('ACCESS_METHODS'));
    }
}
