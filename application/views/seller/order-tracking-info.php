<?php defined('SYSTEM_INIT') or die('Invalid Usage . ');
if ( $trackingInfo['meta']['code'] != 200 ){ ?>
	<h4><?php echo $trackingInfo['meta']['code'].' : '.$trackingInfo['meta']['message']; ?></h4>
<?php }else{  ?>

		<div><?php echo Labels::getLabel('LBL_Tracking_Number', $siteLangId).": ".$trackingInfo['data']['tracking']['tracking_number'];  ?></div>
		<div><?php echo Labels::getLabel('LBL_Courier', $siteLangId).": ".$trackingInfo['data']['tracking']['slug'];  ?></div>
		<div><?php echo Labels::getLabel('LBL_status', $siteLangId).": ".$trackingInfo['data']['tracking']['tag'];  ?></div>
		<?php 
		if(!empty($trackingInfo['data']['tracking']['order_promised_delivery_date'])) { 
			echo "<div>".Labels::getLabel('LBL_order_promised_delivery_date', $siteLangId).": ".FatDate::format($trackingInfo['data']['tracking']['order_promised_delivery_date'])."</div>";
		} 
		
		if(!empty($trackingInfo['data']['tracking']['courier_tracking_link'])) { 
			echo "<div>".Labels::getLabel('LBL_courier_tracking_link', $siteLangId).": ".$trackingInfo['data']['tracking']['courier_tracking_link']."</div>";
		}
		
		if(!empty($trackingInfo['data']['tracking']['checkpoints'])) { 
			echo "<div><ul>";
			foreach($trackingInfo['data']['tracking']['checkpoints'] as $data){
				echo "<li class='mt-5'>";
				echo "<div>".FatDate::format($data['checkpoint_time'], true)."</div>";
				echo "<div>".$data['tag']."</div>";
				echo "<div>".$data['slug']."</div>";
				echo "<div>".$data['message']."</div>";
				echo "<div>";
				if(!empty($data['location'])){
					echo $data['location'].", ";
				}
				if(!empty($data['city'])){
					echo $data['city'].", ";
				}
				if(!empty($data['state'])){
					echo $data['state'].", ";
				}
				if(!empty($data['country_name'])){
					echo $data['country_name'].", ";
				}
				if(!empty($data['zip'])){
					echo $data['zip'];
				}
				echo "</div>";
				echo "</li>";
			
			}
			echo "</ul></div>";
		}
	} 
?>