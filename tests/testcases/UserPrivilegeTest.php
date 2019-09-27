<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class UserPrivilegeTest extends TestCase
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
            array('test', 7, 1, false), //Invalid user id and valid plan id            
            array('test', 'test', 1, false), //Invalid plan id and user id
            array(79, 7, 1, true), //Valid user id and plan id
            array(79, 'test', 1, false), //Invalid plan id and Valid user id
        ); 
    }
    
    
    
    
    
}