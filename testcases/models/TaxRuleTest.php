<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class TaxRuleTest extends TestCase
{

    /**
     * @dataProvider setDeleteRule
     */
    public function testDeleteRules($taxCatId, $expected)
    {
        $taxRule = new TaxRule();
        $result = $taxRule->deleteRules($taxCatId);
        $this->assertEquals($expected, $result);
    }

    public function setDeleteRule()
    {
        return array(
            array(-1, false), // Invalid taxCatId
            array(0, false), // Invalid taxCatId
            array(1, false), // Invalid taxCatId
            array(8, true), // valid taxCatId
        );
    }

    /**
     * @dataProvider setGetRules
     */
    public function testGetRules($taxCatId)
    {
        $taxRule = new TaxRule();
        $result = $taxRule->getRules($taxCatId);
        $this->assertIsArray($result);
    }

    public function setGetRules()
    {
        return array(
            array(-1), // Invalid taxCatId
            array(0), // Invalid taxCatId
            array(1), // Invalid taxCatId
            array(8), // valid taxCatId
        );
    }

    /**
     * @dataProvider setGetCombinedRuleDetails
     */
    public function testGetCombinedRuleDetails($rulesIds)
    {
        $taxRule = new TaxRule();
        $result = $taxRule->getCombinedRuleDetails($rulesIds);
        $this->assertIsArray($result);
    }

    public function setGetCombinedRuleDetails()
    {
        return array(
            array(array()), // Empty Array
            array(array(-1,0,null)), // Invalid array
            array(array(1,2,3,4,5)), // valid Rule Ids
        );
    }

    /**
     * @dataProvider setGetLocations
     */
    public function testGetLocations($taxCatId)
    {
        $taxRule = new TaxRule();
        $result = $taxRule->getLocations($taxCatId);
        $this->assertIsArray($result);
    }

    public function setGetLocations()
    {
        return array(
            array(-1), // Invalid taxCatId
            array(0), // Invalid taxCatId
            array(1), // Invalid taxCatId
            array(8), // valid taxCatId
        );
    }
}
