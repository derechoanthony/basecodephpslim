<?php

namespace App\Service;

use App\Helper\CCommon;


class BaseService
{
    /**
     * BaseService constructor.
     *
     */
    public function __construct() {}

     /**
     * Return array with error messages
     *
     * @param string $messages
     * 
     * @return array
     */
    protected function returnWithErrors($messages)
    {
        return [
            'success' => false,
            'errors' => is_array($messages) ? $messages : [$messages] 
        ];
    }

    /**
     * Return array with error messages and data
     *
     * @param string $messages
     * 
     * @return array
     */
    protected function returnWithErrorsData($messages,$data)
    {
        return [
            'success' => false,
            'errors' => is_array($messages) ? $messages : [$messages] ,
            'data' => $data
        ];
    }
     /**
     * Return success response with data
     *
     * @param array $data
     * 
     * @return array
     */
    protected function returnSuccess(array $data)
    {
        return [
            'success' => true,
            'data' => $data
        ];
    }

    /**
     * Return success response with data
     *
     * @param array $data
     * 
     * @return array
     */
    protected function returnSuccessObject($data)
    {
        return [
            'success' => true,
            'data' => $data
        ];
    }


    /**
     * Will convert password
     *
     * @param string $password
     * 
     * @return string
     */
    public function convertPassword($password)
    {
        return password_hash(base64_decode($password), PASSWORD_DEFAULT);
    }

    /**
     * Will convert password
     *
     * @param string $password
     * 
     * @return string
     */
    public function encodePassword($password)
    {
        return password_hash(base64_encode($password), PASSWORD_DEFAULT);
    }

    /**
     * Return transformed collection data with pagination
     *
     * @param object $transformer
     * @param \Illuminate\Pagination\LengthAwarePaginator $model
     * 
     * @return array
     */
    protected function transformWithPagination($transformer, $model)
    {
        
        if ($model == null) return [];
        return [
            'items' => $transformer->transformCollection($model->items()),
            'total' => $model->total(),
            'pages' => ceil($model->total() / CCommon::ITEMS_PER_PAGE),
            'lastPage' => $model->lastPage(),
            'currentPage' => $model->currentPage(),
            'perPage' => $model->perPage()
        ];
    }
    protected function transformCollectionActivity($transformer,$modelList)
    {
        
        if ($modelList == null) return [];
        return $transformer->transformCollectionActivity($modelList);
    }
    protected function transformListObject($transformer,$modelList){
        if ($modelList == null) return [];
        return $transformer->transformListObject($modelList);
    }

    /**
     * Default Password
     */ 
    protected function getDefaultPassword()
    {
        return getenv('DEFAULT_PASSWORD');
    }

    /**
     * Check if the environment is local
     * 
     * @return boolean
     */
    protected function isLocal()
    {
        return getenv('APP_ENV') !== 'local' ? false : true;
    }
}