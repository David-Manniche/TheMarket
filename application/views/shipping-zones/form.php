<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$zoneIds = [];
$countryStatesArr = [];
$zoneCountries = [];
if (!empty($zoneLocations)) {
    $zoneIds = array_column($zoneLocations, 'shiploc_zone_id');
    $zoneIds = array_unique(array_map('intval', $zoneIds));
    foreach ($zoneLocations as $location) {
        $selectedCountryId = $location['shiploc_country_id'];
        $selectedStateId = $location['shiploc_state_id'];
        $selectedZoneId = $location['shiploc_zone_id'];
        $zoneCountries[$selectedZoneId][] = $selectedCountryId;
        $countryStatesArr[$selectedCountryId][] = $selectedStateId;
    }
}
$excludeCountryStates = [];
$exZoneIds = [];

if (!empty($excludeLocations)) {
    $exZoneIds = array_column($excludeLocations, 'shiploc_zone_id');
    $exZoneIds = array_unique(array_map('intval', $exZoneIds));
    foreach ($excludeLocations as $exLocation) {
        $disableCountryId = $exLocation['shiploc_country_id'];
        $disableStateId = $exLocation['shiploc_state_id'];
        $excludeCountryStates[$disableCountryId][] = $disableStateId;
    }
}
?>
<div class="cards-header">
    <h5 class="cards-title"><?php echo Labels::getLabel('LBL_Zone_Setup', $siteLangId); ?></h5>
