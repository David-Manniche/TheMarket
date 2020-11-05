<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

array_walk($downloads, function (&$row) use ($siteLangId) {
    $uploadedTime = AttachedFile::setTimeParam($row['product_updated_on']);
    $row['product_image_url'] = UrlHelper::getCachedUrl(UrlHelper::generateFullFileUrl('image', 'product', array($row['selprod_product_id'], "CLAYOUT3", $row['op_selprod_id'], 0, $siteLangId)) . $uploadedTime, CONF_IMG_CACHE_TIME, '.jpg');
    $row['downloadUrl'] = UrlHelper::generateFullUrl() . 'public/index.php?url=buyer/download-digital-file/' . $row['afile_id'] . '/' . $row['afile_record_id'];
});

$data = array(
    'downloads' => $downloads,
    'page' => $page,
    'pageCount' => $pageCount,
    'recordCount' => $recordCount,
);

if (1 > $recordCount) {
    $status = applicationConstants::OFF;
}
