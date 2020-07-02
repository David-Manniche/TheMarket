<?php

class InstagramLoginTest extends YkPluginTest
{
    public const KEY_NAME = 'InstagramLogin';

    /**
     * init
     *
     * @return void
     */
    public function init()
    {
        if (false === $this->classObj->init()) {
            $this->error = $this->classObj->getError();
            return false;
        }
        return true;
    }

    /**
     * testRequestAccessToken
     *
     * @dataProvider dataRequestAccessToken
     * @param  bool $expected
     * @param  mixed $code
     * @return void
     */
    public function testRequestAccessToken($expected, $code)
    {
        $response = $this->execute(self::KEY_NAME, [CommonHelper::getLangId()], 'requestAccessToken', [$code]);
        $this->assertEquals($expected, $response);
    }
        
    /**
     * dataRequestAccessToken
     *
     * @return array
     */
    public function dataRequestAccessToken(): array
    {
        // Returned false in case of invalid or missing Plugin Keys. Fail in case of opposite expectation.
        return [
            [false, ''], // Return False in case of all input empty.
            [false, 'abc'], // Return False in case of all input empty
            [false, 'AQDr4yahWlB7JuyWEJT-y5PCrw8zBlqM7mzMF5L4TXjjTJa9ldEv-nRx0XJ7ro_RxR-clLsDp_Vz_YjEk-cQh2yD9g4DDpIaFGnitBy1z6ZH9wQMo0bLZKim_tnVf0vmHIMpWl5hgNqt-E0zHHZ33rbhsupE4ufsohZZEIR1xmdl6RWvgI1ZFiuhFWT5Rldnjn2xT9Q6iCRFePf_Vs4qwbuczfJk49kwA9oQBk_LKazGTQ#_'], // Return false if expired 
        ];
    }

    /**
     * testRequestUserProfileInfo
     *
     * @dataProvider setAccessTokenInput
     * @param  bool $expected
     * @param  mixed $accessToken
     * @return void
     */
    public function testRequestUserProfileInfo($expected, $accessToken): void
    {
        $response = $this->execute(self::KEY_NAME, [CommonHelper::getLangId()], 'requestUserProfileInfo', [$accessToken]);
        $this->assertEquals($expected, $response);
    }
        
    /**
     * setAccessTokenInput
     *
     * @return array
     */
    public function setAccessTokenInput(): array
    {
        // Returned false in case of invalid or missing Plugin Keys. Fail in case of opposite expectation.
        return [
            [false, ''], // Return False in case of empty input.
            [false, 'abc'], // Return False in case of invalid input.
            [false, 'IGQVJYQ0FTUW5fdzdjWkVLM21CM2hQdDZAWOWRXU04wcTdrc2tmWElPd3RyZAkFhcGxybVBYWnJWODRGS045aVNJRWRDRWUxTEpBM05HUy1PZAUdHOVFMRWtPcVVvMDYxcUFoNGkwOWpVRmdHb0hkdU4wYUN1SzVpdEtucVRv'], // Return false if expired
        ];
    }
}
