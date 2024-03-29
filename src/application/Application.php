<?php

namespace concepture\core\application\base;

use concepture\base\Component;
use concepture\core\helpers\ArrayHelper;
use concepture\core\helpers\ContainerHelper;
use concepture\domain\Domain;

class Application extends Component
{
    public $domains = [];

    private $_domains;

    public function getDomains()
    {
        return $this->domains;
    }

    public function setDomains($domains)
    {
        $this->domains = $domains;
    }

    private function setDomain($name, Domain $domain)
    {
        $this->_domains[$name] = $domain;
    }

    private function getDomain($key)
    {
        if (isset($this->_domains[$key])){
            return $this->_domains[$key];
        }
        $domains = $this->getDomains();
        $config = ArrayHelper::getValue($domains, $key);
        if ($config === null){
            return $config;
        }
        $domain = ContainerHelper::createObject($config);
        if ($domain === null){
            return $domain;
        }
        $this->setDomain($key, $domain);

        return $domain;
    }

    public function __get($name)
    {
        $domain = $this->getDomain($name);
        if ($domain){
            return $domain;
        }

        return parent::__get($name);
    }

}

