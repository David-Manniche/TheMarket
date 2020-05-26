<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php if (!empty($zones)) {
    foreach ($zones as $zone) {
        $zoneId = $zone['shipzone_id'];
        $locationData = (isset($zoneLocations[$zoneId])) ? $zoneLocations[$zoneId] : array();
        $countryNames = array_column($locationData, 'country_name');
        $countryNames = array_unique($countryNames);
        $zoneIds = array_column($locationData, 'shiploc_zone_id');
        if (in_array(-1, $zoneIds)) {
            $countryNames = array(Labels::getLabel("LBL_REST_OF_THE_WORLD", $siteLangId));
        }
        $shipProZoneId = $zone['shipprozone_id'];
        $shipRates = (isset($shipRatesData[$shipProZoneId])) ? $shipRatesData[$shipProZoneId] : array(); ?>
<div class="py-4 border-bottom">
	<div id="" class="row justify-content-between my-4">
		<div class="col">
			<div class="row no-gutters">
				<div class="col-auto mr-3">
					<span class="box-icon"><i class="fa fa-globe icon">
						</i></span>
				</div>
				<div class="col">
					<h6 class="font-bold"><?php echo $zone['shipzone_name']?>
					</h6>
					<p class="mb-0">
						<span><?php echo implode(', ', $countryNames); ?></span>
					</p>
				</div>
			</div>
		</div>
		<div class="col-auto">
			<ul class="actions">
				<li>
					<a href="javascript:0;"
						onClick="zoneForm(<?php echo $profile_id; ?>, <?php echo $zone['shipzone_id']?>)"
						title="<?php echo Labels::getLabel("LBL_Edit_Zone", $siteLangId); ?>"><i
							class="fa fa-edit"></i></a>
				</li>
				<li>
					<a href="javascript:0;"
						onClick="deleteZone(<?php echo $shipProZoneId?>)"
						title="<?php echo Labels::getLabel("LBL_Delete_Zone", $siteLangId); ?>"><i
							class="fa fa-trash"></i></a>
				</li>
				<li>
					<a href="javascript:void(0)"
						title="<?php echo Labels::getLabel("LBL_Add_Rates", $siteLangId); ?>"
						onclick="addEditShipRates(<?php echo $shipProZoneId; ?>, 0);"><i
							class="fa fa-plus-square"></i></a>
				</li>
			</ul>
		</div>
	</div>
	<?php if (!empty($shipRates)) { ?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th><?php echo Labels::getLabel("LBL_Rate_Name", $siteLangId); ?>
				</th>
				<th><?php echo Labels::getLabel("LBL_Conditions", $siteLangId); ?>
				</th>
				<th><?php echo Labels::getLabel("LBL_Cost", $siteLangId); ?>
				</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($shipRates as $rate) { ?>
			<tr>
				<td><?php echo $rate['shiprate_rate_name'];?>
				</td>
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
				<td><?php echo CommonHelper::displayMoneyFormat($rate['shiprate_cost']);?>
				</td>
				<td>
					<ul class="actions">
						<li>
							<a href="javascript:0;"
								onclick="addEditShipRates(<?php echo $rate['shiprate_shipprozone_id']?>, <?php echo $rate['shiprate_id']?>);"
								title="<?php echo Labels::getLabel("LBL_Edit", $siteLangId); ?>"><i
									class="fa fa-edit"></i></a>
						</li>
						<li>
							<a href="javascript:0;"
								onClick="deleteRate(<?php echo $rate['shiprate_id']?>)"
								title="<?php echo Labels::getLabel("LBL_Delete", $siteLangId); ?>"><i
									class="fa fa-trash"></i></a>
						</li>
					</ul>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php } ?>
	<!--<div class="row">
		<div class="col"><button type="button" class="btn btn-secondary" onclick="addEditShipRates(<?php echo $shipProZoneId; ?>,
	0);"><i class="ion-ios-plus-empty icon"></i> <?php echo Labels::getLabel("LBL_Add_Rate", $siteLangId); ?></button>
</div>
</div> -->
</div>
<?php
    }
}
