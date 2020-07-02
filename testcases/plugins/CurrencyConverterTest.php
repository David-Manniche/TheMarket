<?php

class CurrencyConverterTest extends YkPluginTest
{
    public const KEY_NAME = 'CurrencyConverter';

    /**
     * testGetRates
     *
     * @dataProvider setInput
     * @param  bool $expected
     * @param  mixed $toCurrencies
     * @return void
     */
    public function testGetRates(bool $expected, $toCurrencies)
    {
        $this->setFailureReturnType(static::TYPE_ARRAY);
        $result = $this->execute(self::KEY_NAME, [CommonHelper::getLangId()], 'getRates', [$toCurrencies]);
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
            [true, ['USD', 'INR']], // Return Passed Currencies Conversion Rates. Expected TRUE
            [true, []], // It is required to pass currencies to convert. Expected TRUE
            [false, 'test'],   // Return error, Invalid request param,
        ];
    }
}
