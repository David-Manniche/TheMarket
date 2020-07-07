<?php
class ShopTest extends YkModelTest
{
    private $class = 'Shop';
    /**
     * testIsActive
     *
     * @dataProvider dataIsActive
     * @param  mixed $expected
     * @param  mixed $userId
     * @param  mixed $shopId
     * @return void
     */
    public function testIsActive($expected, $shopId, $userId)
    {
        $result = $this->execute($this->class, [$shopId, $userId, 0], 'isActive');
        $this->assertEquals($expected, $result);
    }
        
    /**
     * shopActiveData
     *
     * @return array
     */
    public function dataIsActive()
    {
        return array(
            array(false, 'test', '4'), // Invalid shopId and Invalid userId
            array(false, 'test', 4), // Invalid shopId and valid userId
            array(false, '1', 'test'), // Invalid userId and Invalid shopId
            array(false, 1, 'test'), // Valid userId and Invalid shopId
            array(false, 'test', 'test'), // Invalid shopId and userId
            array(false, '1', '4'), // Invalid shopId and  Invalid userId as string
            array(true, 1, 4), // Valid shopId and userId
        );
    }
    
    /**
     * testGetName
     *
     * @dataProvider dataGetName
     * @param  mixed $expected
     * @param  mixed $shopId
     * @param  mixed $langId
     * @return void
     */
    public function testGetName($expected, $shopId, $langId)
    {
        $result = $this->execute($this->class, [], 'getName', [$shopId, $langId]);
        $this->assertEquals($expected, $result);
    }
    
    /**
     * dataGetName
     *
     * @return array
     */
    public function dataGetName()
    {
        return array(
            array(false, 'test', '1'), // Invalid shopId and Invalid langId
            array(false, 'test', 1), // Invalid shopId and valid langId
            array(false, '1', 'test'), // Invalid shopId and Invalid langId
            array(false, 1, 'test'), // Valid shopId and Invalid langId
            array(false, 'test', 'test'), // Invalid shopId and langId
            array(false, '1', '1'), // Invalid shopId and  Invalid langId as string
            array(false, 99, 1), // Valid shopId and langId   
            array("Kanwar's Shop", 1, 1), // Valid shopId and langId   
        );
    }
    
    /**
     * getRewriteCustomUrl
     *
     * @dataProvider dataGetRewriteCustomUrl
     * @param  mixed $expected
     * @param  mixed $shopId
     * @param  mixed $arr
     * @return void
     */
    public function testGetRewriteCustomUrl($expected, $shopId)
    {
        $result = $this->execute($this->class, [], 'getRewriteCustomUrl', [$shopId]);
        $this->assertEquals($expected, $result);
    }
    
    /**
     * dataGetRewriteCustomUrl
     *
     * @return void
     */
    public function dataGetRewriteCustomUrl()
    {
        return array(
            array(false, 'test'), // Invalid shopId
            array(false, '1'), // Invalid shopId            
            array('kanwar', 1), // Valid shopId
        );
    }
    
    /**
     * testSetFavorite
     *
     * @dataProvider dataSetFavorite
     * @param  mixed $expected
     * @param  mixed $shopId
     * @param  mixed $userId
     * @return void
     */
    public function testSetFavorite($expected, $shopId, $userId)
    {
        $result = $this->execute($this->class, [$shopId], 'setFavorite', [$userId]);
        $this->assertEquals($expected, $result);
    }

    public function dataSetFavorite()
    {
        return array(
            array(false, 'test', '4'), // Invalid shopId and Invalid userId
            array(false, 'test', 4), // Invalid shopId and valid userId
            array(false, '1', 'test'), // Invalid shopId and Invalid userId
            array(false, 1, 'test'), // Valid shopId and Invalid userId
            array(false, 'test', 'test'), // Invalid shopId and userId
            array(false, '1', '4'), // Invalid shopId and  Invalid userId as string
            array(true, 1, 4), // Valid shopId and userId
        );
    }
}
