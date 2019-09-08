<?php
namespace concepture\core\base;

use concepture\core\helpers\ContainerHelper;
use concepture\core\validator\ProtectedValidator;

/**
 * Dto
 *
 * @author citizenzet <exgamer@live.ru>
 */
abstract class Dto extends BaseObject
{
    protected $errors = [];
    protected $protectedData = [];
    private $data = [];

    public function rules()
    {
        return [];
    }

    public function filters()
    {
        return [];
    }

    public function hasFilter($name)
    {
        $rule = $this->getRule($name);
        if ($rule === null){

            return false;
        }

        return true;
    }

    protected function getFilter($name)
    {
        $filters = $this->filters();
        if (! isset($filters[$name])){
            return null;
        }

        return $filters[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasRule($name)
    {
        $rule = $this->getRule($name);
        if ($rule === null){

            return false;
        }

        return true;
    }

    protected function getRule($name)
    {
        $rules = $this->rules();
        if (! isset($rules[$name])){
            return null;
        }

        return $rules[$name];
    }

    public function load($data)
    {
        $rules = $this->rules();
        $attributes = array_keys($rules);
        foreach ($attributes as $attribute){
            if (isset($data[$attribute])){
                continue;
            }
            $data[$attribute] = null;
        }
        foreach ($data as $name => $value){
            if (!$this->hasRule($name)){
                throw new \Exception("no rule for {$name}");
            }
            $filter = $this->getFilter($name);
            if ($filter){
                $value = $this->filter($name,$value, $filter);
            }
            $rule = $this->getRule($name);
            $this->validate($name, $value, $rule);
            $this->data[$name] = $value;
        }
    }

    protected function filter($name, $value, $filter)
    {
        if (is_array($filter)){
            if (isset($filter[0])){
                foreach ($filter as $f){
                    $value = $this->filterData($name, $value, $f);
                }

                return $value;
            }
        }
        return $this->filterData($name, $value, $filter);
    }

    protected function filterData($name, $value, $filter)
    {
        $filterObj = ContainerHelper::createObject($filter);

        return $filterObj->filter($value);
    }

    protected function validate($name, $value, $rule)
    {
        if (is_array($rule)){
            if (isset($rule[0])){
                foreach ($rule as $r){
                    $this->validateData($name, $value, $r);
                }

                return;
            }
        }
        $this->validateData($name, $value, $rule);
    }

    protected function validateData($name, $value, $rule)
    {
        $validator = ContainerHelper::createObject($rule);
        if ($validator->validate($value) == false){
            $this->errors[$name] = $validator->getMessage();
        }
        if ($validator instanceof ProtectedValidator){
            $this->protectedData[$name] = $name;
        }
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        if (! empty($this->errors)){

            return true;
        }

        return false;
    }

    /**
     * @return DataValidationErrors
     */
    public function getErrors()
    {
        $errors = new DataValidationErrors();
        $errors->setErrors($this->errors);

        return $errors;
    }

    /**
     * @return array
     */
    public function getData()
    {

        return $this->data;
    }

    /**
     * @return array
     */
    public function getDataForUpdate()
    {
        $data = $this->getData();
        $result = [];
        $attributes = array_keys($this->rules());
        foreach ($attributes as $attribute){
            if (in_array($attribute, $this->protectedData)){
                continue;
            }
            if (!isset($data[$attribute])){
                continue;
            }
            $result[$attribute] = $data[$attribute];
        }

        return $result;
    }
}