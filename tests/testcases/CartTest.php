<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{   
   
    /**
     * @dataProvider setAddCart
     */
    public function testAdd( $userId, $selProdId, $qty, $expected )
    {
        $cart = new Cart($userId);
        $result = $cart->add($selProdId, $qty);
        $this->assertEquals($expected, $result);
    }
    
    public function setAddCart()
    {
        return array(
            array(24, 'test', 'test', false), // Invalid selprodid and quantity
            array(24, 'test', 1, false), // Invalid selprodid and valid quantity
            array(24, 14, 'test', false), // Invalid quantity and valid selprodid
            array(24, 14, 1, true), // Valid selprodid and quantity
        ); 
    }
    
    /**
     * @dataProvider setUpdateTempStock
     */
    public function testUpdateTempStockHold( $userId, $selProdId, $qty, $expected )
    {
        $cart = new Cart($userId);
        $result = $cart->updateTempStockHold($selProdId, $qty);
        $this->assertEquals($expected, $result);
    }
    
    public function setUpdateTempStock()
    {
        return array(
            array(24, 'test', 'test', false), // Invalid selprodid and quantity
            array(24, 'test', 1, false), // Invalid selprodid and valid quantity
            array(24, 14, 'test', false), // Invalid quantity and valid selprodid
            array(24, 14, 1, true), // Valid selprodid and quantity
        ); 
    }

    /**
     * @dataProvider setRemoveCart
     */
    public function testRemove( $userId, $key, $expected )
    {
        $cart = new Cart($userId);
        $result = $cart->remove($key);
        $this->assertEquals($expected, $result);
    }
    
    public function setRemoveCart()
    {
        return array(
            array('test', 'test', false), // Invalid user id and key
            array('test', md5('czo1OiJTUF8xNCI7'), false), // Invalid user id and valid key
            array(24, 'test', false), // Invalid key and valid user id
            array(24, md5('czo1OiJTUF8xNCI7'), true), // Valid user id and key
        );
    }

}

