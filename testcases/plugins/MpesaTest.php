<?php

class MpesaTest extends YkPluginTest
{
    public const KEY_NAME = 'Mpesa';

    /**
     * init
     *
     * @return void
     */
    public function init()
    {
        $userId = UserAuthentication::getLoggedUserId(true);
        if (false === $this->classObj->init($userId)) {
            $this->error = $this->classObj->getError();
            return false;
        }
        return true;
    }

    /**
     * testCallbackUrl
     *
     * @dataProvider dataCallbackUrl
     * @param  mixed $orderId
     * @return void
     */
    public function testCallbackUrl($orderId)
    {
        $response = $this->execute(self::KEY_NAME, [CommonHelper::getLangId()], 'callbackUrl', [$orderId]);
        $this->assertIsString($response);
    }

    /**
     * dataCallbackUrl
     *
     * @return array
     */
    public function dataCallbackUrl(): array
    {
        return [
            [''], // Return url string in case of all input empty.
            ['abc'], // Return url string in case of invalid order id
            [123], // Return url string if numeric variable passed
            ['O1607946732'], // Return url string if correct order id passed
        ];
    }

    /**
     * testGenerateToken
     *
     * @dataProvider dataGenerateToken
     * @return void
     */
    public function testGenerateToken(): void
    {
        $response = $this->execute(self::KEY_NAME, [CommonHelper::getLangId()], 'generateToken');
        $this->assertIsBool($response);
    }

    /**
     * dataGenerateToken
     *
     * @return array
     */
    public function dataGenerateToken(): array
    {
        return [
            [], // Return bool(true/false) if token generated or not.
        ];
    }

    /**
     * testGetToken
     *
     * @dataProvider dataGetToken
     * @return void
     */
    public function testGetToken(): void
    {
        $response = $this->execute(self::KEY_NAME, [CommonHelper::getLangId()], 'getToken');
        $this->assertIsString($response);
    }

    /**
     * dataGetToken
     *
     * @return array
     */
    public function dataGetToken(): array
    {
        return [
            [], // Return string type if response set or not.
        ];
    }

    /**
     * testSTKPushSimulation
     *
     * @dataProvider dataSTKPushSimulation
     * @param  mixed $orderId
     * @param  mixed $amount
     * @param  mixed $customerPhone
     * @param  mixed $transactionDesc
     * @return void
     */
    public function testSTKPushSimulation($expected, $orderId, $amount, $customerPhone, $transactionDesc): void
    {
        $response = $this->execute(self::KEY_NAME, [CommonHelper::getLangId()], 'STKPushSimulation', [$orderId, $amount, $customerPhone, $transactionDesc]);
        $this->assertEquals($expected, $response);
    }

    /**
     * dataSTKPushSimulation
     *
     * @return array
     */
    public function dataSTKPushSimulation(): array
    {
        return [
            [false, 'O1607946732', '80', '918053250813', 'test'], // Return false if all invalid values are passed
            [false, 'O1607946732', '-80', 918053250813, 'test'], // Return false if all invalid values are passed
            [false, 'O1607946732', -80, 'abc', 'test'], // Return false if all invalid values are passed
            [false, 'O1607946732', '0', '918053250813', 'test'], // Return false if all invalid values are passed
            [false, 'O1607946732', '80', '254708374149', 'test'], // Return false if all values are correct. But dependent upon live dynamic created data.
        ];
    }
    
    /**
     * testSTKPushQuery
     *
     * @dataProvider dataSTKPushQuery
     * @param  mixed $checkoutRequestID
     * @return void
     */
    public function testSTKPushQuery($checkoutRequestID): void
    {
        $response = $this->execute(self::KEY_NAME, [CommonHelper::getLangId()], 'STKPushQuery', [$checkoutRequestID]);
        $this->assertIsBool($response);
    }

    /**
     * dataSTKPushQuery
     *
     * @return array
     */
    public function dataSTKPushQuery(): array
    {
        return [
            ['O1607946732'], // Return false if all invalid values are passed
        ];
    }
}
