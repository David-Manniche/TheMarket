<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class SellerPackagesTest extends TestCase
{   
   
    /**
     * @dataProvider setvisiblePackages
     */
    public function testGetSellerVisiblePackages( $langId, $includeFreePackages)
    {
        $result = SellerPackages::getSellerVisiblePackages($langId, $includeFreePackages);                                           
        $this->assertIsArray($result);
    }
    
    public function setvisiblePackages()
    {
        return array(
            array(1, true), // Set include Free package to true
            array(1, false), // Set include Free package to false
        ); 
    }    

    
}

