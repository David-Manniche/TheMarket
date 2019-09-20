<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class UserAuthenticationTest extends TestCase
{    
    public function setUp() :void
    {
        $this->langId = 1;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    }
        
    /**
     * @dataProvider getGuestLoginData
     */
    public function testGuestLogin( $userEmail, $name, $ip, $expected )
    {
        $userAuth = new UserAuthentication();
        $result = $userAuth->guestLogin( $userEmail, $name, $ip );
        $this->assertEquals($expected, $result);
    }
    
    public function getGuestLoginData()
    {        
        return array(
            array('dev@dummyid.com', 'Dev', $_SERVER['REMOTE_ADDR'], false), // Existing User
            array('dev101@dummyid.com', 'Dev101', $_SERVER['REMOTE_ADDR'], true), // Existing Unverified User
            array('dev102@dummyid.com', 'Dev102', $_SERVER['REMOTE_ADDR'], true), // New User
        ); 
    }
    
    
    /**
     * @dataProvider getLoginData
     */
    public function testLogin( $userName, $password, $ip, $encryptPassword, $isAdmin, $tempUserId, $expected )
    {
        $userAuth = new UserAuthentication();
        $result = $userAuth->login( $userName, $password, $ip, $encryptPassword, $isAdmin, $tempUserId );
        $this->assertEquals($expected, $result);
    }
    
    public function getLoginData()
    {        
        return array(          
            array('wrong@dummyid.com', 'Cindy@123', $_SERVER['REMOTE_ADDR'], true, false, 0, false), // User With Wrong Email
            array('Cindy@dummyid.com', 'invalidpass', $_SERVER['REMOTE_ADDR'], true, false, 0, false), // User With Wrong Password
            array('wrong@dummyid.com', 'Wrong@123', $_SERVER['REMOTE_ADDR'], true, false, 0, false), // User With Wrong Email & Password
            array('demo@gmail.com', 'Demo@123', $_SERVER['REMOTE_ADDR'], true, false, 0, false), // User With Unverified account
            array('kanwar@dummyid.com', 'Kanwar@123', $_SERVER['REMOTE_ADDR'], true, false, 0, false), // User account deleted
            array('testshop@dummyid.com', 'Test@123', $_SERVER['REMOTE_ADDR'], true, false, 0, false), // User With deactivated account
            array('Cindy@dummyid.com', 'Cindy@123', $_SERVER['REMOTE_ADDR'], true, false, 0, true), // User With Valid Information
        ); 
    }
    
    /**
     * @dataProvider getUserData
     */
    public function testGetUserByEmailOrUserName( $username, $expected )
    {
        $userAuth = new UserAuthentication();
        $result = $userAuth->getUserByEmailOrUserName( $username);
        if(is_array($expected)){
            $this->assertIsArray($result);
        }else{
            $this->assertEquals($expected, $result);
        }
    }
    
    public function getUserData()
    {        
        return array(          
            array('wrong@dummyid.com', false), // User With Invalid Email
            array('wrong', false), // User With Invalid UserName
            array('Cindy@dummyid.com', array()), // User With Valid Email 
            array('Cindy', array()), // User With Valid UserName
        ); 
    }
    
    /**
     * @dataProvider userPwdResetRequest
     */
    public function testCheckUserPwdResetRequest( $userId, $expected )
    {
        $userAuth = new UserAuthentication();
        $result = $userAuth->checkUserPwdResetRequest( $userId );
        $this->assertEquals($expected, $result);
    }
    
    public function userPwdResetRequest()
    {        
        return array(
            array('wrong', false), // User with wrong userid 
            array(1, true), // User already request for reset password 
            array(999999999, false), // User do not request for reset password                     
        );
    }

    /**
     * @dataProvider addPwdRequest
     */
    public function testAddPasswordResetRequest($userData, $expected)
    {
        $userAuth = new UserAuthentication();
        $result = $userAuth->addPasswordResetRequest($userData);
        $this->assertEquals($expected, $result);
    }
    
    public function addPwdRequest()
    {        
        $token = UserAuthentication::encryptPassword(FatUtility::getRandomString(20));   
        return array(                      
            array(array('user_id' => 'test', 'token' => $token), false), // User with invalid userid
            array(array('user_id' => 1, 'token' => 'token545'), false), // User with invalid token
            array(array('user_id' => 'test', 'token' => 'token545'), false), // User with invalid userid and token
            array(array('user_id' => 1, 'token' => $token), false), // User already have reset password request
            array(array('user_id' => 2, 'token' => $token), true), // User with valid data
        );
    }
    
    public function testDeleteOldPasswordResetRequest()
    {
        $userAuth = new UserAuthentication();
        $result = $userAuth->deleteOldPasswordResetRequest( );
        $this->assertEquals(true, $result);
    }
 
    
}