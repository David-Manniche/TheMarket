<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class ShopTest extends TestCase
{   
   
    /**
     * @dataProvider shopActiveData
     */
    public function testIsShopActive( $userId, $shopId, $expected )
    {
        $shop = new Shop();
        $result = $shop->isShopActive( $userId, $shopId);
        $this->assertEquals($expected, $result);
    }
    
    public function shopActiveData()
    {       
        return array(
            array('test', '1', false), // Invalid userid and valid shopid
            array('4', 'test', true), // Invalid shopid and valid userid
            array('test', 'test', false), // Invalid userid and shopid
            array('4', '1', true), // Valid userid and shopid
        ); 
    }
    
    
    
    
    
}