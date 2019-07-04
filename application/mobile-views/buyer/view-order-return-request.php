<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

$statusArr = array(
    'status'=> 1,
    'msg' => Labels::getLabel('MSG_Success', $siteLangId)
);
$request['charges'] = array_values($request['charges']);
$data = array(
    'canEscalateRequest' => $canEscalateRequest,
    'canWithdrawRequest' => $canWithdrawRequest,
    'request' => $request,
    'vendorReturnAddress' => $vendorReturnAddress,
    'returnRequestTypeArr' => $returnRequestTypeArr,
    'requestRequestStatusArr' => $requestRequestStatusArr,
    'returnRequestTypeArr' => $returnRequestTypeArr,
);
if (1 > count($request)) {
    $statusArr['status'] = 0;
    $statusArr['msg'] = Labels::getLabel('MSG_No_record_found', $siteLangId);
}
