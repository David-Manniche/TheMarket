<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$variables = array('language' => $language, 'siteLangId' => $siteLangId, 'shop_id' => $shop_id, 'action' => $action);
$this->includeTemplate('seller/_partial/shop-navigation.php', $variables, false);
?>
<div class="card">
    <div class="card-header">
        <h5 class="card-title"><?php echo Labels::getLabel('LBL_Shop_Pickup_Addresses', $siteLangId); ?></h5>
        <?php if ($canEdit) { ?>
        <div class="btn-group">
            <a href="javascript:void(0)" onClick="pickupAddressForm(0)" class="btn btn-outline-brand btn-sm"><?php echo Labels::getLabel('LBL_Add_Address', $siteLangId); ?></a>
        </div>
        <?php } ?>
    </div>
    <div class="card-body"> 
        <div class="container--addresses">
            <div class="row">
            <?php
            if (isset($addresses) && !empty($addresses)) {
                if (count($addresses) == 1 && $addresses[0]['addr_is_default'] != 1) {
                    $addresses[0]['addr_is_default'] = 1;
                }
                foreach ($addresses as $address) {
                    $address['addr_title'] = ($address['addr_title'] == '') ? '&nbsp;' : $address['addr_title'];
            ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <label class="address-block <?php echo ($address['addr_is_default'] == 1) ? 'is-active' : ''; ?>">
                            <address>
                                <h6><?php echo $address['addr_title']; ?></h6>
                                <p>
                                    <?php echo $address['addr_name']; ?><br> <?php echo $address['addr_address1']; ?><br> <?php echo (strlen($address['addr_address2']) > 0) ? $address['addr_address2'] . '<br>' : ''; ?>
                                    <?php echo (strlen($address['addr_city']) > 0) ? $address['addr_city'] . ',' : ''; ?> <?php echo (strlen($address['state_name']) > 0) ? $address['state_name'] . '<br>' : ''; ?>
                                    <?php echo (strlen($address['country_name']) > 0) ? $address['country_name'] . '<br>' : ''; ?> <?php echo (strlen($address['addr_zip']) > 0) ? Labels::getLabel('LBL_Zip:', $siteLangId) . $address['addr_zip'] . '<br>' : ''; ?>
                                    <?php echo (strlen($address['addr_phone']) > 0) ? Labels::getLabel('LBL_Phone:', $siteLangId) . $address['addr_phone'] . '<br>' : ''; ?> 
                                </p>
                            </address>
                            <div class="btn-group">
                                <a href="javascript:void(0)" onClick="pickupAddressForm(<?php echo $address['addr_id']; ?>)" class="btn btn-outline-brand btn-sm"><?php echo Labels::getLabel('LBL_Edit', $siteLangId); ?></a>
                                <a href="javascript:void(0)" onClick="removeAddress(<?php echo $address['addr_id']; ?>, <?php echo Address::TYPE_SHOP_PICKUP; ?>)" class="btn btn-outline-brand btn-sm"><?php echo Labels::getLabel('LBL_Delete', $siteLangId); ?></a>
                            </div>
                        </label>
                    </div> 
            <?php
                }
            } elseif (isset($noRecordsHtml)) {
                echo FatUtility::decodeHtmlEntities($noRecordsHtml);
            }
        ?>
            </div>
        </div> 
    </div>
</div>
