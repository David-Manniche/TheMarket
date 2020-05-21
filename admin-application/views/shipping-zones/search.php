<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php if (!empty($zones)) {
foreach ($zones as $zone) {
	$zoneId = $zone['shipzone_id'];
	$locationData = (isset($zoneLocations[$zoneId])) ? $zoneLocations[$zoneId] : array();
	$countryNames = array_column($locationData, 'country_name');
	$countryNames = array_unique($countryNames);
	$zoneIds = array_column($locationData, 'shiploc_zone_id');
	if (in_array(-1, $zoneIds)) {
		$countryNames = array(Labels::getLabel("LBL_REST_OF_THE_WORLD", $adminLangId));
	}
	$shipProZoneId = $zone['shipprozone_id'];
	$shipRates = (isset($shipRatesData[$shipProZoneId])) ? $shipRatesData[$shipProZoneId] : array();
?>
<div class="py-4 border-bottom">
	<div id="" class="row justify-content-between my-4">
        <div class="col">
            <div class="row no-gutters">
                <div class="col-auto mr-3">
					<span class="box-icon"><i class="icon-svg">
						<svg class="svg">
							<use xlink:href="<?php echo CONF_WEBROOT_URL; ?>/images/retina/sprite.svg#world" href="<?php echo CONF_WEBROOT_URL; ?>/images/retina/sprite.svg#world"></use>
                         </svg>
					</i></span>
				</div>
                <div class="col">
                    <h6 class="font-bold"><?php echo $zone['shipzone_name']?></h6>
                    <p class="mb-0">
					<span><?php echo implode(', ', $countryNames); ?></span>
					</p>
                </div>
            </div>
        </div>
		<div class="col-auto">
            <div class="dropdown">
				<a data-toggle="dropdown" aria-expanded="" class="btn btn-sm btn-clean btn-icon btn-icon-md"><i class="ion-android-more-vertical icon"></i></a>
                <div x-placement="bottom-end" class="dropdown-menu dropdown-menu-right">
					<a href="javascript:0;" onClick = "zoneForm(<?php echo $profile_id;?>, <?php echo $zone['shipzone_id']?>)" class="dropdown-item"><?php echo Labels::getLabel("LBL_Edit", $adminLangId); ?></a> 
					<a href="javascript:0;" class="dropdown-item" onClick = "deleteZone(<?php echo $shipProZoneId?>)"><?php echo Labels::getLabel("LBL_Delete", $adminLangId); ?></a>
				</div>
            </div>
        </div>
    </div>
	<?php if (!empty($shipRates)) { ?>
    <table class="table table-bordered mb-3">
        <thead>
            <tr>
                <th><?php echo Labels::getLabel("LBL_Rate_Name", $adminLangId); ?></th>
                <th><?php echo Labels::getLabel("LBL_Conditions", $adminLangId); ?></th>
                <th><?php echo Labels::getLabel("LBL_Cost", $adminLangId); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
		<?php foreach ($shipRates as $rate) { ?>
            <tr>
                <td><?php echo $rate['shiprate_rate_name'];?></td>
                <td>
				<?php if ($rate['shiprate_condition_type'] > 0) { 
					if ($rate['shiprate_condition_type'] == ShippingRate::CONDITION_TYPE_PRICE) {
						echo CommonHelper::displayMoneyFormat($rate['shiprate_min_val']).' - '. CommonHelper::displayMoneyFormat($rate['shiprate_max_val']);
					} else {
						echo $rate['shiprate_min_val'].' - '. $rate['shiprate_max_val'];
					}
				} else {
					echo'—';
				}
				?>
				</td>
                <td><?php echo CommonHelper::displayMoneyFormat($rate['shiprate_cost']);?></td>
                <td>
					<div class="dropdown">
						<a data-toggle="dropdown" aria-expanded="false" class="btn btn-sm btn-clean btn-icon btn-icon-md"><i class="ion-android-more-vertical icon"></i></a>
						<div class="dropdown-menu dropdown-menu-right" style="">
							<a href="javascript:0;" onclick="addEditShipRates(<?php echo $rate['shiprate_shipprozone_id']?>, <?php echo $rate['shiprate_id']?>);" class="dropdown-item"><?php echo Labels::getLabel("LBL_Edit", $adminLangId); ?></a>
							<a href="javascript:0;" onClick = "deleteRate(<?php echo $rate['shiprate_id']?>)" class="dropdown-item"><?php echo Labels::getLabel("LBL_Delete", $adminLangId); ?></a>
						</div>
					</div>
                </td>
            </tr>
		<?php } ?>	
        </tbody>
	</table>
	<?php } ?>
	<div class="row">
		<div class="col"><button type="button" class="btn btn-secondary" onclick="addEditShipRates(<?php echo $shipProZoneId; ?>, 0);"><i class="ion-ios-plus-empty icon"></i> <?php echo Labels::getLabel("LBL_Add_Rate", $adminLangId); ?></button></div>
	</div>
</div>
<?php 
}
} ?>