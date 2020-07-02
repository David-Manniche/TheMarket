<?php

class FixerCurrencyConverterTest extends YkPluginTest
{
    public const KEY_NAME = 'FixerCurrencyConverter';

    /**
     * testGetRates - Return Array in case of missing required keys.
     *
     * @dataProvider setInput
     * @param  bool $expected
     * @param  mixed $toCurrencies
     * @return void
     */
    public function testGetRates(bool $expected, $toCurrencies)
    {
        $this->expectedReturnType(static::TYPE_ARRAY);
        $response = $this->execute(self::KEY_NAME, [CommonHelper::getLangId()], 'getRates', [$toCurrencies]);
        $this->assertEquals($expected, $response);
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
