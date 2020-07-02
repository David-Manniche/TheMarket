<?php
class ShopTest extends YkModelTest
{
    /**
     * testIsShopActive
     *
     * @dataProvider shopActiveData
     * @param  bool $expected
     * @param  mixed $userId
     * @param  mixed $shopId
     * @return void
     */
    public function testIsShopActive($expected, $userId, $shopId)
    {
        $result = $this->execute('Shop', [], 'isShopActive', [$userId, $shopId]);
        $this->assertEquals($expected, $result);
    }
        
    /**
     * shopActiveData
     *
     * @return array
     */
    public function shopActiveData()
    {
        return array(
            array(false, 'test', '1'), // Invalid userid and valid shopid
            array(false, 'test', 1), // Invalid userid and valid shopid
            array(false, '4', 'test'), // Invalid shopid and valid userid
            array(false, 4, 'test'), // Invalid shopid and valid userid
            array(false, 'test', 'test'), // Invalid userid and shopid
            array(false, '4', '1'), // Valid userid and shopid
            array(true, 4, 1), // Valid userid and shopid
        );
    }
}
