<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class SubscriptionCartTest extends TestCase
{   
   
    /**
     * @dataProvider setDataForAdd
     */
    public function testAdd( $userId, $spPlanId, $expected )
    {
        $subscriptioncart = new SubscriptionCart($userId);
        $result = $subscriptioncart->add( $spPlanId );
        $this->assertEquals($expected, $result);
    }
    
    public function setDataForAdd()
    {
        return array(
            array(80, 7, true), 
        ); 
    }

    
    
}