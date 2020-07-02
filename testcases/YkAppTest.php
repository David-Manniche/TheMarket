<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;

class YkAppTest extends TestCase
{
    public const TYPE_BOOL = 1;
    public const TYPE_INT = 2;
    public const TYPE_STRING = 3;
    public const TYPE_ARRAY = 4;

    private $returnType = self::TYPE_BOOL;
    private $response = '';

    /**
     * execute
     *
     * @param  string $class
     * @param  array $constructorArgs
     * @param  string $method
     * @param  array $args
     * @return void
     */
    protected function execute(string $class, array $constructorArgs, string $method, array $args): bool
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
                        return $this->returnResponse();
                    }
                    break;
                case 'string':
                    if (false === is_string($args[$index])) {
                        return $this->returnResponse();
                    }
                    break;
                case 'float':
                    if (false === is_float($args[$index])) {
                        return $this->returnResponse();
                    }
                    break;
                case 'bool':
                    if (false === is_bool($args[$index])) {
                        return $this->returnResponse();
                    }
                    break;
                case 'array':
                    if (false === is_array($args[$index])) {
                        return $this->returnResponse();
                    }
                    break;
            }
        }
      
        $classObj = $reflector->newInstanceArgs($constructorArgs);

        $reflectionMethod = new ReflectionMethod($class, $method);
        $this->response = $reflectionMethod->invokeArgs($classObj, $args);
        return $this->returnResponse();
    }
    
    /**
     * returnResponse
     *
     * @return bool
     */
    private function returnResponse(): bool
    {
        if (empty($this->response)) {
            return false;
        }

        switch ($this->returnType) {
            case self::TYPE_BOOL:
                return is_bool($this->response);
                break;
            case self::TYPE_INT:
                return is_int($this->response);
                break;
            case self::TYPE_STRING:
                return is_string($this->response);
                break;
            case self::TYPE_ARRAY:
                return is_array($this->response);
                break;
            
            default:
                return false;
                break;
        }
    }
    
    /**
     * expectedReturnType
     *
     * @param  int $returnType
     * @return void
     */
    protected function expectedReturnType(int $returnType): void
    {
        $this->returnType = $returnType;
    }
        
    /**
     * getResponse
     *
     * @return mixed
     */
    protected function getResponse()
    {
        return $this->response;
    }
}
