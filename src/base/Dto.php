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
        foreach ($data as $name => $value){
            if (!$this->hasRule($name)){
                throw new \Exception("no rule for {$name}");
            }
            $rule = $this->getRule($name);
            $this->validate($name, $value, $rule);
            $this->{$name} = $value;
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
        if ($validator->validate($value) === false){
            $this->errors[$name] = $validator->getMessage();
        }
        if ($validator instanceof ProtectedValidator){
            $this->protectedData[] = $name;
        }
    }

    public function hasErrors()
    {
        if (empty($this->errors)){

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
        $result = [];
        $rules = $this->rules();
        foreach ($rules as $name => $rule){
            $result[$name] = $this->{$name};
        }

        return $result;
    }

    public function getDataForUpdate()
    {
        $data = $this->getData();
        foreach ($data as $name => $value){
            if (!$this->hasRule($name)){
                throw new \Exception("no rule for {$name}");
            }
            $this->validate($name, $value);
        }
        $result = [];
        $rules = $this->rules();
        foreach ($rules as $name => $rule){
            if (isset($this->protectedData[$name])){
                continue;
            }
            $result[$name] = $this->{$name};
        }

        return $result;
    }

    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } elseif (method_exists($this, 'get' . $name)) {
            throw new \Exception('Setting read-only property: ' . get_class($this) . '::' . $name);
        }
    }

    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } elseif (method_exists($this, 'set' . $name)) {
            throw new \Exception('Getting write-only property: ' . get_class($this) . '::' . $name);
        }
    }
}
