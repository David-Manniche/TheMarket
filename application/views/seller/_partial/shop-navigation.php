<?php  defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$inactive = (0 == $shop_id) ? 'fat-inactive' : '';
?>
<div class="tabs ">
    <ul class="arrowTabs">
        <li
            class="<?php echo !empty($action) && $action == 'shopForm' ? 'is-active' : '';?>">
            <a href="javascript:void(0)" onClick="shopForm()"><?php echo Labels::getLabel('LBL_General', $siteLangId); ?></a>
        </li>
        <li class="<?php echo $inactive; echo (!empty($formLangId) ? 'is-active' : '') ; ?>">
            <a class="<?php echo $formLangId?>" href="javascript:void(0);" <?php echo (0 < $shop_id) ? "onclick='shopLangForm(" . $shop_id . "," . FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1) . ");'" : ""; ?>>
                <?php echo Labels::getLabel('LBL_Language_Data', $siteLangId); ?>
            </a>
        </li>
        <li class="<?php if ((!empty($action) && ($action == 'returnAddressForm' || $action == 'returnAddressLangForm'))) {
            echo 'is-active';
        } ?>"><a href="javascript:void(0);" onClick="returnAddressForm()"><?php echo Labels::getLabel('LBL_Return_Address', $siteLangId);?></a>
        </li>
        <?php /* <li class="<?php echo !empty($action) && ($action=='shopTemplate' || $action=='shopThemeColor')?'is-active' : ''; echo $inactive?>"><a href="javascript:void(0)" <?php if($shop_id>0){?> onClick="shopTemplates(this)"
            <?php }?>><?php echo Labels::getLabel('LBL_Layout',$siteLangId); ?></a></li> */ ?>
        <li class="<?php echo !empty($action) && $action == 'shopMediaForm' ? 'is-active' : ''; echo $inactive?>">
            <a href="javascript:void(0)" <?php if ($shop_id > 0) {
            ?>
                onClick="shopMediaForm(this)"
                <?php
        } ?>> <?php echo Labels::getLabel('LBL_Media', $siteLangId); ?></a>
        </li>
        <li
            class="<?php echo !empty($action) && ($action == 'shopCollections') ? 'is-active' : ''; ?>">
            <a href="javascript:void(0)" <?php if ($shop_id > 0) {
            ?>
                onClick="shopCollections(this)"
                <?php
        } ?>><?php echo Labels::getLabel('LBL_COLLECTIONS', $siteLangId); ?></a>
        </li>
        <li
            class="<?php echo !empty($action) && ($action == 'socialPlatforms') ? 'is-active' : ''; ?>">
            <a href="javascript:void(0)" <?php if ($shop_id > 0) {
            ?>
                onClick="socialPlatforms(this)"
                <?php
        } ?>><?php echo Labels::getLabel('LBL_SOCIAL_PLATFORMS', $siteLangId); ?></a>
        </li>
    </ul>
</div>