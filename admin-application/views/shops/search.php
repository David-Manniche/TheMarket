<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
$arr_flds = array(
		'listserial'=>Labels::getLabel('LBL_Sr._No',$adminLangId),
		'user_name'=>Labels::getLabel('LBL_Owner',$adminLangId),	
		'shop_identifier'=>Labels::getLabel('LBL_Name',$adminLangId),	
		'numOfReports'=>Labels::getLabel('LBL_Reports',$adminLangId),	
		'numOfReviews'=>Labels::getLabel('LBL_Reviews',$adminLangId),	
		'shop_featured'	=>	Labels::getLabel('LBL_Featured',$adminLangId),
		'shop_active'=>Labels::getLabel('LBL_Status',$adminLangId),
		'shop_created_on'=>Labels::getLabel('LBL_Created_on',$adminLangId),
		'shop_supplier_display_status'=>Labels::getLabel('LBL_Status_by_seller',$adminLangId),
		'action' => Labels::getLabel('LBL_Action',$adminLangId),
	);
$tbl = new HtmlElement('table', array('width'=>'100%', 'class'=>'table table-responsive table--hovered'));
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $val) {
	$e = $th->appendElement('th', array(), $val);
}

$sr_no = $page==1?0:$pageSize*($page-1);
foreach ($arr_listing as $sn=>$row){
	$sr_no++;
	$tr = $tbl->appendElement('tr', array('id' => $row['shop_id'], 'class' => '' ) );
	
	foreach ($arr_flds as $key=>$val){
		$td = $tr->appendElement('td');
		switch ($key){
			case 'listserial':
				$td->appendElement('plaintext', array(), $sr_no);
			break;
			case 'shop_supplier_display_status':
				$td->appendElement('plaintext', array(), $onOffArr[$row[$key]], true);
			break;
			case 'shop_active':
				//$td->appendElement('plaintext', array(), $activeInactiveArr[$row[$key]]);
				
				$active = "";
				if( $row['shop_active'] ) {
					$active = 'checked';
				}
				$statucAct = ( $canEdit === true ) ? 'toggleStatus(event,this)' : '';
				//$str = '<div class="checkbox-switch"><input '.$active.' type="checkbox" id="switch'.$row['shop_id'].'" value="'.$row['shop_id'].'" onclick="'.$statucAct.'"/><label for="switch'.$row['shop_id'].'">Toggle</label></div>';
				$str= '<label class="statustab -txt-uppercase">
					   <input '.$active.' type="checkbox" id="switch'.$row['shop_id'].'" value="'.$row['shop_id'].'" onclick="'.$statucAct.'" class="switch-labels"/>
                       <i class="switch-handles"></i></label>';
				$td->appendElement('plaintext', array(), $str,true);
				
			break;
			case 'shop_featured':
				$td->appendElement('plaintext', array(), applicationConstants::getYesNoArr($adminLangId)[$row[$key]], true );
			break;
			case 'numOfReports':
			if($canViewShopReports){
				$td->appendElement('a', array('href' => CommonHelper::generateUrl('ShopReports' ,'index' ,array($row['shop_id'])) ), $row[$key]);
			} else {
				$td->appendElement('plaintext', array(), $row[$key]);
			}
			break;
			case 'numOfReviews':
			if($canViewShopReports){
				$td->appendElement('a', array('href' => CommonHelper::generateUrl('ProductReviews' ,'index' ,array($row['shop_user_id'])) ), $row[$key]);
			} else {
				$td->appendElement('plaintext', array(), $row[$key]);
			}
			break;
			case 'shop_identifier':
				if( $row['shop_name'] != '' ){
					$td->appendElement('plaintext', array(), $row['shop_name'],true);
					$td->appendElement('br', array());
					$td->appendElement('plaintext', array(), '('.$row[$key].')',true);
				} else {
					$td->appendElement('plaintext', array(), $row[$key],true);
				}
				$td->appendElement( 'br', array() );
				$shopLink = CommonHelper::generateFullUrl("Shops", 'View', array($row['shop_id']), CONF_WEBROOT_FRONT_URL);
				$td->appendElement( 'plaintext', array(), 'Shop Url: <a href="'.$shopLink.'" target="_blank">'.$shopLink.'</a>', true  );
			break;
			case 'shop_created_on':
				$td->appendElement( 'plaintext', array(), FatDate::format($row[$key]) );
			break;
			case 'action':
				$ul = $td->appendElement( "ul",array("class"=>"actions actions--centered") );
				if( $canEdit ){
					$li = $ul->appendElement("li",array('class'=>'droplink'));
											
    			    $li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green','title'=>Labels::getLabel('LBL_Edit',$adminLangId)),'<i class="ion-android-more-horizontal icon"></i>', true);
              		$innerDiv=$li->appendElement('div',array('class'=>'dropwrap'));
              		$innerUl=$innerDiv->appendElement('ul',array('class'=>'linksvertical'));
              		$innerLi=$innerUl->appendElement('li');
					$innerLi->appendElement('a', array('href'=>'javascript:void(0)','class'=>'button small green','title'=>Labels::getLabel('LBL_Edit',$adminLangId),"onclick"=>"addShopForm(".$row['shop_id'].")"),Labels::getLabel('LBL_Edit',$adminLangId), true);
				}
			break;
			default:
				$td->appendElement('plaintext', array(), $row[$key]);
			break;
		}
	}
}
if (count($arr_listing) == 0){
	$tbl->appendElement('tr')->appendElement('td', array('colspan'=>count($arr_flds)), Labels::getLabel('LBL_No_Records_Found',$adminLangId));
}
echo $tbl->getHtml();
$postedData['page']=$page;
echo FatUtility::createHiddenFormFromData ( $postedData, array (
		'name' => 'frmShopSearchPaging'
) );
$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'recordCount'=>$recordCount,'adminLangId'=>$adminLangId);
$this->includeTemplate('_partial/pagination.php', $pagingArr,false);
?>