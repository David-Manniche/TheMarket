<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class AbandonedCartTest extends TestCase
{   
   
    /**
     * @dataProvider dataSave
     */
    public function testSave( $userId, $selProdId, $qty, $action, $expected )
    {
        $result = AbandonedCart::save( $userId, $selProdId, $qty, $action );
        $this->assertEquals($expected, $result);
    }
    
    public function dataSave()
    {       
        return array(
            array(1, 50, 4, 1, true), // Add data with valid parameters
            array(1, 50, 2, 2, true), // Update qty and action field with valid parameters
            array('test', 50, 4, 1, false), // Invalid userId
            array(1, 'test', 4, 1, false), // Invalid selProdId
            array(1, 50, 'test', 1, false), // Invalid quantity
            array(1, 50, 4, 'test', false), // Invalid action
        ); 
    }
    
    /**
     * @dataProvider dataGetAbandonedCartList
     */
    public function testGetAbandonedCartList( $langId, $userId, $selProdId, $action, $page, $expected )
    {
        $abandonedCart = new AbandonedCart();
        $result = $abandonedCart->getAbandonedCartList($langId, $userId, $selProdId, $action, $page);
        $this->assertEquals($expected, count($result));
    }
    
    public function dataGetAbandonedCartList()
    {       
        return array(
            array(1, 0, 0, 0, 1, 13), // Get all records
            array(1, 'test', 'test', 'test', 'test', 13), // Get all records            
            array(1, 10, 0, 0, 1, 2), // Get records of userId = 10
            array(1, 0, 149, 0, 1, 2), // Get records of selProdId = 149
            array(1, 0, 0, 1, 1, 8), // Get records of action added
            array(1, 0, 0, 2, 1, 5), // Get records of action deleted
            array(1, 0, 0, 3, 1, 2), // Get records of action Purchased
            array(1, 10, 149, 0, 1, 0), // Get records of userId = 10 and selProdId = 149
            array(1, 10, 141, 0, 1, 1), // Get records of userId = 10 and selProdId = 141
            array(1, 10, 0, 1, 1, 2), // Get records of userId = 10 and action added
            array(1, 10, 0, 2, 1, 0), // Get records of userId = 10 and action deleted
            array(1, 10, 0, 3, 1, 0), // Get records of userId = 10 and action purchased
            array(1, 0, 149, 1, 1, 1), // Get records of selProdId = 149 and action added
            array(1, 0, 149, 2, 1, 1), // Get records of selProdId = 149 and action deleted
            array(1, 0, 149, 3, 1, 0), // Get records of selProdId = 149 and action purchased
        ); 
    }
    
    /**
     * @dataProvider dataGetAbandonedCartProducts
     */
    public function testGetAbandonedCartProducts( $langId, $page, $expected )
    {
        $abandonedCart = new AbandonedCart();
        $result = $abandonedCart->getAbandonedCartProducts($langId, $page);
        $this->assertEquals($expected, count($result));
    }
    
    public function dataGetAbandonedCartProducts()
    {       
        return array(
            array(1, 1, 11), // Get all records
            array(1, 'test', 11), // Get all records
            array(1, '2test', 0), // Get all records
        ); 
    }
    
    /**
     * @dataProvider dataUpdateDiscountNotification
     */
    public function testUpdateDiscountNotification( $userId, $selProdId, $expected )
    {
        $abandonedCart = new AbandonedCart();
        $result = $abandonedCart->updateDiscountNotification($userId, $selProdId);
        $this->assertEquals($expected, $result);
    }
    
    public function dataUpdateDiscountNotification()
    {       
        return array(
            array('test', 1, false), //Invalid userId
            array(10, 'test', false), //Invalid selProdId
            array(10, 1, true), //Valid userId and selProdId
            array(6, 116, true), //Valid userId and selProdId
        );
    }
    
    /**
     * @dataProvider dataUpdateReminderCount
    */
    public function testUpdateReminderCount( $userId, $selProdIds, $expected )
    {
        $abandonedCart = new AbandonedCart();
        $result = $abandonedCart->updateReminderCount($userId, $selProdIds);
        $this->assertEquals($expected, $result);
    }
    
    public function dataUpdateReminderCount()
    {       
        return array(
            array('test', array(44,116), false), //Invalid userId 
            array(6, 'test', false), //Invalid selProdIds
            array(6, array(44,116), true), //Valid userId and selProdIds           
        );
    }
    
    /**
     * @dataProvider dataSendDiscountEmail
     */
    public function testSendDiscountEmail( $langId, $userId, $action, $couponId, $selProdId, $expected )
    {
        $abandonedCart = new AbandonedCart();
        $result = $abandonedCart->sendDiscountEmail($langId, $userId, $action, $couponId, $selProdId);
        $this->assertEquals($expected, $result);
    }
    
    public function dataSendDiscountEmail()
    {       
        return array(
            array('test', 10, 1, 11, 47, false), //Invalid langId   
            array(1, 'test', 1, 11, 47, false), //Invalid userId 
            array(1, 10, 1, 'test', 47, false), //Invalid couponId 
            array(1, 10, 1, 11, 'test', false), //Invalid selProdId        
            array(1, 10, 5, 11, 47, false), //Invalid action not present in action array
            array(1, 10, 1, 11, 47, true), //Valid parameters
        );
    }

    
    
    
    
}