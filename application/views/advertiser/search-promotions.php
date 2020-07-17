<?php
$arr_flds = array(
        'listserial'=>'#',
        'promotion_name'=>Labels::getLabel('LBL_TITLE', $siteLangId),
        'promotion_budget'=>Labels::getLabel('LBL_Budget', $siteLangId),
        'promotion_duration'=>Labels::getLabel('LBL_Duration', $siteLangId),
        'promotion_type'=>Labels::getLabel('LBL_Type', $siteLangId),
        'promotion_date'=>Labels::getLabel('LBL_SCHEDULED', $siteLangId),
        // 'promotion_time'=>Labels::getLabel('LBL_Time', $siteLangId),
        'promotion_end_date'=>Labels::getLabel('LBL_PROMOTION', $siteLangId),
        'promotion_approved'=>Labels::getLabel('LBL_Approved', $siteLangId),
        'promotion_active'=>Labels::getLabel('LBL_Status', $siteLangId),
        'action' => '',
    );
$tbl = new HtmlElement(
    'table',
    array('width'=>'100%', 'class'=>'table','id'=>'promotions')
);

$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $key => $val) {
    $th->appendElement('th', array(), $val);
}
$arrYesNo = applicationConstants::getYesNoArr($siteLangId);
$activeInactiveArr = applicationConstants::getActiveInactiveArr($siteLangId);
$sr_no = $page==1?0:$pageSize*($page-1);
foreach ($arr_listing as $sn => $row) {
    $sr_no++;
    $tr = $tbl->appendElement('tr');
    $tr->setAttribute("id", $row['promotion_id']);

    foreach ($arr_flds as $key => $val) {
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'listserial':
                $td->appendElement('plaintext', array(), $sr_no);
                break;
            case 'promotion_name':
                $td->appendElement('plaintext', array(), $row[$key], true);
                break;
            case 'promotion_budget':
                $td->appendElement('plaintext', array(), CommonHelper::displayMoneyFormat($row[$key]));
                break;
            case 'promotion_duration':
                $td->appendElement('plaintext', array(), $promotionBudgetDurationArr[$row[$key]], true);
                break;
            case 'promotion_type':
                $td->appendElement('plaintext', array(), $typeArr[$row[$key]], true);
                break;
            case 'promotion_approved':
                $td->appendElement('plaintext', array(), $arrYesNo[$row[$key]], true);
                break;
            case 'promotion_active':
                $td->appendElement('plaintext', array(), $activeInactiveArr[$row[$key]], true);
                break;
            case 'promotion_end_date':
                $txt = '';
                if ($row[$key] < date("Y-m-d")) {
                    $txt .= Labels::getLabel('LBL_Expired', $siteLangId);
                } else {
                    if ($row['promotion_start_date'] >= date("Y-m-d")){
                        $txt .= Labels::getLabel('LBL_RUNNING', $siteLangId);
                    } else {
                        $txt .= Labels::getLabel('LBL_SCHEDULED', $siteLangId);
                    }
                }
                $td->appendElement('plaintext', array(), $txt, true);
                break;
            case 'promotion_date':
                $str = '<span class="text-nowrap">' . Labels::getLabel('LBL_Start', $siteLangId).' : ' . FatDate::format($row['promotion_start_date']) . ' ' . date("G:i", strtotime($row['promotion_start_time'])) . '</span><br>';
                $str.= '<span class="text-nowrap">' . Labels::getLabel('LBL_End', $siteLangId).' : ' . FatDate::format($row['promotion_end_date']) . ' ' . date("G:i", strtotime($row['promotion_end_time'])) . '</span>';

                $td->appendElement('plaintext', array(), $str, true);
                break;
            /* case 'promotion_time':
                $str = "<span class='text-nowrap'>".Labels::getLabel('LBL_Start_Time', $siteLangId).' : '.date("G:i", strtotime($row['promotion_start_time']))."</span><br>";
                $str.= "<span  class='text-nowrap'>".Labels::getLabel('LBL_End_Time', $siteLangId).' : '.date("G:i", strtotime($row['promotion_end_time']))."</span>";

                $td->appendElement('plaintext', array(), $str, true);
                break; */
            case 'action':
                $ul = $td->appendElement("ul", array("class"=>"actions"));
                if ($canEdit) {
                    $li = $ul->appendElement("li");
                    $li->appendElement(
                        'a',
                        array(
                        'href'=>'javascript:void(0)',
                        'class'=>'button small green', 'title'=>Labels::getLabel('LBL_Edit', $siteLangId),
                        "onclick"=>"promotionForm(".$row['promotion_id'].")"),
                        '<i class="fa fa-edit"></i>',
                        true
                    );
                }

                $li = $ul->appendElement("li");
                $li->appendElement(
                    'a',
                    array(
                    'href'=>UrlHelper::generateUrl('advertiser', 'analytics', array($row['promotion_id'])),
                    'class'=>'button small green', 'title'=>Labels::getLabel('LBL_Analytics', $siteLangId)),
                    '<i class="far fa-file-alt"></i>',
                    true
                );

                /* $li = $ul->appendElement("li");
                $li->appendElement('a', array(
                    'href'=>"javascript:void(0)", 'class'=>'button small green',
                    'title'=>Labels::getLabel('LBL_Delete',$siteLangId),"onclick"=>"deletepromotionRecord(".$row['promotion_id'].")"),
                    '<i class="fa fa-trash"></i>', true); */

                break;
            default:
                $td->appendElement('plaintext', array(), $row[$key], true);
                break;
        }
    }
}
echo $tbl->getHtml();
if (count($arr_listing) == 0) {
    $message = Labels::getLabel('LBL_No_Records_Found', $siteLangId);
    $this->includeTemplate('_partial/no-record-found.php', array('siteLangId'=>$siteLangId,'message'=>$message));
}
$postedData['page'] = $page;
echo FatUtility::createHiddenFormFromData($postedData, array(
        'name' => 'frmPromotionSearchPaging'
));
$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'recordCount'=>$recordCount);
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
