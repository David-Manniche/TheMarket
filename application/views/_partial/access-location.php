<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;
$location = $frm->getField('location');
$location->developerTags['noCaptionTag'] = true;
$location->addFieldTagAttribute("id", "ga-autoComplete");
$location->addFieldTagAttribute("title", Labels::getLabel('LBL_ENTER_YOUR_LOCATION', $siteLangId));
$location->addFieldTagAttribute("placeholder", Labels::getLabel('LBL_ENTER_MANUALLY_?', $siteLangId));
?>
<div class="cols--group">
    <div class="box__head text-center">
        <h5>
            <?php echo Labels::getLabel('LBL_ALLOW_"LOCATIONACCESS"_TO_ACCESS_YOUR_LOCATION_WHILE_YOU_ARE_USING_THE_WEBSITE?', $siteLangId); ?>
        </h5>
    </div>
    <div class="box__body">
        <p class="text-center"><?php echo Labels::getLabel('LBL_WE_NEED_TO_ACCESS_YOUR_LOCATION!', $siteLangId); ?></p>
        <div class="gap"></div>
        <div class="row no-gutters">
            <div class="col-auto mr-2">
                <button class="btn btn-primary btn-block" type="button" name="btn_submit" onclick="loadGeoLocation()" title="<?php echo Labels::getLabel('LBL_ALLOW', $siteLangId); ?>">
                    <i class="icn">
                        <svg class="svg">
                            <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#gps" href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#gps"></use>
                        </svg>
                    </i>
                </button>
            </div>
            <div class="col">
                <?php echo $frm->getFormHtml(); ?>
            </div>
        </div>
    </div>
</div>
