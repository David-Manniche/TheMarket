<?php
class ProductTest extends YkModelTest
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
    
    /**
     * @dataProvider setProductSellerShipping
     */
    public function testSaveProductSellerShipping( $mainTableRecordId, $prodSellerId, $psFree, $psCountryId, $expected )
    {
        $prod = new Product();    
        $prod->setMainTableRecordId( $mainTableRecordId );
        $result = $prod->saveProductSellerShipping($prodSellerId, $psFree, $psCountryId);
        $this->assertEquals($expected, $result);
    }
    
    public function setProductSellerShipping()
    {  
        return array(
            array(0, 0, 0, 0, 0, false), //Invalid Data
            array(140, 0, 1, 0, true), //Valid Data            
        );
    }
    
    /**
     * @dataProvider setProductSpecifications
     */
    public function testSaveProductSpecifications( $mainTableRecordId, $prodSpecId, $langId, $prodSpecName, $prodSpecValue, $prodSpecGroup, $expected )
    {
        $prod = new Product();    
        $prod->setMainTableRecordId( $mainTableRecordId );
        $result = $prod->saveProductSpecifications($prodSpecId, $langId, $prodSpecName, $prodSpecValue, $prodSpecGroup);
        $this->assertEquals($expected, $result);
    }
    
    public function setProductSpecifications()
    {  
        return array(
            array(0, 0, 1, 'test name', 'test value', 'test group', false), //Invalid product id
            array(140, 0, 0, 'test name', 'test value', 'test group', false), //Invalid lang id
            array(140, 0, 1, '', 'test value', 'test group', false), //invalid product specification name
            array(140, 0, 1, 'test name', '', 'test group', false), //invalid product specification value
            array(140, 0, 1, 'test name', 'test value', 'test group', true), //Add specification
            array(140, 343, 1, 'test update name', 'test update value', 'test update group', true), //update specification 
        );
    }
    
    /**
     * @dataProvider dataProdSpecificationsByLangId
     */
    public function testGetProdSpecificationsByLangId( $mainTableRecordId, $langId, $expected )
    {
        $prod = new Product();    
        $prod->setMainTableRecordId( $mainTableRecordId );
        $result = $prod->getProdSpecificationsByLangId($langId);
        $this->$expected($result);
    }
    
    public function dataProdSpecificationsByLangId()
    {  
        return array(
            array(0, 1, 'assertFalse'), //Invalid product id
            array(140, 0, 'assertFalse'), //Invalid lang id
            array(140, 1, 'assertIsArray'), //Valid data
        );
    }
    
    /**
     * @dataProvider dataProductSpecificsDetails
     */
    public function testGetProductSpecificsDetails( $productId, $expected )
    {
        $result = Product::getProductSpecificsDetails($productId);
        $this->$expected($result);
    }
    
    public function dataProductSpecificsDetails()
    {  
        return array(
            array(0, 'assertFalse'), //Invalid product id
            array(140, 'assertEmpty'), //Valid data with no record
            array(124, 'assertIsArray'), //Valid data having records
        );
    }
    
    /**
     * @dataProvider dataAddUpdateProductOption
     */
    public function testAddUpdateProductOption( $productId, $optionId, $expected )
    {
        $prod = new Product($productId);
        $result = $prod->addUpdateProductOption($optionId);
        $this->assertEquals($expected, $result);
    }
    
    public function dataAddUpdateProductOption()
    {  
        return array(
            array(0, 42, false), //Invalid product id
            array(140, 0, false), //Invalid option id
            array(140, 42, true), //Valid data
        );
    }
    
    /**
     * @dataProvider dataRemoveProductOption
     */
    public function testRemoveProductOption( $productId, $optionId, $expected )
    {
        $prod = new Product($productId);
        $result = $prod->removeProductOption($optionId);
        $this->assertEquals($expected, $result);
    }
    
    public function dataRemoveProductOption()
    {  
        return array(
            array(0, 42, false), //Invalid product id
            array(140, 0, false), //Invalid option id
            array(140, 42, true), //Valid data
        );
    }
    
    /**
     * @dataProvider dataAddUpdateProductTag
     */
    public function testAddUpdateProductTag( $productId, $tagId, $expected )
    {
        $prod = new Product($productId);
        $result = $prod->addUpdateProductTag($tagId);
        $this->assertEquals($expected, $result);
    }
    
    public function dataAddUpdateProductTag()
    {  
        return array(
            array(0, 54, false), //Invalid product id
            array(140, 0, false), //Invalid tag id
            array(140, 54, true), //Valid data
        );
    }
    
    /**
     * @dataProvider dataRemoveProductTag
     */
    public function testRemoveProductTag( $productId, $tagId, $expected )
    { 
        $prod = new Product($productId);
        $result = $prod->removeProductTag($tagId);
        $this->assertEquals($expected, $result);
    }
    
    public function dataRemoveProductTag()
    {  
        return array(
            array(0, 54, false), //Invalid product id
            array(140, 0, false), //Invalid tag id
            array(140, 54, true), //Valid data
        );
    }
    
    /**
     * @dataProvider dataAddUpdateProductShippingRates
     */
    public function testAddUpdateProductShippingRates( $productId, $data, $userId, $expected )
    {
        $result = Product::addUpdateProductShippingRates( $productId, $data, $userId );
        $this->assertEquals($expected, $result);
    }
    
    public function dataAddUpdateProductShippingRates()
    {  
        $data = array(array('country_id' =>'156', 'company_id' =>'1', 'processing_time_id' => '2', 'cost' => '250', 'additional_cost' => '25'));
        
        return array(
            array(0, array(), 0, false), //Invalid product id with empty data
            array(140, array(), 0, false), //Valid product id with empty data
            array(140, $data, 0, true), //Valid product id with data
        );
    }
    
    
    
}