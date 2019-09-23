<?php
namespace concepture\core\storage;

use concepture\core\base\TransactionInterface;
use concepture\core\helpers\ArrayHelper;
use concepture\core\helpers\ContainerHelper;
use Exception;

abstract class Aggregator extends Storage implements TransactionInterface
{
    private $_storages;

    protected abstract function storages();

    private function setStorage($name, Storage $storage)
    {
        $this->_storages[$name] = $storage;
    }

    private function getStorage($key)
    {
        if (isset($this->_storages[$key])){
            return $this->_storages[$key];
        }
        $storages = static::storages();
        $config = ArrayHelper::getValue($storages, $key);
        if ($config === null){
            return $config;
        }
        $className = "";
        $arguments = [];
        if (is_string($config)){
            $className = $config;
        }
        if (is_array($config)){
            $className = ArrayHelper::getValue($config, 0);
            $arguments = ArrayHelper::getValue($config, 1, []);
        }
        $arguments = ArrayHelper::merge($arguments, [
            'connection' => $this->getConnection()
        ]);
        $storageConfig = [
            $className,
            $arguments
        ];
        $storage = ContainerHelper::createObject($storageConfig);
        if ($storage === null){
            return $storage;
        }
        $this->setStorage($key, $storage);

        return $storage;
    }

    public function __get($name)
    {
        $storage = $this->getStorage($name);
        if ($storage){
            return $storage;
        }

        return parent::__get($name);
    }

    public function insert($params)
    {
        try{
            $this->transactionBegin();
            $this->insertAction($params);
            $this->transactionCommit();
        }catch (Exception $e){
            $this->transactionRollback();
            throw $e;
        }
    }

    protected abstract function insertAction($params);

    public function updateById($id, $params)
    {
        $condition = [
            'id' => $id
        ];

        return $this->update($params, $condition);
    }

    public function update($params, $condition)
    {
        try{
            $this->transactionBegin();
            $this->updateAction($params);
            $this->transactionCommit();
        }catch (Exception $e){
            $this->transactionRollback();
            throw $e;
        }
    }

    protected abstract function updateAction($params, $condition);

    public function deleteById($id)
    {
        $condition = [
            'id' => $id
        ];

        return $this->delete($condition);
    }

    public function delete($condition)
    {
        try{
            $this->transactionBegin();
            $this->deleteAction($condition);
            $this->transactionCommit();
        }catch (Exception $e){
            $this->transactionRollback();
            throw $e;
        }
    }

    protected abstract function deleteAction($condition);

    protected function transactionBegin()
    {
        $this->getConnection()->beginTransaction();
    }
    protected function transactionCommit()
    {
        $this->getConnection()->commit();
    }
    protected function transactionRollback()
    {
        $this->getConnection()->rollBack();
    }
}