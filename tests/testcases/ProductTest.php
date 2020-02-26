<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{   
   
    /**
     * @dataProvider setProductData
     */
    public function testSaveProductData( $data, $expected )
    {
        $prod = new Product();    
        $prod->setMainTableRecordId($data['product_id']);
        $result = $prod->saveProductData($data);
        $this->assertEquals($expected, $result);
    }
    
    public function setProductData()
    {            
        $data = array('product_id' => 0, 'product_identifier' => 'test unit product', 'product_type' => 1, 'product_brand_id' => 111, 'product_min_selling_price' => 280, 'product_approved' => 1, 'product_active' => 1, 'product_added_by_admin_id' => 0, 'product_model' => 'test prod', 'product_featured' => 1, 'product_cod_enabled' => 0, 'product_dimension_unit' => 2, 'product_length' => 20, 'product_width' => 30, 'product_height' => 40, 'product_weight_unit' => 2, 'product_weight' => 10); // Add new product
        
        $data1 = array('product_id' => 0, 'product_identifier' => 'test unit product', 'product_type' => 2, 'product_brand_id' => 113, 'product_min_selling_price' => 150, 'product_approved' => 1, 'product_active' => 1, 'product_added_by_admin_id' => 0, 'product_model' => 'digi', 'product_featured' => 0); // Duplicate product Identifier
        
        $data2 = array('product_id' => 111, 'product_identifier' => 'fastfood', 'product_type' => 1, 'product_brand_id' => 111, 'product_min_selling_price' => 280, 'product_approved' => 1, 'product_active' => 1, 'product_added_by_admin_id' => 0, 'product_model' => 'test prod', 'product_featured' => 1, 'product_cod_enabled' => 0, 'product_dimension_unit' => 2, 'product_length' => 5, 'product_width' => 6, 'product_height' => 7, 'product_weight_unit' => 2, 'product_weight' => 8); // Update existing product
        
        return array(
            array($data, true),
            array($data1, false),
            array($data2, true),
        );
    }
    
    /**
     * @dataProvider setProductLangData
     */
    public function testSaveProductLangData( $data, $mainTableRecordId, $expected )
    {
        $prod = new Product();    
        $prod->setMainTableRecordId( $mainTableRecordId );
        $result = $prod->saveProductLangData($data);
        $this->assertEquals($expected, $result);
    }
    
    public function setProductLangData()
    {  
        $data = array(
            'product_name' => array('1' => 'test unit product'), 
            'product_description_1' => 'test unit product decsription in english for first editor', 
            'product_youtube_video' => array('1' => 'video url in english'),                     
        );
        
        return array(
            array($data, 0, false),     // Product id with 0
            array($data, 140, true),    // Update existing product
        );
    }
    
    /**
     * @dataProvider setProductCategory
     */
    public function testSaveProductCategory( $categoryId, $mainTableRecordId, $expected )
    {
        $prod = new Product();    
        $prod->setMainTableRecordId( $mainTableRecordId );
        $result = $prod->saveProductCategory($categoryId);
        $this->assertEquals($expected, $result);
    }
    
    public function setProductCategory()
    {  
        return array(
            array(0, 0, false), //Product id and category id is 0
            array(0, 140, false), //Category id is 0
            array(170, 0, false), //Product id is 0
            array(170, 140, true), // Valid category id and product id
        );
    }
    
    
    /**
     * @dataProvider setProductTax
     */
    public function testSaveProductTax( $taxId, $mainTableRecordId, $userId, $expected )
    {
        $prod = new Product();    
        $prod->setMainTableRecordId( $mainTableRecordId );
        $result = $prod->saveProductTax($taxId, $userId);
        $this->assertEquals($expected, $result);
    }
    
    public function setProductTax()
    {  
        return array(
            array(0, 0, 0, false), //Product id and tax id is 0
            array(0, 140, 0, false), //Tax id is 0
            array(4, 0, 0, false), //Product id is 0
            array(4, 140, 0, true), // Valid category id and product id
        );
    }
    
    
}