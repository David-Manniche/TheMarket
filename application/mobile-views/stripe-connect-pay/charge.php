<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$paymentIntendId = isset($paymentIntendId) ? $paymentIntendId : '';
$data = array(
    'paymentAmount' => $paymentAmount,
    'customerId' => isset($customerId) ? $customerId : '',
    'orderInfo' => $orderInfo,
    'savedCards' => $savedCards,
    'paymentIntendId' => $paymentIntendId,
);