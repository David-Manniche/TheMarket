<?php 
$variables= array('siteLangId'=>$siteLangId, 'action'=>$action);
$this->includeTemplate('seller-requests/_partial/requests-navigation.php', $variables, false); ?>
<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$arr_flds = array(
    'listserial'=>'#',
    'prodcat_name' => Labels::getLabel('LBL_Category_Name', $siteLangId),
	'prodcat_parent'=>Labels::getLabel('LBL_Parent_category', $siteLangId),
    'prodcat_requested_on' => Labels::getLabel('LBL_Requested_on', $siteLangId),
    'prodcat_status' => Labels::getLabel('LBL_Status', $siteLangId),
);
if ($canEdit) {
    $arr_flds['action'] = '';
}
$tbl = new HtmlElement('table', array('width'=>'100%', 'class'=>'table table-justified'));
$th = $tbl->appendElement('thead')->appendElement('tr', array('class' => ''));
foreach ($arr_flds as $val) {
    $e = $th->appendElement('th', array(), $val);
}

$sr_no = ($page == 1) ? 0 : ($pageSize*($page-1));
foreach ($arr_listing as $sn => $row) {
    $sr_no++;
    $tr = $tbl->appendElement('tr', array('class' => ''));

    foreach ($arr_flds as $key => $val) {
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'listserial':
                $td->appendElement('plaintext', array(), $sr_no, true);
                break;
            case 'prodcat_name':
                $td->appendElement('plaintext', array(), (!empty($row['prodcat_name']) ? $row['prodcat_name'] : $row['prodcat_identifier']) . '<br>', true);
                $td->appendElement('plaintext', array(), '('.$row['prodcat_identifier'].')', true);
                break;
			case 'prodcat_parent':
                $prodCat = new productCategory();
                $name = $prodCat->getParentTreeStructure($row['prodcat_id'], 0, '', $siteLangId, false, -1);
                $td->appendElement('plaintext', array(), $name, true);
            break;
            case 'prodcat_status':
                $td->appendElement('span', array('class' => 'label label-inline '. $statusClassArr[$row[$key]]), $statusArr[$row[$key]] . '<br>', true);
                $td->appendElement('small', array('class' => 'ml-1'), ($row['prodcat_status_updated_on'] != '0000-00-00 00:00:00') ? FatDate::Format($row['prodcat_status_updated_on']) : '', true);
                break;
            case 'prodcat_requested_on':
                $td->appendElement('plaintext', array(), ($row[$key] != '0000-00-00 00:00:00') ? FatDate::Format($row[$key]) : Labels::getLabel('LBL_NA', $siteLangId), true);
                break;
            case 'action':
                $ul = $td->appendElement("ul", array('class'=>'actions'), '', true);
                $li = $ul->appendElement("li");
                if ($row['prodcat_status'] == ProductCategory::REQUEST_PENDING) {
                    $li->appendElement(
                        'a',
                        array('href'=>'javascript:void(0)', 'onclick' => "addCategoryReqForm(".$row['prodcat_id'].")", 'class'=>'','title'=>Labels::getLabel('LBL_Edit', $siteLangId)),
                        '<i class="fa fa-edit"></i>',
                        true
                    );
                }
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
echo FatUtility::createHiddenFormFromData($postedData, array('name' => 'frmSrchProdCategoryRequest'));

$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'callBackJsFunc' => 'goToProdCategorySearchPage');
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
