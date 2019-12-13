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
            array(125, 188, 1, 1, true), // Valid parameters
        ); 
    }
    
    
    
}