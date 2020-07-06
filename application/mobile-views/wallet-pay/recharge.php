<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

foreach ($paymentMethods as $key => $val) {    
    $fileData = AttachedFile::getAttachment(AttachedFile::FILETYPE_PLUGIN_LOGO, $val['plugin_id']);
    $uploadedTime = AttachedFile::setTimeParam($fileData['afile_updated_at']);
    $paymentMethods[$key]['image'] = UrlHelper::getCachedUrl(UrlHelper::generateFileUrl('Image', 'plugin', array($val['plugin_id'], 'ICON'), CONF_WEBROOT_FRONT_URL) . $uploadedTime, CONF_IMG_CACHE_TIME, '.jpg');
}

$orderNetAmount = (!empty($orderInfo['order_net_amount']) && 0 < $orderInfo['order_net_amount'] ? $orderInfo['order_net_amount'] : 0);
$data = array(
    'paymentMethods' => $paymentMethods,
    // 'orderInfo' => $orderInfo,
    'order_type' => $orderInfo['order_type'],
    'orderNetAmount' => $orderNetAmount
);
$data['netPayable'] = array(
    'key' => Labels::getLabel('LBL_Net_Payable', $siteLangId),
    'value' => CommonHelper::displayMoneyFormat($orderNetAmount)
);

if (empty(array_filter($paymentMethods)) || empty(array_filter($orderInfo))) {
    $status = applicationConstants::OFF;
}
