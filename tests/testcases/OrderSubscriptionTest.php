<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class OrderSubscriptionTest extends TestCase
{   
   
    /**
     * @dataProvider setDataFreeSubscription
     */
    public function testCanUserBuyFreeSubscription($langId, $userId, $expected)
    {
        $result = OrderSubscription::canUserBuyFreeSubscription($langId, $userId);                                           
        $this->assertEquals($expected, $result);
    }
    
    public function setDataFreeSubscription()
    {
        return array(
            array('test', 80, false), // Invalid langid and userid having subscription already
            array('test', 79, true), // Invalid langid and userid do not have subscription
            array(1, 80, false), // Valid langid and userid having subscription already
            array(1, 79, true), // Valid langid and userid do not have subscription
            array('test', 'test', false) // Invalid langid and userid
        ); 
    }    

    
}