<?php
class TaxTest extends YkModelTest
{

    /**
     * @dataProvider setGetSaleTaxCatArr
     */
    public function testGetSaleTaxCatArr($langId, $isActive)
    {
        $result = Tax::getSaleTaxCatArr($langId, $isActive);
        $this->assertIsArray($result);
    }

    public function setGetSaleTaxCatArr()
    {
        return [
            [-1, false],  // Invalid $langId
            [0, false],  // Invalid $langId
            [1, true],  // Valid $langId
        ];
    }

    /**
     * @dataProvider setGetTaxValuesByCatId
     */
    public function testGetTaxCatObjByProductId($taxcatId, $userId, $defaultValue)
    {
        $tax = new Tax();
        $result = $tax->getTaxValuesByCatId($taxcatId, $userId, $defaultValue);
        $this->assertIsArray($result);
    }

    public function setGetTaxValuesByCatId()
    {
        return [
            [-1, -1, false],  // Invalid $taxcatId with Invalid $userId
            [-1, 0, false],  // Invalid $taxcatId with valid $userId
            [0, 4, false],  // Invalid $taxcatId with valid $userId
            [1, 4, true],  // Valid $taxcatId with Invalid $userId
            [1, 4, false],  // Valid $taxcatId with Valid $userId
        ];
    }

    /**
     * @dataProvider setAddUpdateProductTaxCat
     */
    public function testAddUpdateProductTaxCat($data, $expected)
    {
        $tax = new Tax();
        $result = $tax->addUpdateProductTaxCat($data);
        $this->assertEquals($expected, $result);
    }

    public function setAddUpdateProductTaxCat()
    {
        return [
            [
                [
                    'ptt_product_id' => -1,
                    'ptt_taxcat_id' => 4,
                    'ptt_seller_user_id' => 0
                ], false
            ],  // Invalid productId
            [
                [
                'ptt_product_id' => 1,
                'ptt_taxcat_id' => 0,
                'ptt_seller_user_id' => 0
                ], false
            ],  // Invalid taxCatId
            [
                [
                'ptt_product_id' => 1,
                'ptt_taxcat_id' => 4,
                'ptt_seller_user_id' => -1
                ], false
            ],  // Invalid sellerUserId
            [
                [
                'ptt_product_id' => 1,
                'ptt_taxcat_id' => 4,
                'ptt_seller_user_id' => 0
                ], false
            ],  // valid data
        ];
    }

    /**
     * @dataProvider setGetTaxRates
     */
    public function testGetTaxRates($data)
    {
        $tax = new Tax();
        $result = $tax->getTaxRates($data['prouct_id'], $data['user_id'], $data['lang_id'], $data['user_country'], $data['user_state']);
        $this->assertIsArray($result);
    }

    public function setGetTaxRates()
    {
        return [
            [
                [
                    'prouct_id' => 0,
                    'user_id' => 0,
                    'lang_id' => 0,
                    'user_country' => 0,
                    'user_state' => 0
                ]
            ],  // Invalid data
            [
                [
                    'prouct_id' => 1,
                    'user_id' => 4,
                    'lang_id' => 1,
                    'user_country' => 0,
                    'user_state' => 0
                ]
            ],  // Valid data without country and state
            [
                [
                    'prouct_id' => 1,
                    'user_id' => 4,
                    'lang_id' => 1,
                    'user_country' => 99,
                    'user_state' => 1287

                ]
            ],  // Valid data for specific country and state
        ];
    }

    /**
     * @dataProvider setCalculateTaxRates
     */
    public function testCalculateTaxRates($data)
    {
        $tax = new Tax();
        $result = $tax->calculateTaxRates($data['prouctId'], $data['prodPrice'], $data['sellerId'], $data['langId'], $data['prodQty'], $data['extraInfo'], $data['useCache']);
        $this->assertIsArray($result);
    }

    public function setCalculateTaxRates()
    {
        return [
            [
                [
                    'prouctId' => 0,
                    'prodPrice' => 0,
                    'sellerId' => 0,
                    'langId' => 0,
                    'prodQty' => 0,
                    'extraInfo' => [],
                    'useCache' => true
                ]
            ],  // Invalid data
            [
                [
                    'prouctId' => 1,
                    'prodPrice' => 100,
                    'sellerId' => 4,
                    'langId' => 1,
                    'prodQty' => 2,
                    'extraInfo' => [],
                    'useCache' => false
                ]
            ],  // Valid data
        ];
    }

    /**
     * @dataProvider setGetTaxCatByProductId
     */
    public function testGetTaxCatByProductId($data)
    {
        $result = Tax::getTaxCatByProductId($data['prouctId'], $data['userId'], $data['langId'], $data['attr']);
        $this->assertIsArray($result);
    }

    public function setGetTaxCatByProductId()
    {
        return [
            [
                [
                    'prouctId' => 0,
                    'userId' => 0,
                    'langId' => 0,
                    'attr' => [],
                ]
            ],  // Invalid data
            [
                [
                    'prouctId' => 0,
                    'userId' => 0,
                    'langId' => 1,
                    'attr' => ['ptt_taxcat_id'],
                ]
            ],  // Invalid data
            [
                [
                    'prouctId' => 1,
                    'userId' => 4,
                    'langId' => 1,
                    'attr' => ['ptt_taxcat_id'],
                ]
            ],  // Valid data
        ];
    }

    /**
     * @dataProvider setRemoveTaxSetByAdmin
     */
    public function testRemoveTaxSetByAdmin($data, $expected)
    {
        $tax = new Tax();
        $result = $tax->removeTaxSetByAdmin($data);
        $this->assertEquals($expected, $result);
    }

    public function setRemoveTaxSetByAdmin()
    {
        return [
            [
                -1, false
            ],  // Invalid productId
            [
                0, false
            ],  // Invalid productId
            [
                5555555555, false
            ],  // Invalid productId
            [
                1, true
            ],  // valid productId
        ];
    }

    /**
     * @dataProvider setGetAttributesByCode
     */
    public function testGetAttributesByCode($data)
    {
        $result = Tax::getAttributesByCode($data['code'], $data['attr'], $data['pluginId']);
        $this->assertIsArray($result);
    }

    public function setGetAttributesByCode()
    {
        return [
            [
                [
                    'code' => 0,
                    'attr' => [],
                    'pluginId' => 0,
                ]
            ],  // Invalid data
            [
                [
                    'code' => '',
                    'attr' => [],
                    'pluginId' => 0,
                ]
            ],  // Invalid data
            [
                [
                    'code' => 'testapp',
                    'attr' => [],
                    'pluginId' => 0,
                ]
            ],  // Invalid data
            [
                [
                    'code' => 'testapp',
                    'attr' => ['taxcat_plugin_id'],
                    'pluginId' => 15,
                ]
            ],  // Valid data
        ];
    }
}
