<?php

class GoogleLoginTest extends PluginBaseTest
{
    public const KEY_NAME = 'GoogleLogin';
    private $classObj = '';
    private $error = '';

    /**
     * init
     *
     * @return bool
     */
    private function init(): bool
    {
        $class = self::KEY_NAME;
        $this->langId = CommonHelper::getLangId();
        $this->classObj = new $class($this->langId);

        $this->classObj = PluginHelper::callPlugin($class, [$this->langId], $this->error, $this->langId);
        if (false === $this->classObj) {
            return false;
        }

        if (false === $resp = $this->classObj->init()) {
            $this->error = $this->classObj->getError();
            return false;
        }
        return true;
    }

    /**
     * testAuthenticate
     *
     * @dataProvider setAuthInput
     * @param  array $code
     * @return void
     */
    public function testAuthenticate(string $code, bool $expected = true): void
    {
        switch ($this->init()) {
            case false:
                echo $this->error;
                $this->assertEquals($expected, false);
                break;
            
            default:
                $result = $this->classObj->authenticate($code);
                if (false === $result) {
                    echo $this->classObj->getError();
                }
                $this->assertEquals($expected, $result);
                break;
        }
    }
        
    /**
     * setAuthInput
     *
     * @return array
     */
    public function setAuthInput(): array
    {
        // Returned false in case of invalid or missing Plugin Keys. Fail in case of opposite expectation.
        return [
            ['', false], // Return False in case of empty input.
            ['abc', false], // Return False in case of wrong input.
            ['4/1AGMhN5-Wob96JggkuwJhSCEW9tXH8ngw4G4JilTT4YWeAHaV0C4noApoBcjyclkanShIw5MoPeBrppRhBP5jME', false], // Return True in case valid Code
        ];
    }

    /**
     * testSetAccessToken
     *
     * @dataProvider setAccessTokenInput
     * @param  array $accessToken
     * @return void
     */
    public function testSetAccessToken(string $accessToken, bool $expected = true): void
    {
        switch ($this->init()) {
            case false:
                echo $this->error;
                $this->assertEquals($expected, false);
                break;
            
            default:
                $result = $this->classObj->setAccessToken($accessToken);
                if (false === $result) {
                    echo $this->classObj->getError();
                }
                $this->assertEquals($expected, $result);
                break;
        }
    }
        
    /**
     * setAuthInput
     *
     * @return array
     */
    public function setAccessTokenInput(): array
    {
        // Returned false in case of invalid or missing Plugin Keys. Fail in case of opposite expectation.
        return [
            ['', false], // Return False in case of empty input.
            ['abc', true], // Return true either access_Token is wrong.
            ['abc', false], // In case missing plugin keys.
            ['ya29.a0AfH6SMCnHrEFgUqi2G4P1GG5q1p-cXlIh7AwNyHODTDTtJu47hnl_IXdJIiKrut9hV5MYUZQQSzqTNyWItZUOejLYLSJEkhqgcyOfptidJCnz6Lcg0ufCfDrBoCTHIPKMXlaAz9AkRIIkLlipbS9gyoM_RkOR2xjbhk'], // Return True in case valid accessToken
        ];
    }
}
