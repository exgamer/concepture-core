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
            $this->validate($name, $value);
            $this->{$name} = $value;
        }
    }

    protected function validate($name, $value)
    {
        if (is_array($value)){
            if (isset($value[0])){
                foreach ($value as $v){
                    $this->validateData($name, $v);
                }

                return;
            }
        }
        $this->validateData($name, $value);
    }

    protected function validateData($name, $value)
    {
        $validator = ContainerHelper::createObject($value);
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
}
