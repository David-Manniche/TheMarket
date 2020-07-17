<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); 
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;
$location = $frm->getField('location');
$location->developerTags['noCaptionTag'] = true;
$location->addFieldTagAttribute("id", "ga-autoComplete");
$location->addFieldTagAttribute("title", Labels::getLabel('LBL_ENTER_YOUR_LOCATION', $siteLangId));
$location->addFieldTagAttribute("placeholder", Labels::getLabel('LBL_ENTER_MANUALLY_?', $siteLangId));
?>

<div class="location-permission">
    <div class="location-permission_head">
        <h5>
            <?php echo Labels::getLabel('LBL_ALLOW_"LOCATIONACCESS"_TO_ACCESS_YOUR_LOCATION_WHILE_YOU_ARE_USING_THE_WEBSITE?', $siteLangId); ?>
        </h5>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
            magna aliqua. </p>
    </div>

    <div class="location-permission_body">        
            <a class="default" href="">
                <i class="icn"><img src="images/retina/location.svg" alt=""></i>
                <span class="location-name">Deliver in <strong>Chandigarh</strong></span>
            </a> 

        <div class="or"><span>Or</span></div>
        <div class="location-search">
            <input class="form-control" type="text" placeholder="Type your city (e.g Chennai, Pune)">
        </div>
    </div>
</div>
