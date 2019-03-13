<?php

namespace App\Helper;

use Respect\Validation\Exceptions\NestedValidationException;

class CValidator {
    
    /**
     * Error messages
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Validate array based on provided rules
     *
     * @param array $values
     * @param array $rules
     *
     * @return array
     */
    public function validate(array $values, array $rules)
    {
        foreach ($rules as $field => $rule) {
            try {
                $rule->setName($field)->assert($this->getValue($values, $field));
            } catch (NestedValidationException $e) {
                array_push($this->errors, 
                implode( "|", $e->getMessages() ));
            }
        }

        return $this;
    }

    /**
     * get the value of the array
     *
     * @param array $values
     * @param string $field
     *
     * @return string|null
     */
    public function getValue($values, $field)
    {
        return isset($values[$field]) ? $values[$field] : null;
    }

     /**
     * Return all validations errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Set error messages
     *
     * @param array $errors
     * 
     * @return void
     */ 
    public function setErrors($errors)
    {
        if(is_array($errors)){
            $this->errors = array_merge($this->errors, $errors);
        } else {
            array_push($this->errors, $errors);
        }        
    }

     /**
     * Check if valid
     * 
     * @return boolean
     */ 
    public function isValid($data=null)
    {
        return empty($this->getErrors()) ? true: false;
    }
}