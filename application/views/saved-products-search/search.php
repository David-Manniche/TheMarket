<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
if(!empty($arrListing)){?>
<div class="saved-search-list">
	<ul>
	  <?php foreach ($arrListing as $sn => $row){ ?>
		<li>
			<div class="detail-side">
				<div class="heading3"><?php echo ucfirst($row['pssearch_name']); ?></div>
				<div class="heading5">
					<?php 
						$str = '';
						foreach($row['search_items'] as $record){
							if(is_array($record['value'])){
								$str.= ' <strong>'.$record['label'].'</strong>: ';
								$listValues = '';								
								foreach($record['value'] as $list){
									$listValues.= $list.',';
								}
								$str.= rtrim($listValues,' , ').' |';
							}else{
								$str.= ' <strong>'.$record['label'].'</strong>: '.$record['value'].' |';
							}						
					}
					echo rtrim($str,'|');	
					?>
				</div>
				<div class="date"><?php echo FatDate::format($row['pssearch_added_on']); ?></div>
			</div>
			<div class="results-side">
				<strong><a href="<?php echo $row['search_url'];?>" class="btn btn--primary"><?php echo Labels::getLabel('LBL_View_results', $siteLangId); ?></a></strong>
			</div>
		</li>
	  <?php }?>
	</ul>
</div>
<?php  
	$pagingArr=array('pageCount'=>$pageCount,'page'=>$page,'recordCount'=>$recordCount,'siteLangId'=>$siteLangId);
	$this->includeTemplate('_partial/pagination.php', $pagingArr,false);
}else{
	$this->includeTemplate('_partial/no-record-found.php' , array('siteLangId'=>$siteLangId),false);
}?>