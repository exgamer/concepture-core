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
            $rule = $this->getRule($name);
            $this->validate($name, $value, $rule);
            $this->data[$name] = $value;
        }
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

    public function hasErrors()
    {
        if (! empty($this->errors)){

            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function getData()
    {

        return $this->data;
    }

    public function getDataForUpdate()
    {
        $data = $this->getData();
        foreach ($data as $name => $value){
            if (!$this->hasRule($name)){
                throw new \Exception("no rule for {$name}");
            }
            $rule = $this->getRule($name);
            $this->validate($name, $value, $rule);
        }
        $result = [];
        $rules = $this->rules();
        foreach ($rules as $name => $rule){
            if (in_array($name, $this->protectedData)){
                continue;
            }
            $result[$name] = $data[$name];
        }

        return $result;
    }
}
