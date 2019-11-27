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
            array(6, 'test', 'test', false), // Invalid selprodid and quantity
            array(6, 'test', 1, false), // Invalid selprodid and valid quantity
            array(6, 14, 'test', false), // Invalid quantity and valid selprodid
            array(6, 115, 2, true), // Deleted seller product
            array(6, 13, 4, true), // Deleted product catalog
            array(6, 19, 1, true), // Deactivate seller product
            array(6, 62, 1, true), // Deactivate product catalog
            array(6, 109, 1, true), // Product out of stock
            array(6, 14, 1, true), // Valid selprodid and quantity
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
    
    /**
     * @dataProvider setUpdateCart
    */
    public function testUpdateCart( $userId, $key, $quantity, $expected )
    {  
        $cart = new Cart($userId);
        $result = $cart->update($key, $quantity);
        $this->assertEquals($expected, $result);
    }
    
    public function setUpdateCart()
    {
        return array(
            array(6, 'test', 'test', false), // Invalid key and quantity
            array(6, 'test', 1, false), // Invalid key and valid quantity
            array(6, '901d654fbc401258caf0e68296921ecd', 2, true), // Valid key and quantity
        );
    }
    
    /**
     * @dataProvider userCartData
    */
    public function testGetCartData($userId, $expected){
        $result = Cart::getCartData($userId);
        $this->$expected($result);
    }
    
    public function userCartData()
    {
        return array(
            array(10, 'assertEmpty'),
            array(6, 'assertNotEmpty'),
        );
    }
    

}

