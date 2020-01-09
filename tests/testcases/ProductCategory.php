<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class ProductCategory extends TestCase
{   
   
    /**
     * @dataProvider setCredentialsData
     */
    public function testSetLoginCredentials( $userId, $username, $email, $password, $active, $verified, $expected )
    {
        $user = new User();
        $user->setMainTableRecordId($userId);
        $result = $user->setLoginCredentials( $username, $email, $password, $active, $verified );
        $this->assertEquals($expected, $result);
    }
    
    public function setCredentialsData()
    {       
        return array(
            array('70000', 'dev70000', 'dev70000@dummyid.com', 'Test@123', null, null, true),//User details with inactive and unverified
        ); 
    }
    
    
    
    
}