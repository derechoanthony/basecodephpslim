<?php

namespace App\Wrapper;

/**
 * Base class for all wrappers
 */
abstract class CWrapper
{/**
     * Constant for the parent field accessor
     *
     * @var array
     */
    const PARENT_FIELD = 'parent';
    /**
     * Holds the attributes of this wrapper
     *
     * @var array
     */
    protected $attributes;

    /**
     * Holds the name string of the parent class of this wrapper
     *
     * @var string
     */
    protected $parentName;

    /**
     * Holds the empty model of the parent class of this wrapper
     *
     * @var CModelInterface
     */
    protected $parentModel;

    /**
     * Holds the schema of each child class of this wrapper(relationship name and empty model)
     *
     * @var array
     */
    protected $childSchemas;

    /**
     * Convert the payload to associated wrapper
     *
     * @param array $payload
     * @return CWrapper
     */
    public function convert($payload){
        $parentAttributes = [];

        // Iterate contents of payload. In each iteration, check if current key
        // is a legitimate alias(masked column) in the parent model's mapping. If true, store it
        // in parentAttributes. Else, assume that it is a child table and convert it
        // accordingly.
        foreach($payload as $key => $value){
            if($this->parentModel->hasAlias($key)){
                $parentAttributes[$key] = $value;
            } else {
                // If current iterated payload key is a legitimate child table, try to convert
                // it into a single or a list of models, depending on the structure of the array.
                // Afterwards, assign it into this wrapper's attributes.
                if(array_key_exists($key, $this->childSchemas)){
                    $childSchema = $this->childSchemas[$key];
                    if($this->isSingleModel($value)){
                        $childModel = $this->convertToModel($value, $childSchema['model']);
                        $this->attributes[$childSchema['name']] = $childModel;
                    } else {
                        $childModels = $this->convertToListModel($value, $childSchema['model']);
                        $this->attributes[$childSchema['name']] = $childModels;
                    }
                }
            }
        }

        // Convert parent model attributes into an Eloquent model, then assign it to wrapper's attributes.
        $parentModel = $this->convertToModel($parentAttributes, $this->parentModel);
        $this->attributes[$this->parentName] = $parentModel;

        return $this;
    }


    /**
     * Convert a collection of modeled arrays into a wrapper list
     *
     * @param array $data
     * @return object
     */
    public abstract function convertToList($collection);

    /**
     * Fetch the attribute value from the attributes array
     *
     * @param string $key
     * @return object
     */
    public function getAttribute($key){
        if(array_key_exists($key, $this->attributes)){
            return $this->attributes[$key];
        }

        return null;
    }

    /**
     * Magic method to fetch from attributes array of this wrapper
     *
     * @param string $name
     * @return object
     */
    public function __get($name){
        if($name === CWrapper::PARENT_FIELD){
            $name = $this->parentName;
        }
        
        return $this->getAttribute($name);
    }

    /**
     * Fetches the child schemas of this wrapper
     * 
     * @return array
     */
    public function getChildSchemas()
    {
        return $this->childSchemas;
    }

    /**
     * Convert the request to associated model
     *
     * @param array $payload
     * @param \App\Model\CModelInterface $model
     *
     * @return \App\Model\CModelInterface
     */
    protected function convertToModel($payload, $model)
    {
        return $model->convert($payload);
    }

    /**
     * Convert the request to associated model list
     *
     * @param array $payload
     * @param \App\Model\CModelInterface $model
     * 
     * @return \App\Model\CModelInterface
     */
    protected function convertToListModel($payload, $model)
    {
        return $model->convertToListModel($payload);
    }

    /**
     * Checks if passed array is single associative array resembling a model
     *
     * @param array $array
     * 
     * @return \App\Model\CModelInterface
     */
    protected function isSingleModel($array)
    {
        // Checking is done by verifying if the array has string keys. If it has, then it's a single model
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }
}