</div>
<div class="cards-content">
    <div class="row">
        <div class="col-md-12">
            <form onsubmit="setupZone(this); return(false);" method="post" class="form" id="shippingZoneFrm">
                <input type="hidden" name="shipprozone_id" value="<?php echo (!empty($zone_data)) ? $zone_data['shipprozone_id'] : 0; ?>">
                <input type="hidden" name="shipzone_user_id" value="<?php echo $userId; ?>">
                <input type="hidden" name="shipzone_id" value="<?php echo $zone_id; ?>">
                <input type="hidden" name="shipzone_profile_id" value="<?php echo $profile_id; ?>">
                <!--<input type="hidden" name="selected_ship_zone"> -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group zone-main-field--js">
                            <input type="text" placeholder="<?php echo Labels::getLabel("LBL_Zone_Name", $siteLangId); ?>" name="shipzone_name" class="form-control shipzone_name" value="<?php echo (!empty($zone_data)) ? $zone_data['shipzone_name'] : ''; ?>" required>
                            <span class="form-text text-muted"><?php echo Labels::getLabel("LBL_Customers_will_not_see_this.", $siteLangId); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="checkbox_container--js">
                            <ul class="list-country-zone">
                                <li>
                                    <div class="row no-gutters zone-row">
                                        <div class="col">
                                            <div class="field-wraper">
                                                <div class="field_cover">
                                                    <label>
                                                        <span class="checkbox" data-zoneid="-1"><input type="checkbox" name="rest_of_the_world" value="-1" class="checkbox_zone_-1" <?php echo (in_array(-1, $zoneIds)) ? 'checked' : ''; ?> <?php echo (in_array(-1, $exZoneIds)) ? 'disabled' : ''; ?>><i class="input-helper"></i><?php echo Labels::getLabel("LBL_REST_OF_THE_WORLD", $siteLangId); ?></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php if (!empty($zones)) {
                                    foreach ($zones as $zone) {
                                        $countCounties = 0;
                                        if (!empty($zoneCountries)) {
                                            $cZoneCountries = (isset($zoneCountries[$zone['zone_id']])) ? $zoneCountries[$zone['zone_id']] : array();
                                            $countCounties = count(array_unique($cZoneCountries));
                                        }

                                        $countries = (isset($zone['countries'])) ? $zone['countries'] : array();
                                        $totalCountries = count($countries); ?>
                                        <li>
                                            <div class="row no-gutters zone-row">
                                                <div class="col">
                                                    <div class="field-wraper">
                                                        <div class="field_cover">
                                                            <label>
                                                                <span class="checkbox zone--js" data-zoneid="<?php echo $zone['zone_id']; ?>"><input type="checkbox" name="shiploc_zone_ids[]" value="<?php echo $zone['zone_id']; ?>" class="checkbox_zone_<?php echo $zone['zone_id']; ?>" <?php echo (in_array($zone['zone_id'], $exZoneIds)) ? 'disabled' : ''; ?> <?php echo ($countCounties == $totalCountries && $countCounties != 0) ? 'checked' : ''; ?>><i class="input-helper"></i><?php echo $zone['zone_name']; ?></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if (!empty($countries)) { ?>
                                                    <div class="col-auto">
                                                        <a class="btn btn-sm" data-toggle="collapse" href="#countries_list_<?php echo $zone['zone_id']; ?>" role="button" aria-expanded="false" aria-controls="countries_list_<?php echo $zone['zone_id']; ?>"><span class="fa fa-angle-down" aria-hidden="true"></span> </a>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <?php if (!empty($countries)) { ?>
                                                <ul class="child-checkbox-ul zone_<?php echo $zone['zone_id']; ?>">
                                                    <?php foreach ($countries as $country) {
                                                        $statesCount = $country['state_count'];
                                                        $countryId = $country['country_id'];
                                                        $disabled = '';
                                                        $checked = '';
                                                        $countryStates = [];
                                                        if (!empty($countryStatesArr) && isset($countryStatesArr[$countryId])) {
                                                            $countryStates = $countryStatesArr[$countryId];
                                                        }
                                                        if (!empty($countryStates) && in_array('-1', $countryStates)) {
                                                            $checked = 'checked';
                                                        }
                                                        if (!empty($excludeCountryStates) && isset($excludeCountryStates[$countryId])) {
                                                            $disabled = 'disabled';
                                                        } ?>
                                                        <li class="collapse" id="countries_list_<?php echo $zone['zone_id']; ?>">
                                                            <div class="row no-gutters">
                                                                <div class="col">
                                                                    <div class="field-wraper">
                                                                        <div class="field_cover">
                                                                            <label>
                                                                                <span class="checkbox country--js " data-countryid="<?php echo $countryId; ?>" data-statecount="<?php echo $statesCount; ?>"><input type="checkbox" name="shiploc_country_ids[]" value="<?php echo $zone['zone_id']; ?>-<?php echo $countryId; ?>" class="checkbox_country_<?php echo $countryId; ?>" <?php echo $checked; ?> <?php echo $disabled; ?>><i class="input-helper"></i><?php echo $country['country_identifier']; ?></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-auto">
                                                                    <?php if ($statesCount > 0) { ?>
                                                                        <a class="btn  btn-sm link_<?php echo $countryId; ?>" data-toggle="collapse" href="#state_list_<?php echo $countryId; ?>" role="button" aria-expanded="false" aria-controls="state_list_<?php echo $countryId; ?>" data-countryid="<?php echo $countryId; ?>" data-loadedstates="0" onclick="getStates(<?php echo $countryId . ',' . $zone['zone_id'] . ',' . $profile_id; ?>);"><span class="statecount--js selectedStateCount--js_<?php echo $countryId; ?> " data-totalcount="<?php echo $statesCount; ?>">0</span>
                                                                            <?php echo Labels::getLabel("LBL_of", $siteLangId); ?>
                                                                            <span class="totalStates "><?php echo $statesCount; ?></span>
                                                                            <span class="fa fa-angle-down" aria-hidden="true"></span>
                                                                        </a>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="collapse" id="state_list_<?php echo $countryId; ?>">
                                                            </div>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            <?php } ?>
                                        </li>
                                <?php }
                                } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-lg-5">
                        <input class="btn btn-primary btn-block" type="submit" name="btn_submit" value="<?php echo Labels::getLabel("LBL_Add_Zone", $siteLangId); ?>">
                    </div>

                    <div class="col-lg-5">
                        <input class="btn btn-outline-primary btn-block" type="button" name="cancel" onClick="clearForm();" value="<?php echo Labels::getLabel("LBL_Cancel", $siteLangId); ?>">
                    </div>


                </div>
            </form>
        </div>
    </div>
</div>
<script>
    /*  $(document).on('keyup', "input[name='shipzone_name']", function() {
        var currObj = $(this);
        var parentForm = currObj.closest('form').attr('id');
        $("#" + parentForm + " input[name='shipzone_id']").val(0);
        $('.country--js input[type="checkbox"]').prop('checked', false);
        $('.zone--js input[type="checkbox"]').prop('checked', false);
        if ('' != currObj.val()) {
            currObj.siblings('ul.dropdown-menu').remove();
            currObj.autocomplete({
                'source': function(request, response) {
                    $.ajax({
                        url: fcom.makeUrl('ShippingZones', 'autoCompleteZone'),
                        data: {
                            fIsAjax: 1,
                            keyword: currObj.val()
                        },
                        dataType: 'json',
                        type: 'post',
                        success: function(json) {
                            response($.map(json, function(item) {
                                return {
                                    label: item['name'],
                                    value: item['name'],
                                    id: item['id']
                                };
                            }));
                        },
                    });
                },
                appendTo: '.zone-main-field--js',
                select: function(event, ui) {
                    $("#" + parentForm + " input[name='shipzone_id']").val(ui.item.id);
                    getZoneLocation(ui.item.id);
                }
            });
        } else {
            $("#" + parentForm + " input[name='shipzone_id']").val(0);
        }
    }); */
</script>