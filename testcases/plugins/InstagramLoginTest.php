<?php

class InstagramLoginTest extends PluginBaseTest
{
    public const KEY_NAME = 'InstagramLogin';

    /**
     * testRequestAccessToken
     *
     * @dataProvider setCodeInput
     * @param  array $code
     * @return void
     */
    public function testRequestAccessToken(string $code, bool $expected = true): void
    {
        switch ($this->init()) {
            case false:
                echo $this->error;
                $this->assertEquals($expected, false);
                break;
            
            default:
                $result = $this->classObj->requestAccessToken($code);
                $this->assertEquals($expected, $result);
                break;
        }
    }
        
    /**
     * setCodeInput
     *
     * @return array
     */
    public function setCodeInput(): array
    {
        // Returned false in case of invalid or missing Plugin Keys. Fail in case of opposite expectation.
        return [
            ['', false], // Return False in case of all input empty.
            ['abc', false], // Return False in case of all input empty
            ['AQDr4yahWlB7JuyWEJT-y5PCrw8zBlqM7mzMF5L4TXjjTJa9ldEv-nRx0XJ7ro_RxR-clLsDp_Vz_YjEk-cQh2yD9g4DDpIaFGnitBy1z6ZH9wQMo0bLZKim_tnVf0vmHIMpWl5hgNqt-E0zHHZ33rbhsupE4ufsohZZEIR1xmdl6RWvgI1ZFiuhFWT5Rldnjn2xT9Q6iCRFePf_Vs4qwbuczfJk49kwA9oQBk_LKazGTQ#_'], // Return True 
            ['AQDr4yahWlB7JuyWEJT-y5PCrw8zBlqM7mzMF5L4TXjjTJa9ldEv-nRx0XJ7ro_RxR-clLsDp_Vz_YjEk-cQh2yD9g4DDpIaFGnitBy1z6ZH9wQMo0bLZKim_tnVf0vmHIMpWl5hgNqt-E0zHHZ33rbhsupE4ufsohZZEIR1xmdl6RWvgI1ZFiuhFWT5Rldnjn2xT9Q6iCRFePf_Vs4qwbuczfJk49kwA9oQBk_LKazGTQ#_', false], // Return False if already use
        ];
    }

    /**
     * testRequestUserProfileInfo
     *
     * @dataProvider setAccessTokenInput
     * @param  array $accessToken
     * @return void
     */
    public function testRequestUserProfileInfo(string $accessToken, bool $expected = true): void
    {
        switch ($this->init()) {
            case false:
                echo $this->error;
                $this->assertEquals($expected, false);
                break;
            
            default:
                $result = $this->classObj->requestUserProfileInfo($accessToken);
                $this->assertEquals($expected, $result);
                break;
        }
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
            ['', false], // Return False in case of empty input.
            ['abc', false], // Return False in case of invalid input.
            ['IGQVJYQ0FTUW5fdzdjWkVLM21CM2hQdDZAWOWRXU04wcTdrc2tmWElPd3RyZAkFhcGxybVBYWnJWODRGS045aVNJRWRDRWUxTEpBM05HUy1PZAUdHOVFMRWtPcVVvMDYxcUFoNGkwOWpVRmdHb0hkdU4wYUN1SzVpdEtucVRv'], // Return True if valid
        ];
    }
}
