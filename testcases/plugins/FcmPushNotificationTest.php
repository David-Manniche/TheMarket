<?php

class FcmPushNotificationTest extends YkPluginTest
{
    public const KEY_NAME = 'FcmPushNotification';

    /**
     * testRequestAccessToken
     *
     * @dataProvider setNotify
     * @param  array $code
     * @return void
     */
    public function testNotify($title, $message, $os, $data = [], $expected = Plugin::RETURN_TRUE)
    {
        $this->init();
        if (Plugin::RETURN_TRUE === $expected) {
            $deviceTokens = [
                'dk3NLiO2u1E:APA91bFvzOfGpl6shjmNSQYcQ5TQGpePXuHqC5KxbO0Ej8k9dezACGPdPxWxPjjPKMvWWtbc_jHe22YRCUY6TOQbsE5mG1Pw3X-NvCzDqolqNXpJU0nkSmfKsqyQwYkfF-J4xlyYxEEV',
                'fRgOw1XauQU:APA91bHUecfYKOZLAGjvLzJvKfvR8Y3gFTmHGiq9FDJS9dNWTTzN7SxK6GtuyERVgyZLbZ96879mCU3AWR5EhN21ewAVO8B6Y_xzev_mPLrOmtLsbTJ03W-yi6FeWLJOjgkBHRGqeFKH',
                'd3eOtP4gspU:APA91bFZ78Kj_cC0AR_2FvU9mhrYWYCRwIy6Pgz44Ie6vI5u_478j_7nFZuH5NKEZMucmVjAty2E5lrFYnotZHVz6AIm7UUkj9PqUjnbz4BetGmGWgqVuYFzFYquz0JWVK4560R3S2xa',
                'fDHtNBApNF0:APA91bEvSSMp6O9DZSO6Y3zNEvKgFmd07WY84Xyz7AilNIepX3gWdvbm70fNNgaDBQJMXwyI8LB5kO62Ajb1lMy-azAZboNpJ4Bfxub433lMRoOr3qUOhaJjslVImuKSvamyNFjqC6Zh'
            ];
            $this->classObj->setDeviceTokens($deviceTokens);
        }

        $result = $this->classObj->notify($title, $message, $os, $data);
        // CommonHelper::printArray($result);
        $this->assertEquals($expected, $result['status']);
    }
        
    /**
     * setNotify
     *
     * @return array
     */
    public function setNotify()
    {
        // Returned false in case of invalid or missing Plugin Keys. Fail in case of opposite expectation.
        return [
            ['', '', 0, [], Plugin::RETURN_FALSE], // Return False in case of all input empty and Invalid.
            ['Title1', 'Message1', 0, [], Plugin::RETURN_FALSE], // Return False if all value passed and os 0 but no device token set.
            ['Title2', 'Message2', 1, [], Plugin::RETURN_FALSE], // Return False if all value passed and os 0 but no device token set.
            ['Title3', 'Message3', 0, []], // Return true Either Invalid Device token or OS is 0 but function run successfully. Because It will tell number of successfully sent and number of failure.
            ['Title4', 'Message4', 1, []], // Return true Either Invalid Device token but function run successfully. Because It will tell number of successfully sent and number of failure.
            ['Title5', 'Message5', 1, ['test' => 'body']], // Return true Either Invalid Device token but function run successfully. Because It will tell number of successfully sent and number of failure.
        ];
    }
}
