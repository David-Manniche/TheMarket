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
    private $result = '';
    private $error = '';
    public $reflectionClassInstance = '';

    public $langId = CONF_LANG_ID;

    /**
     * execute
     *
     * @param  string $class
     * @param  array $constructorArgs
     * @param  string $method
     * @param  array $args
     * @return mixed
     */
    protected function execute(string $class, array $constructorArgs, string $method, array $args = [])
    {
        //Target our class
        $reflectionClass = new ReflectionClass($class);

        //Get the parameters of a constructor
        $reflectionClassParam = $reflectionClass->getConstructor()->getParameters();
        $reflectMethod = $reflectionClass->getMethod($method);
        
        if (!$reflectMethod->isStatic()) {
            $invalidParam = $this->validateParamType($reflectionClassParam, $constructorArgs);

            if (true === $invalidParam) {
                return false;
            }
        }
        
        //Get the parameters of a method
        $reflectionParam = $reflectMethod->getParameters();
        
        $invalidParam = $this->validateParamType($reflectionParam, $args);

        if (true === $invalidParam) {
            return false;
        }
       
        
       
        if (method_exists($this, 'init') && false === $this->init()) {
            return false;
        }

        if (!$reflectMethod->isStatic()) {
            $reflectionClass = $reflectionClass->newInstanceArgs($constructorArgs);
        }
        
        $reflectionMethod = new ReflectionMethod($class, $method);
        $this->result = $reflectionMethod->invokeArgs($reflectionClass, $args);
        
        return $this->returnResponse();
    }
    
    /**
     * validateParamType
     *
     * @param  array $reflectionParam
     * @param  array $args
     * @return bool
     */
    private function validateParamType(array $reflectionParam, array $args) :bool
    {
        $invalidParam = false;
        foreach ($reflectionParam as $index => $param) {
            $paramValue = (array_key_exists($index, $args)) ? $args[$index] : null;
            
            if ($param->isOptional() && null == $paramValue) {
                continue;
            }

            $paramName = $param->getName();
            $paramType = null;
            if (null != $param->getType()) {
                $reflectionType = $param->getType();
                $paramType = $reflectionType->getName();
            }
            
            switch ($paramType) {
                case 'int':
                    $invalidParam = (false === is_int($paramValue)) ;
                    break;
                case 'string':
                    $invalidParam = (false === is_string($paramValue));
                    break;
                case 'float':
                    $invalidParam = (false === is_float($paramValue));
                    break;
                case 'bool':
                    $invalidParam = (false === is_bool($paramValue));
                    break;
                case 'array':
                    $invalidParam = (false === is_array($paramValue));
                    break;
            }

            if (true === $invalidParam) {
                $msg = Labels::getLabel('MSG_INVALID_{PARAM}_ARGUMENT_TYPE_{WRONG-PARAM-TYPE}_EXPECTED_{PARAM-TYPE}', CommonHelper::getLangId());
                $replaceData = ['{PARAM}' => $paramName, '{WRONG-PARAM-TYPE}' => gettype($paramValue), '{PARAM-TYPE}' => $paramType];
                $this->error = CommonHelper::replaceStringData($msg, $replaceData);
                return $invalidParam;
            }
        }

        return $invalidParam;
    }
    
    /**
     * returnResponse
     *
     * @return mixed
     */
    private function returnResponse()
    {
        switch ($this->returnType) {
            case self::TYPE_ARRAY:
                return is_array($this->result);
                break;
            default:
                return $this->result;
                break;
        }
    }
    
    /**
     * expectedReturnType
     *
     * @param  int $returnType
     * @return void
     */
    public function expectedReturnType(int $returnType): void
    {
        $this->returnType = $returnType;
    }
        
    /**
     * getResult
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * getError
     *
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }
    
    /**
     * InsertDbData
     *
     * @param  string $table
     * @param  array $data
     * @return void
     */
    protected function InsertDbData(string $table, array $arr)
    {
        if(!empty($table) && !empty($arr)){
            foreach($arr as $data){
                FatApp::getDb()->insertFromArray($table, $data, false, array(), $data);
            }
        }
    }
    
}
