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
                        return $this->returnFailureResponse();
                    }
                    break;
                case 'string':
                    if (false === is_string($args[$index])) {
                        return $this->returnFailureResponse();
                    }
                    break;
                case 'float':
                    if (false === is_float($args[$index])) {
                        return $this->returnFailureResponse();
                    }
                    break;
                case 'bool':
                    if (false === is_bool($args[$index])) {
                        return $this->returnFailureResponse();
                    }
                    break;
                case 'array':
                    if (false === is_array($args[$index])) {
                        return $this->returnFailureResponse();
                    }
                    break;
            }
        }
      
        $classObj = $reflector->newInstanceArgs($constructorArgs);

        $reflectionMethod = new ReflectionMethod($class, $method);
        return $reflectionMethod->invokeArgs($classObj, $args);
    }
    
    /**
     * returnFailureResponse
     *
     * @return mixed
     */
    private function returnFailureResponse()
    {
        switch ($this->returnType) {
            case self::TYPE_BOOL:
                return false;
                break;
            case self::TYPE_INT:
                return 0;
                break;
            case self::TYPE_STRING:
                return '';
                break;
            case self::TYPE_ARRAY:
                return [];
                break;
            
            default:
                return false;
                break;
        }
    }
    
    /**
     * setFailureReturnType
     *
     * @param  int $returnType
     * @return void
     */
    public function setFailureReturnType(int $returnType): void
    {
        $this->returnType = $returnType;
    }
}
