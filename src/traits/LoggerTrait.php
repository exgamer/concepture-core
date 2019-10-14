<?php
namespace concepture\core\traits;

/**
 * Треит содержащий методы для рабоыт с логгером
 *
 * Trait LoggerTrait
 * @author citizenzet <exgamer@live.ru>
 */
trait LoggerTrait
{
    protected $logger;

    /**
     * @return LoggerInterface
     */
    protected function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param mixed $logger
     */
    protected function setLogger($logger): void
    {
        $this->logger = $logger;
    }

    protected function critical($message, $array=[])
    {
        $this->log($message, $array, "critical");
    }

    protected function warning($message, $array=[])
    {
        $this->log($message, $array, "warning");
    }

    protected function error($message, $array=[])
    {
        $this->log($message, $array, "error");
    }

    protected function info($message, $array=[])
    {
        $this->log($message, $array, "info");
    }

    protected function debug($message, $array=[])
    {
        $this->log($message, $array, "debug");
    }

    protected function log($message, $array, $level = "info")
    {
        $context = [];
        if (method_exists($this,'extendLogContext')){
            $context = $this->extendLogContext($context);
        }
        foreach ($array as $k => $a){
            $context[$k] = $a;
        }
        if (method_exists($this,'extendLogMessage')){
            $message = $this->extendLogMessage($message);
        }


        $this->getLogger()->{$level}($message, $context);
    }
}