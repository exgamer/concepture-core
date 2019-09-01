<?php
namespace concepture\core\base;

/**
 * DataValidationErrors
 *
 * @author citizenzer <exgamer@live.ru>
 */
class DataValidationErrors extends BaseObject
{
    private $_errors = [];

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors($errors)
    {
        $this->_errors = $errors;
    }


}
