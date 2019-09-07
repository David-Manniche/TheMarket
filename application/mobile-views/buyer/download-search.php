<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

foreach ($digitalDownloads as $index => $row) {
    $digitalDownloads[$index]['product_image_url'] = CommonHelper::generateFullUrl('image', 'product', array($row['selprod_product_id'], "THUMB", $row['op_selprod_id'], 0, $siteLangId));
    $digitalDownloads[$index]['downloadUrl'] = CommonHelper::generateFullUrl('Buyer', 'downloadDigitalFile', array($row['afile_id'], $row['afile_record_id']));
}
$data = array(
    'digitalDownloads'=> $digitalDownloads,
    'page'=> $page,
    'pageCount'=> $pageCount,
    'recordCount'=> $recordCount,
);

if (empty($digitalDownloads)) {
    $status = applicationConstants::OFF;
}
