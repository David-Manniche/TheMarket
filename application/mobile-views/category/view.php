<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

unset($data['frmProductSearch'], $data['postedData']);

if (array_key_exists('products', $data)) {
    foreach ($data['products'] as $index => $product) {
        $data['products'][$index]['product_image_url'] = CommonHelper::generateFullUrl('image', 'product', array($product['product_id'], "CLAYOUT3", $product['selprod_id'], 0, $siteLangId));
    }
}
if (empty($data)) {
    $status = applicationConstants::OFF;
}
