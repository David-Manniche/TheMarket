<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class EmailHandlerTest extends TestCase
{   
   
    /**
     * @dataProvider sendPasswordLinkEmail
     */
    public function testSendForgotPasswordLinkEmail( $langId, $data, $expected )
    {
        $email = new EmailHandler();
        $result = $email->sendForgotPasswordLinkEmail( $langId, $data );
        $this->assertEquals($expected, $result);
    }
    
    public function sendPasswordLinkEmail()
    {       
        return array(
            array(1, array('user_name' =>'cindy', 'credential_email' =>'cindy@dummyid.com', 'link' =>'test'), true), //Valid data 
            array(1, array('user_name' =>'wrong', 'credential_email' =>'wrong@dummyid.com', 'link' =>'wrong'), true), //Invalid data I 
        ); 
    }
    
    
    
}