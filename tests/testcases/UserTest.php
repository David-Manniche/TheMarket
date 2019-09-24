<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
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
            array('70000', 'dev70000', 'dev70000@dummyid.com', 'Test@123', null, null, true),//User details with inactive and unverified parameter
            array('70001', 'dev70001', 'dev70001@dummyid.com', 'Test@123', 1, null, true),// User details with active and unverified parameter
            array('70002', 'dev70002', 'dev70002@dummyid.com', 'Test@123', null, 1, true),// User details with inactive and verified parameter
            array('70003', 'dev70003', 'dev70003@dummyid.com', 'Test@123', 1, 1, true), // User details with active and verified parameter
            array('70004', 'dev70004', 'dev70003@dummyid.com', 'Test@123', 1, 1, true), // User with existing email id
            array('70000', 'test', 'test@dummyid.com', 'Test@123', null, null, true),//User with existing user id
            array('wrong', 'wrong', 'wrong@dummyid.com', 'Test@123', null, null, false),//User with invalid user id
        ); 
    }
    
    
    /**
     * @dataProvider setNotifyAdminRegistration
     */
    public function testNotifyAdminRegistration( $data, $langId, $expected )
    {
        $user = new User();
        $result = $user->notifyAdminRegistration( $data, $langId );
        $this->assertEquals($expected, $result);
    }
    
    public function setNotifyAdminRegistration()
    {       
        return array(
            array(array('user_name' =>'cindy', 'user_username' =>'cindy', 'user_email' =>'cindy@dummyid.com', 'user_registered_initially_for' => 1), 1, true), //Valid parameters 
        ); 
    }
    
    /**
     * @dataProvider setGuestWelcomeEmail
     */
    public function testGuestUserWelcomeEmail( $data, $langId, $expected )
    {
        $user = new User();
        $result = $user->guestUserWelcomeEmail( $data, $langId );
        $this->assertEquals($expected, $result);
    }
    
    public function setGuestWelcomeEmail()
    {       
        return array(
            array(array('user_name' =>'cindy', 'user_email' =>'cindy@dummyid.com'), 1, true), //Valid parameters
        ); 
    }
    
    /**
     * @dataProvider loginPasswordData
     */
    public function testSetLoginPassword( $userId, $password, $expected )
    {
        $user = new User();
        $user->setMainTableRecordId($userId);
        $result = $user->setLoginPassword( $password );
        $this->assertEquals($expected, $result);
    }
    
    public function loginPasswordData()
    {
        return array(
            array('test', 'www@123', false), // Invalid user id
            array('70003', 'www@123', true), // User id exist
        );
    }
    
    
    
}