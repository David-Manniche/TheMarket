<?php
class PayPalPayout
{
    public static function particulars()
    {
        return [
                'amount' => [
                    'type' => 'float',
                    'required' => true,
                    'label' => "Amount",
                ],
                'email' => [
                    'type' => 'string',
                    'required' => false,
                    'label' => "Email Id",
                ],
                'paypal_id' => [
                    'type' => 'string',
                    'required' => false,
                    'label' => "PayPal Id",
                ],
            ];
    }
}
