<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
$arr_flds = array(
        'listserial'=>Labels::getLabel('LBL_Sr._No', $adminLangId),
        'prodcat_identifier'=>Labels::getLabel('LBL_Category_Name', $adminLangId),
        'prodcat_parent'=>Labels::getLabel('LBL_Parent_category', $adminLangId),
        'shop_name'=>Labels::getLabel('LBL_Requested_BY', $adminLangId),
		'prodcat_requested_on'=>Labels::getLabel('LBL_Requested_On', $adminLangId),
        'action' => '',
    );

if (!$canEdit) {
    unset($arr_flds['action']);
}

$tbl = new HtmlElement('table', array('width'=>'100%', 'class'=>'table table-responsive table--hovered'));
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $key => $val) {
    $e = $th->appendElement('th', array(), $val);
}

$sr_no = $page==1?0:$pageSize*($page-1);
foreach ($arr_listing as $sn => $row) {
    $sr_no++;
    $tr = $tbl->appendElement('tr');
    $tr->setAttribute("id", $row['prodcat_id']);

    foreach ($arr_flds as $key=>$val) {
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'listserial':
                $td->appendElement('plaintext', array(), $sr_no);
            break;
            case 'shop_name':
                $name = $row['shop_name'] . '(' . $row['user_name'] . ')' ;
                $td->appendElement('plaintext', array(), $name);
            break;
            case 'prodcat_parent':
                $prodCat = new productCategory();
                $name = $prodCat->getParentTreeStructure($row['prodcat_id'], 0, '', $adminLangId, false, ProductCategory::REQUEST_PENDING);
                $td->appendElement('plaintext', array(), $name, true);
            break;
            case 'prodcat_identifier':
                if ($row['prodcat_name']!='') {
                    $td->appendElement('plaintext', array(), $row['prodcat_name'], true);
                    $td->appendElement('br', array());
                    $td->appendElement('plaintext', array(), '('.$row[$key].')', true);
                } else {
                    $td->appendElement('plaintext', array(), $row[$key], true);
                }
                break;
			case 'prodcat_requested_on':
                $td->appendElement('plaintext', array(), FatDate::Format($row[$key]), true);
                break;
            case 'action':
                if ($canEdit) {
                    $statucAct = ($canEdit === true) ? 'toggleStatus(this)' : '';
                    $str='<label class="statustab -txt-uppercase" title="'.Labels::getLabel('LBL_Approve_Request', $adminLangId).'">
                          <input type="checkbox" id="switch'.$row['prodcat_id'].'" value="'.$row['prodcat_id'].'" onclick="'.$statucAct.'" class="switch-labels"/>
                          <i class="switch-handles"></i>
                        </label>';
                    $td->appendElement('plaintext', array(), $str, true);
                }
            break;
            default:
                $td->appendElement('plaintext', array(), $row[$key], true);
            break;
        }
    }
}
if (count($arr_listing) == 0) {
    $tbl->appendElement('tr')->appendElement('td', array('colspan'=>count($arr_flds)), Labels::getLabel('LBL_No_Records_Found', $adminLangId));
}
echo $tbl->getHtml();
$postedData['page']=$page;
echo FatUtility::createHiddenFormFromData($postedData, array(
        'name' => 'frmCategorySearchPaging'
));
$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'recordCount'=>$recordCount,'adminLangId'=>$adminLangId);
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
?>
