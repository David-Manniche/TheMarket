<?php
class PayPalPayout
{
    public static function particulars()
    {
        return [
                'withdrawal_amount' => [
                    'type' => 'float',
                    'required' => true,
                    'label' => "Withdrawan Amount",
                ],
                'paypal_id' => [
                    'type' => 'string',
                    'required' => true,
                    'label' => "PayPal Id",
                ]
            ];
    }
}
