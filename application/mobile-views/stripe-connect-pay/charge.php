<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

$data = array(
    'paymentAmount' => $paymentAmount,
    'customerId' => isset($customerId) ? $customerId : '',
    'orderInfo' => $orderInfo,
    'savedCards' => $savedCards,
);