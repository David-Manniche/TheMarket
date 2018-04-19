<?php  defined('SYSTEM_INIT') or die('Invalid Usage.');
if (count($arr_listing) > 0){	
$arr_flds = array(
	'urp_points'	=>	Labels::getLabel('LBL_Points', $siteLangId),
	'urp_comments'	=>	Labels::getLabel('LBL_Description', $siteLangId),
	'urp_date_added' =>	Labels::getLabel('LBL_Added_Date', $siteLangId),
	'urp_date_expiry'	=>	Labels::getLabel('LBL_Expiry_Date', $siteLangId),
);

if($convertReward == 'coupon'){		
	$arr_flds = array_merge(array('select_option'=>''),$arr_flds);
}

$tbl = new HtmlElement('table', array('class'=>'table'));
$th = $tbl->appendElement('thead')->appendElement('tr',array('class' => 'hide--mobile'));
foreach ($arr_flds as $val) {
	$e = $th->appendElement('th', array(), $val);
}

$sr_no = 0;
foreach ($arr_listing as $sn => $row){
	$sr_no++;
	$tr = $tbl->appendElement('tr',array('class' =>'' ));
	
	foreach ($arr_flds as $key=>$val){
		$td = $tr->appendElement('td');
		switch ($key){
			case 'select_option':				
				$td->appendElement('plaintext', array(), '<span class="caption--td">'.$val.'</span><input class="rewardOptions-Js" type="checkbox" name="rewardOptions[]" value="'.$row['urp_id'].'">' , true);
			break;
			/* case 'urp_used':
				$yesNoArr = applicationConstants::getYesNoArr($siteLangId);
				if($row[$key] != applicationConstants::YES && $row['urp_date_expiry'] !='0000-00-00' && $row['urp_date_expiry'] < date('Y-m-d')){
					$td->appendElement('plaintext', array(), '<span class="caption--td">'.$val.'</span>'.Labels::getLabel('LBL_Expired',$siteLangId) , true);
				}else{
					$td->appendElement('plaintext', array(), '<span class="caption--td">'.$val.'</span>'.$yesNoArr[$row[$key]] , true);
				}
			break; */
			case 'urp_date_added':
				$td->appendElement('plaintext', array(), '<span class="caption--td">'.$val.'</span>'.FatDate::format($row[$key]) , true);
			break;
			case 'urp_date_expiry':
				$expiryDate = $row[$key];
				$expiryDate = ($expiryDate =='0000-00-00')?CommonHelper::displayNotApplicable($siteLangId,''):FatDate::format($row[$key]);
				$td->appendElement('plaintext', array(), '<span class="caption--td">'.$val.'</span>'.$expiryDate , true);
			break;			
			default:
				$td->appendElement('plaintext', array(), '<span class="caption--td">'.$val.'</span>'.$row[$key],true);
			break;
		}
	}
}
	echo $tbl->getHtml();	
}else{
	$this->includeTemplate('_partial/no-record-found.php' , array('siteLangId'=>$siteLangId),false);
}

$postedData['page'] = $page;
echo FatUtility::createHiddenFormFromData ( $postedData, array ('name' => 'frmRewardPointSearchPaging') );
$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'recordCount'=>$recordCount);
$this->includeTemplate('_partial/pagination.php', $pagingArr,false);