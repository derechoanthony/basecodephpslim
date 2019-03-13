<?php
use App\Helper\Messages\CAuthMessages;
use App\Helper\Exceptions\CInvalidApiKeyException;

class RequestMiddleware
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
    public function __construct($container)
    {
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
        if ($request->isPost() && !$this->isValidAPIKey($request)) {
            $exception = new CInvalidApiKeyException;
            return $exception($request, $response);
        }

        // Decode ID
        $this->decodeRequestId($request);
        $response = $next($request, $response);
        $this->container['logger']->info('[URI] ' . $request->getUri());
        $this->container['logger']->info('[API-key] ' . $request->getHeaderLine('HTTP_X_API_KEY'));
        $this->container['logger']->info('[TOKEN] ' . $request->getHeaderLine('HTTP_AUTHORIZATION'));
        $this->container['logger']->info('[CONTENT-TYPE] ' . $request->getHeaderLine('HTTP_CONTENT_TYPE'));
        $this->container['logger']->info('[AGENT] ' . $request->getHeaderLine('HTTP_USER_AGENT'));
        $this->container['logger']->info('[REQUEST] ' . $request->getBody());
        $this->container['logger']->info('[RESPONSE] ' . $response);

        return $this->isTokenExpired($response);
    }

    /**
     * validate or check token
     *
     * @param [type] $response
     * @return response
     */
    public function isTokenExpired($response){
        $statusCode = $response->getStatusCode();
        if($statusCode == 401){
            return $response->withJson(array(
                "message" => CAuthMessages::TOKENEXPIRED
            ));
        }
        else{
            return $response;
        }
    }

    /**
     * Check if the api key is valid
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     *
     * @return boolean
     */
    public function isValidAPIKey($request)
    {
        return $request->getHeaderLine('X-Api-Key') === getenv('API_KEY');
    }

    /**
     * Decode all encrypted request id
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     *
     * @return void
     */
    public function decodeRequestId($request)
    {
        $route = $request->getAttribute('route');

        if ($route == null) {
            return;
        }

        $args = $route->getArguments();

        if (empty($args)) {
            return;
        }

        // Add the id you want to decrypt
        if (array_key_exists('userId', $args)) {
            if ($args['userId'] != null) {
                $args['userId'] = base64_decode($args['userId']);
            }
        }
    }
}
