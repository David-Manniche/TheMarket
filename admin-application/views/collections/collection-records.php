<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="box--scroller">
<ul class="columlist links--vertical" id="collection-record">
<?php if($collectionRecords){
	$lis= '';
	foreach($collectionRecords as $record){
		$lis .= '<li id="collection-record' . $record['record_id'] . '"><span class="left	"><a href="javascript:void(0)" title="Remove" onClick="removeCollectionRecord('.$collectionId.','.$record['record_id'].');"><i class=" icon ion-close" data-record-id="' . $record['record_id'] . '"></i></a></span>';
		$lis .= '<span>' . $record['record_title'].'<input type="hidden" value="'.$record['record_id'].'"  name="collection_selprod[]"></span></li>';
	}
	echo $lis;
} ?>
</ul>
</div>