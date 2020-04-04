<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

foreach ($pnotifications as &$pvalue) {
    $image = '';
    if ($imgData = AttachedFile::getAttachment(AttachedFile::FILETYPE_PUSH_NOTIFICATION_IMAGE, $pvalue['pnotification_id'])) {
        $uploadedTime = AttachedFile::setTimeParam($imgData['afile_updated_at']);
        $image = FatCache::getCachedUrl(CommonHelper::generateFullUrl('Image', 'pushNotificationImage', [$pvalue['pnotification_id']], CONF_WEBROOT_FRONT_URL) . $uploadedTime, CONF_IMG_CACHE_TIME, '.jpg');
    }
    $pvalue['image'] = $image;
    $pvalue['urlDetail'] = !empty($pvalue['pnotification_url']) ? CommonHelper::getUrlTypeData($pvalue['pnotification_url']) : (object)array();
}

$data = array(
    'pnotifications' => !empty($pnotifications) ? $pnotifications : [],
    'total_pages' => $total_pages,
    'total_records' => $total_records,
);

if (empty($pnotifications)) {
    $status = applicationConstants::OFF;
}
