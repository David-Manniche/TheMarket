<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?> <?php
$arr_flds = array(
    'select_all' => Labels::getLabel('LBL_Select_all', $adminLangId),
    'listserial' => Labels::getLabel('LBL_Sr._No', $adminLangId),
    'taxcat_identifier' => Labels::getLabel('LBL_Tax_Category_Name', $adminLangId),
);

if ($activatedTaxServiceId) {
    $arr_flds['taxcat_code'] = Labels::getLabel('LBL_Tax_Code', $adminLangId);
} else {
    $arr_flds['taxval_value'] = Labels::getLabel('LBL_Value', $adminLangId);
}

$arr_flds['taxcat_active'] = Labels::getLabel('LBL_Status', $adminLangId);
$arr_flds['action'] = Labels::getLabel('LBL_Action', $adminLangId);
if (!$canEdit) {
    unset($arr_flds['select_all']);
}

$tbl = new HtmlElement('table', array('width'=>'100%', 'class'=>'table table--hovered table-responsive'));
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $key => $val) {
    if ('select_all' == $key) {
        $th->appendElement('th')->appendElement('plaintext', array(), '<label class="checkbox"><input title="'.$val.'" type="checkbox" onclick="selectAll( $(this) )" class="selectAll-js"><i class="input-helper"></i></label>', true);
    } else {
        $e = $th->appendElement('th', array(), $val);
    }
}

$sr_no = $page==1?0:$pageSize*($page-1);
foreach ($arr_listing as $sn => $row) {
    $sr_no++;
    $tr = $tbl->appendElement('tr', array());
    $tr->setAttribute("id", $row['taxcat_id']);

    foreach ($arr_flds as $key => $val) {
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'select_all':
                $td->appendElement('plaintext', array(), '<label class="checkbox"><input class="selectItem--js" type="checkbox" name="taxcat_ids[]" value='.$row['taxcat_id'].'><i class="input-helper"></i></label>', true);
                break;
            case 'listserial':
                $td->appendElement('plaintext', array(), $sr_no);
                break;
            case 'taxcat_identifier':
                if ($row['taxcat_name']!='') {
                    $td->appendElement('plaintext', array(), $row['taxcat_name'], true);
                    $td->appendElement('br', array());
                    $td->appendElement('plaintext', array(), '('.$row[$key].')', true);
                } else {
                    $td->appendElement('plaintext', array(), $row[$key], true);
                }
                break;
            case 'taxval_value':
                $str = ($row[$key]) ? CommonHelper::displayTaxFormat($row['taxval_is_percent'], $row[$key]) : '--';

                $td->appendElement('plaintext', array(), $str, true);
                break;
            case 'taxcat_active':
                $active = "active";
                if (!$row['taxcat_active']) {
                    $active = '';
                }
                $statucAct = ($canEdit === true) ? 'toggleStatus(this)' : '';
                $str = '<label id="'.$row['taxcat_id'].'" class="statustab '.$active.'" onclick="'.$statucAct.'">
                <span data-off="'. Labels::getLabel('LBL_Active', $adminLangId) .'" data-on="'. Labels::getLabel('LBL_Inactive', $adminLangId) .'" class="switch-labels"></span>
                <span class="switch-handles"></span>
                </label>';
                $td->appendElement('plaintext', array(), $str, true);
                break;
            case 'action':
				if($canEdit){
					$ul = $td->appendElement("ul",array("class"=>"actions"));
					$li = $ul->appendElement("li");
					$li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green', 'title'=>Labels::getLabel('LBL_Edit',$adminLangId),"onclick"=>"addTaxForm(".$row['taxcat_id'].")"),'<i class="far fa-edit icon"></i>', true);
					
					$li = $ul->appendElement("li");
					$li->appendElement('a', array('href'=>CommonHelper::generateUrl('Tax','ruleForm',array($row['taxcat_id'])), 'class'=>'button small green', 'title'=>Labels::getLabel('LBL_Add_Rule',$adminLangId)),'<i class="ion-navicon-round icon"></i>', true);
					
					$li = $ul->appendElement("li");
					$li->appendElement('a', array('href'=>"javascript:void(0)", 'class'=>'button small green', 'title'=>Labels::getLabel('LBL_Delete',$adminLangId),"onclick"=>"deleteRecord(".$row['taxcat_id'].")"),'<i class="fa fa-trash  icon"></i>', true);
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


$frm = new Form('frmTaxListing', array('id'=>'frmTaxListing'));
$frm->setFormTagAttribute('class', 'web_form last_td_nowrap actionButtons-js');
$frm->setFormTagAttribute('onsubmit', 'formAction(this, reloadList ); return(false);');
$frm->setFormTagAttribute('action', CommonHelper::generateUrl('Tax', 'toggleBulkStatuses'));
$frm->addHiddenField('', 'status');

echo $frm->getFormTag();
echo $frm->getFieldHtml('status');
echo $tbl->getHtml(); ?>
</form>
<?php $postedData['page'] = $page;
echo FatUtility::createHiddenFormFromData($postedData, array(
    'name' => 'frmTaxSearchPaging'
));
$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'recordCount'=>$recordCount,'adminLangId'=>$adminLangId);
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
