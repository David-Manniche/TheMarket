<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<!-- <div class="tabs ">
    <ul>
        <li class="is-active"><a href="javascript:void(0);" onClick="searchAddresses()"><?php echo Labels::getLabel('LBL_My_Addresses', $siteLangId);?></a></li>
        <li><a href="javascript:void(0);" onClick="addAddressForm(0)"><?php echo Labels::getLabel('LBL_Add_new_address', $siteLangId);?></a></li>
    </ul>
</div> -->

<div class="container--addresses">
    <div class="row">
<?php if (!empty($addresses)) {
    if (count($addresses) == 1 && $addresses[0]['addr_is_default'] != 1) {
        $addresses[0]['addr_is_default'] = 1;
    }
    foreach ($addresses as $address) {
        $address['addr_title'] = ($address['addr_title'] == '') ? '&nbsp;' : $address['addr_title']; ?> <div class="col-lg-4 col-md-6 mb-4">
            <label class="address-block <?php echo ($address['addr_is_default']==1)?'is-active':''; ?>">
                <span class="radio">
                    <?php
                    $action = "setDefaultAddress(".$address['addr_id'].", event)";
                    if (1 == $address['addr_is_default']) {
                        $action = 'return false';
                    }
                    ?>
                    <input type="radio" <?php echo ($address['addr_is_default']==1)?'checked=""':''; ?> name="1" onClick="<?php echo $action; ?>"><i class="input-helper"></i>
                </span>
                <address>
                    <h6><?php echo $address['addr_title']; ?></h6>
                    <p><?php echo $address['ua_name']; ?><br> <?php echo $address['addr_address1']; ?><br> <?php echo (strlen($address['addr_address2'])>0)?$address['addr_address2'].'<br>':''; ?>
                        <?php echo (strlen($address['addr_city'])>0)?$address['addr_city'].',':''; ?> <?php echo (strlen($address['state_name'])>0)?$address['state_name'].'<br>':''; ?>
                        <?php echo (strlen($address['country_name'])>0)?$address['country_name'].'<br>':''; ?> <?php echo (strlen($address['addr_zip'])>0) ? Labels::getLabel('LBL_Zip:', $siteLangId).$address['addr_zip'].'<br>':''; ?>
                        <?php echo (strlen($address['addr_phone'])>0) ? Labels::getLabel('LBL_Phone:', $siteLangId).$address['addr_phone'].'<br>':''; ?> </p>
                </address>
                <div class="btn-group"><a href="javascript:void(0)" onClick="addAddressForm(<?php echo $address['addr_id']; ?>)" class="btn btn-outline-primary btn--sm"><?php echo Labels::getLabel('LBL_Edit', $siteLangId); ?></a>
                <a href="javascript:void(0)" onClick="removeAddress(<?php echo $address['addr_id']; ?>)" class="btn btn-outline-primary btn--sm"><?php echo Labels::getLabel('LBL_Delete', $siteLangId); ?></a></div>
            </label>
        </div> <?php
    }
} elseif (isset($noRecordsHtml)) {
    echo FatUtility::decodeHtmlEntities($noRecordsHtml);
} ?> </div>
</div>
