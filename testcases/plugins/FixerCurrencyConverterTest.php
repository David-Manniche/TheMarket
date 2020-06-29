<?php

class FixerCurrencyConverterTest extends YkPluginTest
{
    public const KEY_NAME = 'FixerCurrencyConverter';

    /**
     * testGetRates - Return Array in case of missing required keys.
     *
     * @dataProvider setInput
     * @param  array $toCurrencies
     * @return void
     */
    public function testGetRates(array $toCurrencies): void
    {
        $class = self::KEY_NAME;
        $object = new $class(CommonHelper::getLangId());
        $result = $object->getRates($toCurrencies);
        print_r($result);
        $this->assertIsArray($result);
    }
        
    /**
     * setInput
     *
     * @return array
     */
    public function setInput(): array
    {
        return [
            [
                ['USD', 'INR']  // Return Passed Currencies Conversion Rates. Expected TRUE
            ],
            [
                []  // Return All Currencies Conversion Rates. Expected TRUE
            ],
        ];
    }
}
