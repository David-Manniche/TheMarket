<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$paymentIntendId = isset($paymentIntendId) ? $paymentIntendId : '';
$confirmationRequired = $confirmationRequired ? Plugin::RETURN_TRUE : Plugin::RETURN_FALSE;
$data = array(
    'paymentAmount' => $paymentAmount,
    'customerId' => isset($customerId) ? $customerId : '',
    'orderInfo' => $orderInfo,
    'savedCards' => $savedCards,
    'paymentIntendId' => $paymentIntendId,
    'clientSecret' => $clientSecret,
    'confirmationRequired' => $confirmationRequired,
);