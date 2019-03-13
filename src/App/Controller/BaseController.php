<?php

namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Model\CModelInterface;

class BaseController
{
    /**
     * BaseController constructor.
     *
     */
    public function __construct() {}

    /**
     * Convert the request to associated model
     *
     * @param \Slim\Http\Request $request
     * @param \App\Model\CModelInterface $model
     * @param array $args
     *
     * @return \App\Model\CModelInterface
     */
    protected function convertToModel($request, CModelInterface $model, array $args = [])
    {
        return $model->convert($request, $args);
    }

    /**
     * Convert the payload to associated wrapper
     *
     * @param array $payload
     * @param \App\Wrapper\CWrapperInterface $wrapper
     * @param array $args
     *
     * @return \App\Model\CModelInterface
     */
    protected function convertToWrapper($payload, $wrapper)
    {
        return $wrapper->convert($payload);
    }

    /**
     * Convert the payload to associated wrapper
     *
     * @param array $payload
     * @param \App\Wrapper\CWrapperInterface $wrapper
     *
     * @return array<\App\Wrapper\CWrapper>
     */
    protected function convertToWrapperList($payload, $wrapper)
    {
        return $wrapper->convertToList($payload);
    }

    /**
     * Convert the request to associated model List
     *
     * @param \Slim\Http\Request $request
     * @param \App\Model\CModelInterface $model
     * 
     * @return \App\Model\CModelInterface
     */
    public static function convertToListModel($request, CModelInterface $model)
    {
        return $model->convertToListModel($request);
    }

    /**
     * Convert the request to associated model new list (without id)
     *
     * @param \Slim\Http\Request $request
     * @param \App\Model\CModelInterface $model
     * 
     * @return \App\Model\CModelInterface
     */
    public static function convertToNewListModel($request, CModelInterface $model)
    {
            return $model->convertToNewListModel($request);
    }
    
    /**
     * Convert the request to filter model
     *
     * @param \Slim\Http\Request $request
     * @param \App\Model\CModelInterface $model
     * 
     * @return \App\Model\CModelInterface
     */
    public static function convertToFilter($request, CModelInterface $model)
    {
            return $model->convertToFilter($request);
    }

    /**
     * Return response with error messages
     *
     * @param \Slim\Http\Response $response
     * @param array $messages
     * 
     * @return response
     */
    protected function returnWithErrors(Response $response, $messages)
    {
        return $response->withJson([
            'success' => false,
            'errors' => is_array($messages) ? $messages : [$messages] 
        ]);
    }
}