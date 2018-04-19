<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
$arr_flds = array(
		'listserial'=>Labels::getLabel('LBL_Sr_no.',$adminLangId),
		'testimonial_identifier'=>Labels::getLabel('LBL_Testimonial_Identifier',$adminLangId),	
		'testimonial_title'=>Labels::getLabel('LBL_Testimonial_Title',$adminLangId),
		'testimonial_active'=>Labels::getLabel('LBL_Status',$adminLangId),
		'action' => Labels::getLabel('LBL_Action',$adminLangId),
	);
$tbl = new HtmlElement('table', array('width'=>'100%', 'class'=>'table table-responsive table--hovered'));
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $val) {
	$e = $th->appendElement('th', array(), $val);
}

$sr_no = 0;
foreach ($arr_listing as $sn=>$row){
	$sr_no++;
	$tr = $tbl->appendElement('tr');
	$tr->setAttribute ("id",$row['testimonial_id']);

	if($row['testimonial_active'] != applicationConstants::ACTIVE) {
		/* $tr->setAttribute ("class","fat-inactive"); */
	}
	foreach ($arr_flds as $key=>$val){
		$td = $tr->appendElement('td');
		switch ($key){
			case 'listserial':
				$td->appendElement('plaintext', array(), $sr_no);
			break;
			case 'testimonial_active':
					$active = "";
					if($row['testimonial_active']) {
						$active = 'checked';
					}
					$statucAct = ($canEdit === true) ? 'toggleStatus(event,this)' : '';

					//$str = '<div class="checkbox-switch"><input '.$active.' type="checkbox" id="switch'.$row['testimonial_id'].'" value="'.$row['testimonial_id'].'" onclick="'.$statucAct.'"/><label for="switch'.$row['testimonial_id'].'">Toggle</label></div>';

					$str='<label class="statustab -txt-uppercase">                 
                     <input '.$active.' type="checkbox" id="switch'.$row['testimonial_id'].'" value="'.$row['testimonial_id'].'" onclick="'.$statucAct.'" class="switch-labels"/>
                                      	<i class="switch-handles"></i></label>';
					$td->appendElement('plaintext', array(), $str,true);
			break;
			case 'action':
				$ul = $td->appendElement("ul",array("class"=>"actions actions centered"));
				if($canEdit){
					//$li = $ul->appendElement("li");
					$li = $ul->appendElement("li",array('class'=>'droplink'));

					$li->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green','title'=>Labels::getLabel('LBL_Edit',$adminLangId)),'<i class="ion-android-more-horizontal icon"></i>', true);
              		$innerDiv=$li->appendElement('div',array('class'=>'dropwrap'));
              		$innerUl=$innerDiv->appendElement('ul',array('class'=>'linksvertical'));
              		$innerLiEdit=$innerUl->appendElement('li');
					$innerLiEdit->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green', 
					'title'=>Labels::getLabel('LBL_Edit',$adminLangId),"onclick"=>"editTestimonialFormNew(".$row['testimonial_id'].")"),Labels::getLabel('LBL_Edit',$adminLangId), 
					true);
					$innerLiDelete = $innerUl->appendElement("li");
					$innerLiDelete->appendElement('a', array('href'=>'javascript:void(0)', 'class'=>'button small green', 
					'title'=>Labels::getLabel('LBL_Delete',$adminLangId),"onclick"=>"deleteRecord(".$row['testimonial_id'].")"),Labels::getLabel('LBL_Delete',$adminLangId), 
					true);
				}
			break;
			default:
				$td->appendElement('plaintext', array(), $row[$key],true);
			break;
		}
	}
}
if (count($arr_listing) == 0){
	$tbl->appendElement('tr')->appendElement('td', array('colspan'=>count($arr_flds)), Labels::getLabel('LBL_No_Records_Found',$adminLangId));
}
echo $tbl->getHtml();