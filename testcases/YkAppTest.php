<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;

class YkAppTest extends TestCase
{
    /**
     * execute
     *
     * @param  string $class
     * @param  array $constructorArgs
     * @param  string $method
     * @param  array $args
     * @return void
     */
    protected function execute(string $class, array $constructorArgs, string $method, array $args)
    {
        //Target our class
        $reflector = new ReflectionClass($class);

        //Get the parameters of a method
        $parameters = $reflector->getMethod($method)->getParameters();

        foreach ($parameters as $index => $param) {
            if ($param->isOptional() && empty($args[$index])) {
                continue;
            }

            switch ($param->getType()) {
                case 'int':
                    if (false === is_int($args[$index])) {
                        return false;
                    }
                    break;
                case 'string':
                    if (false === is_string($args[$index])) {
                        return false;
                    }
                    break;
                case 'float':
                    if (false === is_float($args[$index])) {
                        return false;
                    }
                    break;
                case 'bool':
                    if (false === is_bool($args[$index])) {
                        return false;
                    }
                    break;
                case 'array':
                    if (false === is_array($args[$index])) {
                        return false;
                    }
                    break;
            }
        }
      
        $classObj = $reflector->newInstanceArgs($constructorArgs);

        $reflectionMethod = new ReflectionMethod($class, $method);
        return $reflectionMethod->invokeArgs($classObj, $args);
    }
}
