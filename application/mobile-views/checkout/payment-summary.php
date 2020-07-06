<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
foreach ($paymentMethods as $key => $val) {
    $fileData = AttachedFile::getAttachment(AttachedFile::FILETYPE_PLUGIN_LOGO, $val['plugin_id']);
    $uploadedTime = AttachedFile::setTimeParam($fileData['afile_updated_at']);
    $paymentMethods[$key]['image'] = UrlHelper::getCachedUrl(UrlHelper::generateFileUrl('Image', 'plugin', array($val['plugin_id'], 'ICON'), CONF_WEBROOT_FRONT_URL) . $uploadedTime, CONF_IMG_CACHE_TIME, '.jpg');
}

$data = array(
    'orderId' => $orderId,
    'orderType' => $orderType,
    'canUseWalletForPayment' => (true == $canUseWalletForPayment ? 1 : 0),
    'paymentMethods' => $paymentMethods,
);

require_once(CONF_THEME_PATH . 'cart/price-detail.php');

if (empty($products)) {
    $status = applicationConstants::OFF;
}
