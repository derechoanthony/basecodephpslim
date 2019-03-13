<?php

namespace App\Handler;

use App\Helper\CValidator;

abstract class CHandler
{
    /**
     * Holds the name of the model to be handled
     *
     * @var string
     */
    protected $modelName = '';

    /**
     * Handle a collection of items
     *
     * @param items
     * @return array
     */
	public function handleCollection($items, $method = 'handle')
    {
        $collectionValidator = new CValidator();

        $validators = array_map([$this, $method], $items);

        $flag = 1;
        foreach($validators as $validator){
            if(!$validator->isValid()){
                foreach($validator->getErrors() as $error){
                    // Labels each error with the corresponding model it is associated with
                    $labeledError = $this->modelName.' '.$flag.': '.$error;
                    $collectionValidator->setErrors($labeledError);
                }
            }

            $flag++;
        }

        return $collectionValidator;
    }

    /**
     * Validate the passed model
     *
     * @param \App\Model\CModelInterface $model
     *
     * @return array
     */
    public abstract function handle($model);

    /**
     * Filter nonexistent column values from the model
     *
     * @param array $mapping
     * @param \App\Model\CModelInterface $model
     *
     * @return array
     */
    protected function setValues($mapping, $model){
        $output = [];

        if($model){
            foreach($mapping as $name => $fields){
                if(is_array($fields)){
                    $temp = [];
                    foreach($fields as $key => $field){
                        if($this->hasAttribute($model, $field)){
                            $temp[$key] = $model->{$field};
                        }
                    }
                    $output[$name] = $temp;
                } else {
                    if($this->hasAttribute($model, $fields)){
                        $output[$name] = $model->{$fields};
                    }
                }
            }
        }

        return $output;
    }

    /**
     * Check if model has the attribute
     *
     * @param \App\Model\CModelInterface $model
     * @param string $attribute
     *
     * @return array
     */
    protected function hasAttribute($model, $attribute){
        return array_key_exists($attribute, $model->getAttributes());
    }
}
