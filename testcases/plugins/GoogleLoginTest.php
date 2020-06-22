<?php

class GoogleLoginTest extends PluginBaseTest
{
    public const KEY_NAME = 'GoogleLogin';
    private $classObj = '';
    private $error = '';

    /**
     * testVerifyAccessToken - Return Array in case of missing required keys.
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
     * testVerifyAccessToken - Return Array in case of missing required keys.
     *
     * @dataProvider setInput
     * @param  array $toCurrencies
     * @return void
     */
    public function testVerifyAccessToken(string $accessToken, string $state = '', bool $expected = true): void
    {
        switch ($this->init()) {
            case false:
                echo $this->error;
                $this->assertEquals($expected, false);
                break;
            
            default:
                $result = $this->classObj->verifyAccessToken($accessToken, $state);
                $this->assertEquals($expected, $result);
                break;
        }
    }
        
    /**
     * setInput
     *
     * @return array
     */
    public function setInput(): array
    {
        // Returned false in case of invalid or missing Plugin Keys. Fail in case of opposite expectation.
        return [
            ['', '', false], // Return False in case of all input empty.
            ['abc', 'xyz', false], // Return False in case of all input empty
            ['EAAEViMZCbui8BAOgZB44rNUIWWbIRUtbJjzMI63nvW3iIcd1mAozgtfZCixUPMl6VC3YXA9ocjauZBxi5V6gFeijZBZABtaTY5Sy8Ym5ADZBfS70oG5cDaOa3X5HEDC5irEAPUnZCKfKklZAYmL2AUPnLuBT0TeQdsIDYl9r7kgGvAwZDZD'], // Return True in case state empty
            ['EAAEViMZCbui8BAOgZB44rNUIWWbIRUtbJjzMI63nvW3iIcd1mAozgtfZCixUPMl6VC3YXA9ocjauZBxi5V6gFeijZBZABtaTY5Sy8Ym5ADZBfS70oG5cDaOa3X5HEDC5irEAPUnZCKfKklZAYmL2AUPnLuBT0TeQdsIDYl9r7kgGvAwZDZD', 'ce5f965b037a2a71a316dd7cb2f94e2b'], // Return False in case of same access token been already used
        ];
    }
}
