<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class CartHistoryTest extends TestCase
{   
   
    /**
     * @dataProvider dataSaveCartHistory
     */
    public function testSaveCartHistory( $userId, $selProdId, $qty, $action, $expected )
    {
        $result = CartHistory::saveCartHistory( $userId, $selProdId, $qty, $action );
        $this->assertEquals($expected, $result);
    }
    
    public function dataSaveCartHistory()
    {       
        return array(
            array(125, 188, 4, 1, true), // Add data with valid parameters
            array(125, 188, 2, 2, true), // Update qty and action field with valid parameters
            array('test', 188, 4, 1, false), // Invalid userId
            array(125, 'test', 4, 1, false), // Invalid selProdId
            array(125, 188, 'test', 1, false), // Invalid quantity
            array(125, 188, 4, 'test', false), // Invalid action
        ); 
    }
    
    
    
}