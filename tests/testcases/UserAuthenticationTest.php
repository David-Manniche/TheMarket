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
            array('wrong@dummyid.com', 'wrong@123', $_SERVER['REMOTE_ADDR'], true, false, 0, false), // User With Incorrect Information
            array('demo@gmail.com', 'Demo@123', $_SERVER['REMOTE_ADDR'], true, false, 0, false), // User With Unverified account
            array('kanwar@dummyid.com', 'Kanwar@123', $_SERVER['REMOTE_ADDR'], true, false, 0, false), // User account deleted
            array('testshop@dummyid.com', 'Test@123', $_SERVER['REMOTE_ADDR'], true, false, 0, false), // User With deactivated account
            array('Cindy@dummyid.com', 'Cindy@123', $_SERVER['REMOTE_ADDR'], true, false, 0, true), // User With Correct Information
        ); 
    }
    
    /**
     * @dataProvider getUserData
     */
    public function testGetUserByEmailOrUserName( $username, $isActive, $isVerfied, $addDeletedCheck, $expected )
    {
        $userAuth = new UserAuthentication();
        $result = $userAuth->getUserByEmailOrUserName( $username, $isActive, $isVerfied, $addDeletedCheck );
        if(is_array($expected)){
            $this->assertIsArray($result);
        }else{
            $this->assertEquals($expected, $result);
        }
    }
    
    public function getUserData()
    {        
        return array(          
            array('wrong@dummyid.com', true, true, true, false), // User With Incorrect Information
            array('Cindy@dummyid.com', true, true, true, array()), // User With Correct Information
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
            array(4, true), // User already request for reset password
            array(999999999, false), // User do not make any request for reset password                        
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
            array(array('user_id' => 'test', 'token' => $token), false), // User with invalid data
            array(array('user_id' => 1, 'token' => $token), false), // User already exist
            array(array('user_id' => 2, 'token' => $token), true), // User with valid data
        );
    }
 
    
}