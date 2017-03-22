<?php

declare(strict_types=1);

namespace Benrowe\Properties;

trait PropertyTrait
{
    private $propertyManager;

    public function __call($methodName, $params)
    {
        $pm = $this->getPropertyManager();
        if (!method_exists($pm, $methodName)) {
            throw new \Exception('Unknown method '.$methodName);
        }
        return call_user_func_array([$pm, $methodName], $params);
    }

    public function __get($key)
    {
        return $this->getPropertyManager()->getValue($key);
    }

    public function __set($key, $value)
    {
        return $this->getPropertyManager()->setValue($key, $value);
    }

    private function getPropertyManager()
    {
        if (!$this->propertyManager) {
            $this->propertyManager = new Manager;
        }
        return $this->propertyManager;
    }
}
