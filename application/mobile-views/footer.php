<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

$data = empty($data) ? array() : $data;
$data = array_merge($commonData, $data);

if (applicationConstants::ON != $status) {
    $msg = Labels::getLabel('MSG_NO_RECORD_FOUND', $siteLangId);
    $status = applicationConstants::OFF;
}

$response = array(
    'status' => $status,
    'msg' => !empty($msg) ? $msg : Labels::getLabel('MSG_SUCCESS', $siteLangId),
    'data' => $data
);

// This line is added because we don't want to display web messages from APP.
$messages = Message::getHtml();

CommonHelper::jsonEncodeUnicode($response, true);
