<?php
class UserPrivilegeTest extends YkModelTest
{   
   
    /**
     * @dataProvider canSellerUpgradeOrDowngradePlanData
     */
    public function testCanSellerUpgradeOrDowngradePlan( $userId, $spPlanId, $langId, $expected )
    {
        $result = UserPrivilege::canSellerUpgradeOrDowngradePlan( $userId, $spPlanId, $langId );
        $this->assertEquals($expected, $result);
    }
    
    public function canSellerUpgradeOrDowngradePlanData()
    {
        return array(
            array('test', 'test', 1, false), //Invalid plan id and user id
            array('test', 7, 1, false), //Invalid user id but valid plan id            
            array(79, 'test', 1, false), //Invalid plan id but Valid user id
            array(79, 7, 1, true), //Valid user id and plan id
        ); 
    }
    
    
    
    
    
}