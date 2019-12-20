<?php defined('SYSTEM_INIT') or die('Invalid Usage.');

$arr_flds = array(
    'listserial' => Labels::getLabel('LBL_S.No.', $adminLangId),
    'pnotification_type' => Labels::getLabel('LBL_TYPE', $adminLangId),
    'notification_detail' => Labels::getLabel('LBL_DETAIL', $adminLangId),
    'pnotification_notified_on' => Labels::getLabel('LBL_SCHEDULED_FOR', $adminLangId),
    'notify_to' => Labels::getLabel('LBL_NOTIFY_TO', $adminLangId),
    'pnotification_active' => Labels::getLabel('LBL_STATUS', $adminLangId),
    'action' => Labels::getLabel('LBL_Action', $adminLangId),
);
$tbl = new HtmlElement('table', array('width' => '100%', 'class' => 'table table-responsive'));
$th = $tbl->appendElement('thead')->appendElement('tr');

foreach ($arr_flds as $key => $val) {
    $th->appendElement('th', array(), $val);
}

$sr_no = $page == 1 ? 0 : $pageSize * ($page - 1);
foreach ($arr_listing as $sn => $row) {
    $sr_no++;
    $tr = $tbl->appendElement('tr', array( ));

    foreach ($arr_flds as $key => $val) {
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'listserial':
                $td->appendElement('plaintext', array(), $sr_no);
                break;
            case 'pnotification_type':
                $td->appendElement('plaintext', array(), $typeArr[$row[$key]], true);
                break;
            case 'notification_detail':
                $htm =  '<b>' . Labels::getLabel('LBL_TITLE', $adminLangId) . ':</b> ' . $row['pnotification_title'] . '<br>';
                $htm .= '<b>' . Labels::getLabel('LBL_BODY', $adminLangId) . ':</b> ' . $row['pnotification_description'];
                $td->appendElement('plaintext', array(), $htm, true);
                break;
            case 'pnotification_notified_on':
                $td->appendElement('plaintext', array(), FatDate::format(
                    $row[$key],
                    true,
                    true,
                    FatApp::getConfig('CONF_TIMEZONE', FatUtility::VAR_STRING, date_default_timezone_get())
                ));
                break;
            case 'notify_to':
                $buyerHtm = $sellerHtm = '';
                if (0 < $row['pnotification_for_buyer']) {
                    $buyerHtm = '<span class="badge badge-success">' . Labels::getLabel('LBL_BUYERS', $adminLangId) . '</span>';
                }

                if (0 < $row['pnotification_for_seller']) {
                    $sellerHtm = '<span class="badge badge-info">' . Labels::getLabel('LBL_SELLERS', $adminLangId) . '</span>';
                }
                $td->appendElement('plaintext', array(), $buyerHtm . ' ' . $sellerHtm, true);
                break;
            case 'pnotification_active':
                $td->appendElement('plaintext', array(), $statusArr[$row[$key]], true);
                break;
            case 'action':
                $ul = $td->appendElement("ul", ["class" => "actions actions--centered"]);
                if ($canEdit) {
                    $li = $ul->appendElement("li", ['class' => 'droplink']);
                    $li->appendElement('a', ['href' => 'javascript:void(0)', 'class' => 'button small green', 'title '=> Labels::getLabel('LBL_Edit', $adminLangId)], '<i class="ion-android-more-horizontal icon"></i>', true);
                    $innerDiv = $li->appendElement('div', ['class' => 'dropwrap']);
                    $innerUl = $innerDiv->appendElement('ul', ['class' => 'linksvertical']);

                    $innerLi = $innerUl->appendElement('li');
                    $innerLi->appendElement('a', array('href' => 'javascript:void(0)', 'class' => 'button small green', 'title' => Labels::getLabel('LBL_Edit', $adminLangId), "onclick" => "addNotificationForm(" . $row['pnotification_id'] . ")"), Labels::getLabel('LBL_Edit', $adminLangId), true);
                }
                break;
        }
    }
}
if (count($arr_listing) == 0) {
    $tbl->appendElement('tr')->appendElement('td', ['colspan' => count($arr_flds)], Labels::getLabel('LBL_No_Records_Found', $adminLangId));
}

echo $tbl->getHtml();

$postedData['page'] = $page;
echo FatUtility::createHiddenFormFromData($postedData, ['name' => 'frmUserSearchPaging']);
$pagingArr = [
    'pageCount' => $pageCount,
    'page' => $page,
    'pageSize' => $pageSize,
    'recordCount' => $recordCount,
    'adminLangId' => $adminLangId
];
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
