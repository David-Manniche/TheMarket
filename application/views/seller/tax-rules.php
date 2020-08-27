<?php defined('SYSTEM_INIT') or die('Invalid Usage.');?>
<?php $this->includeTemplate('_partial/seller/sellerDashboardNavigation.php'); ?>
<main id="main-area" class="main" role="main">
    <div class="content-wrapper content-space">
        <div class="content-header row">
            <div class="col">
                <?php $this->includeTemplate('_partial/dashboardTop.php'); ?>
                <h2 class="content-header-title"><?php echo Labels::getLabel('LBL_Tax_Rules', $siteLangId); ?></h2>
            </div>
            <div class="col-auto">
                <div class="btn-group">
                    <a href="<?php echo UrlHelper::generateUrl('seller', 'taxCategories');?>" class="btn btn-outline-primary btn-sm">
                    <?php echo Labels::getLabel('LBL_Back_To_Tax_Categories', $siteLangId)?>
                    </a>
                </div>
            </div>
        </div>
        <div class="content-body">
            <?php if (!empty($rulesData)) { ?>
                <?php foreach($rulesData as $rule) {
                    $combinedData = [];
                    if (!empty($combinedRulesDetails) && isset($combinedRulesDetails[$rule['taxrule_id']])) {
                        $combinedData = $combinedRulesDetails[$rule['taxrule_id']];
                    }
                    $ruleId = $rule['taxrule_id'];
                    $locations = (!empty($ruleLocations) && isset($ruleLocations[$ruleId]))?$ruleLocations[$ruleId]:array();
                    $countryIds = [];
                    $stateIds = [];
                    $typeIds = [];
                    if (!empty($locations)) {
                        $countryNames = array_column($locations, 'country_name');
                        $countryNames = array_unique($countryNames);
                        $stateNames = array_column($locations, 'state_name');
                        $stateNames = array_unique($stateNames);
                        $typeIds = array_column($locations, 'taxruleloc_type');
                        $typeIds = array_unique($typeIds);
                    }
                ?>
                <div class="row mb-4">
                        <div class="col-lg-12">
                        <div class="cards">
                            <div class="cards-header">
                                <h5 class="cards-title"><?php echo Labels::getLabel('LBL_Rule', $siteLangId); ?>: <?php echo $rule['taxrule_name'];?></h5>
                            </div>
                            <div class="cards-content">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 mb-4">
                                        <div class="info--order">
                                            <p><strong><?php echo Labels::getLabel('LBL_Rax_Rate', $siteLangId); ?>: </strong><?php echo $rule['taxrule_rate'];?></p>
                                            <?php if (!empty($combinedData) && $rule['taxrule_is_combined'] > 0) { ?>
                                            <p><strong><?php echo Labels::getLabel('LBL_Combined_Taxes', $siteLangId); ?></strong></h6>
                                            <?php foreach ($combinedData as $comData) { ?>
                                                <p><strong><?php echo $comData['taxruledet_name'][$siteLangId];?>: </strong><?php echo $comData['taxruledet_rate'];?></p>
                                            <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 mb-4">
                                        <div class="info--order">
                                            <p><strong><?php echo Labels::getLabel('LBL_Country', $siteLangId); ?>: </strong><?php echo (!empty($countryNames[0])) ? implode(', ', $countryNames) : Labels::getLabel('LBL_Rest_of_the_world', $siteLangId); ?></p>
                                            <p><strong><?php echo Labels::getLabel('LBL_States', $siteLangId); ?>: </strong><?php echo TaxRule::getTypeOptions($siteLangId)[$typeIds[0]]; ?> <?php echo (!empty($stateNames[0])) ? ': '.implode(', ', $stateNames) : ''; ?></p><span class="gap"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }?>
            <?php } else {?>
                
            <?php }?>
        </div>
    </div>
</main>
