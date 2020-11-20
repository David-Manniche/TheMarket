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
<div class="portlet">
    <form onsubmit="setupZone(this); return(false);" method="post" class="web_form form_horizontal" id="shippingZoneFrm">
        <div class="portlet__head">
            <div class="portlet__head-label">
                <h3 class="portlet__head-title"><?php echo Labels::getLabel('LBL_Zone_Setup', $adminLangId); ?>
                </h3>
            </div>
        </div>
        <div class="portlet__body">
            <input type="hidden" name="shipprozone_id" value="<?php echo (!empty($zone_data)) ? $zone_data['shipprozone_id'] : 0; ?>">
            <input type="hidden" name="shipzone_id" value="<?php echo $zone_id; ?>">
            <input type="hidden" name="shipzone_profile_id" value="<?php echo $profile_id; ?>">
            <!--<input type="hidden" name="selected_ship_zone"> -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb-20 zone-main-field--js">
                        <input type="text" placeholder="<?php echo Labels::getLabel("LBL_Zone_Name", $adminLangId); ?>" name="shipzone_name" class="form-control shipzone_name" value="<?php echo (!empty($zone_data)) ? $zone_data['shipzone_name'] : ''; ?>" required>
                        <span class="form-text text-muted"><?php echo Labels::getLabel("LBL_Customers_will_not_see_this.", $adminLangId); ?></span>
                    </div>
                </div>
            </div>
            <div class="row simplebar-resize-wrapper mb-20">
                <div class="col-sm-12">
                    <div class="field-wraper mb-4">
                        <div class="field_cover">
                            <label>
                                <span class="checkbox" data-zoneid="-1"><input type="checkbox" name="rest_of_the_world" value="-1" class="checkbox_zone_-1" <?php echo (in_array(-1, $zoneIds)) ? 'checked' : ''; ?> <?php echo (in_array(-1, $exZoneIds)) ? 'disabled' : ''; ?>><i class="input-helper"></i></span><?php echo Labels::getLabel("LBL_REST_OF_THE_WORLD", $adminLangId); ?>
                            </label>
                        </div>
                    </div>
                    <div class="checkbox_container--js">
                        <?php
                        if (!empty($zones)) {
                            foreach ($zones as $zone) {
                                $countCounties = 0;
                                if (!empty($zoneCountries)) {
                                    $cZoneCountries = (isset($zoneCountries[$zone['zone_id']])) ? $zoneCountries[$zone['zone_id']] : array();
                                    $countCounties = count(array_unique($cZoneCountries));
                                }
                                $countries = (isset($zone['countries'])) ? $zone['countries'] : array();
                                $totalCountries = count($countries); ?>
                                <div class="field-wraper">
                                    <div class="field_cover">
                                        <label>
                                            <span class="checkbox zone--js" data-zoneid="<?php echo $zone['zone_id']; ?>">
												<input type="checkbox" name="shiploc_zone_ids[]" value="<?php echo $zone['zone_id']; ?>" class="countries-js checkbox_zone_<?php echo $zone['zone_id']; ?>" <?php echo ($countCounties == $totalCountries && $countCounties != 0) ? 'checked' : ''; ?>>
												<i class="input-helper"></i>
											</span><?php echo $zone['zone_name']; ?>
                                        </label>
                                    </div>
                                </div>
                                <?php
                                if (!empty($countries)) { ?>
                                    <ul class="child-checkbox-ul zone_<?php echo $zone['zone_id']; ?>">
                                        <?php foreach ($countries as $country) {
                                            $statesCount = $country['state_count'];
                                            $countryId = $country['country_id'];
                                            $disabled = '';
                                            $checked = '';
                                            $countryStates = [];
                                            //$exCountryStates = [];
                                            if (!empty($countryStatesArr) && isset($countryStatesArr[$countryId])) {
                                                $countryStates = $countryStatesArr[$countryId];
                                            }
                                            if (!empty($countryStates) && in_array('-1', $countryStates)) {
                                                $checked = 'checked';
                                            }
                                            if (!empty($excludeCountryStates) && isset($excludeCountryStates[$countryId])) {
                                                //$exCountryStates = $excludeCountryStates[$countryId];
                                                $disabled = 'disabled';
                                            }
                                            /* if (!empty($exCountryStates) && in_array('-1', $exCountryStates)) {
                                                $disabled = 'disabled';
                                            } */ ?>
                                            <li>
                                                <div class="row no-gutters">
                                                    <div class="col">
                                                        <div class="field-wraper">
                                                            <div class="field_cover">
                                                                <label>
                                                                    <span class="checkbox country--js " data-countryid="<?php echo $countryId; ?>" data-statecount="<?php echo $statesCount; ?>">
                                                                        <input type="checkbox" name="shiploc_country_ids[]" value="<?php echo $zone['zone_id']; ?>-<?php echo $countryId; ?>" class="checkbox_country_<?php echo $countryId; ?>" <?php echo $checked; ?>><i class="input-helper"></i>
                                                                    </span>
                                                                    <?php echo $country['country_identifier']; ?>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-auto mr-3">
                                                        <?php if ($statesCount > 0) { ?>
                                                            <a class="link font-bolder link_<?php echo $countryId; ?> containChild-js" data-toggle="collapse" href="#state_list_<?php echo $countryId; ?>" role="button" aria-expanded="false" aria-controls="state_list_<?php echo $countryId; ?>" data-countryid="<?php echo $countryId; ?>" data-loadedstates="0" onclick="getStates(<?php echo $countryId . ',' . $zone['zone_id'] . ',' . $profile_id; ?>);">
                                                                <span class="statecount--js selectedStateCount--js_<?php echo $countryId; ?> " data-totalcount="<?php echo $statesCount; ?>">0</span>
                                                                <?php echo Labels::getLabel("LBL_of", $adminLangId); ?>
                                                                <span class="totalStates "><?php echo $statesCount; ?></span>
                                                                <span class="ion-ios-arrow-down icon"></span>
                                                            </a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="collapse" id="state_list_<?php echo $countryId; ?>">
                                                </div>
                                            </li>
                                        <?php
                                        } ?>
                                    </ul>
                                <?php } ?>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="portlet__foot">
            <div class="row">
                <div class="col-md-12">
                    <?php 
                        $lbl = (0 < $zone_id ? Labels::getLabel("LBL_UPDATE_ZONE", $adminLangId) : Labels::getLabel("LBL_ADD_ZONE", $adminLangId));
                    ?>
                    <input type="submit" name="btn_submit" value="<?php echo $lbl; ?>">
                    <!--<input type="button" name="cancel" onClick="searchProductsSection(<?php echo $profile_id; ?>);" value="<?php echo Labels::getLabel("LBL_Cancel", $adminLangId); ?>">-->
                </div>
            </div>
        </div>
    </form>
</div>
<?php if (0 < $zone_id) { ?>
    <script>
        $(".containChild-js").each(function(){
            var dropStateElement = $(this);
            dropStateElement.click();
            var countryId = $(this).data("countryid");
            $("#state_list_" + countryId).addClass('d-none');
            setTimeout(function(){
                if (0 < $("#state_list_" + countryId + " .state--js:checked").length) {
                    $("#state_list_" + countryId).removeClass('d-none');
                } else {
                    $("#state_list_" + countryId).removeClass('d-none show');
                }
            }, 500);
        });
    </script>
<?php } ?>
<script>
    /* $(document).on('keyup', "input[name='shipzone_name']", function() {
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