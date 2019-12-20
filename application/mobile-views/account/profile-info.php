<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

$data = array(
    'personalInfo' => (object)$personalInfo,
    'bankInfo' => (object)$bankInfo,
    'privacyPolicyLink' => CommonHelper::generateFullUrl('cms', 'view', array($privacyPolicyLink)),
    'faqLink' => CommonHelper::generateFullUrl('custom', 'faq'),
    'referralModuleIsEnabled' => FatApp::getConfig("CONF_ENABLE_REFERRER_MODULE", FatUtility::VAR_INT, 0),
    'hasDigitalProducts' => $hasDigitalProducts,
);

if (empty((array)$personalInfo) && empty((array)$bankInfo)) {
    $status = applicationConstants::OFF;
}
