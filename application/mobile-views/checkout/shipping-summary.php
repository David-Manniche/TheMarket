<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

$productItems = [];
$count = 0;

switch ($fulfillmentType) {
    case Shipping::FULFILMENT_PICKUP:
        require_once(CONF_THEME_PATH . 'checkout/fulfillment-pickup-summary.php');
        break;
    case Shipping::FULFILMENT_SHIP:
        require_once(CONF_THEME_PATH . 'checkout/fulfillment-ship-summary.php');
        break;
    
    default:
        $msg = Labels::getLabel("MSG_INVALID_FULFILLMENT_TYPE", $siteLangId);
        FatUtility::dieJsonError($msg);
        break;
}

if (Shipping::FULFILMENT_PICKUP == $fulfillmentType) {
    require_once(CONF_THEME_PATH . 'checkout/fulfillment-pickup-summary.php');
}

if (Shipping::FULFILMENT_SHIP == $fulfillmentType) {
    require_once(CONF_THEME_PATH . 'checkout/fulfillment-ship-summary.php');
}

$data = [
    'fulfillmentType' => $fulfillmentType,
    'hasPhysicalProd' => $hasPhysicalProd,
    'addresses' => $addresses,
    'cartSummary' => $cartSummary,
    'productItems' => $productItems,
];

require_once(CONF_THEME_PATH . 'cart/price-detail.php');

if (empty($productItems)) {
    $status = applicationConstants::OFF;
}
