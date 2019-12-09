<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php 
$arr_flds = array(
	'listserial'	=>	'',
	'user_name'=>Labels::getLabel('LBL_Users',$adminLangId),
	'selprod_title'=>Labels::getLabel('LBL_Seller_products',$adminLangId),
    'carthistory_qty'=>Labels::getLabel('LBL_Qty',$adminLangId),
    'carthistory_action'=>Labels::getLabel('LBL_Status',$adminLangId),
	'carthistory_added_on'=>Labels::getLabel('LBL_Date',$adminLangId),
	'action' => Labels::getLabel('LBL_Action',$adminLangId),
);
$tbl = new HtmlElement('table', array('width'=>'100%', 'class'=>'table table--hovered table-responsive'));
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $val) {
	$e = $th->appendElement('th', array(), $val);
}
$sr_no = $page==1?0:$pageSize*($page-1);

foreach ($records as $sn=>$row){
	$sr_no++;
	$tr = $tbl->appendElement('tr');

	foreach ($arr_flds as $key=>$val){
		$td = $tr->appendElement('td');
		switch ($key){
			case 'listserial':
				$td->appendElement('plaintext', array(), $sr_no);
			break;
			case 'carthistory_action': 
                $actionArr = CartHistory::getActionArr($adminLangId);
                $td->appendElement('plaintext', array(), $actionArr[$row[$key]]);
			break;
			case 'carthistory_added_on': 
                $td->appendElement('plaintext',array(),FatDate::format($row[$key],true,true,FatApp::getConfig('CONF_TIMEZONE', FatUtility::VAR_STRING, date_default_timezone_get())));
			break;
			case 'action':
			break;
			default:
				$td->appendElement('plaintext', array(), $row[$key], true);
			break;
		}
	}
}
if (count($records) == 0){
	$tbl->appendElement('tr')->appendElement('td', array('colspan'=>count($arr_flds)), Labels::getLabel('LBL_No_Records_Found',$adminLangId));
}
echo $tbl->getHtml();
$postedData['page']=$page;
echo FatUtility::createHiddenFormFromData ( $postedData, array (
		'name' => 'frmAbandonedCartSearchPaging' 
) );
$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'pageSize'=>$pageSize,'recordCount'=>$recordCount,'adminLangId'=>$adminLangId);
$this->includeTemplate('_partial/pagination.php', $pagingArr,false);
?>
