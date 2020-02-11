<?php if (!empty($product['selprod_warranty_policies']) || !empty($product['selprod_return_policies']) || (isset($shippingDetails['ps_free']) && $shippingDetails['ps_free']==applicationConstants::YES) || (count($shippingRates)>0) || ($codEnabled)) { ?>
<section class="section certified-bar">

    <ul>
        <?php if (!empty($product['product_warranty'])) { ?>
        <li>
            <div class="certified-box">
                <i class="icn">
                    <svg class="svg">
                        <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#yearswarranty"
                            href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#yearswarranty">
                        </use>
                    </svg>
                </i>
                <?php /* <p><?php echo $product['selprod_warranty_policies']['ppoint_title']; ?></p> */ ?>
                <p>
                    <?php
                                $lbl = Labels::getLabel('LBL_{DAYS}_DAYS_WARRANTY', $siteLangId);
                                echo CommonHelper::replaceStringData($lbl, ['{DAYS}' => $product['product_warranty']]);
                            ?>
                </p>
            </div>
        </li>
        <?php } ?>
        <?php if (!empty($product['shop_return_age']) || !empty($product['selprod_return_age'])) { ?>
        <li>
            <div class="certified-box">
                <i class="icn">
                    <svg class="svg">
                        <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#easyreturns"
                            href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#easyreturns">
                        </use>
                    </svg>
                </i>
                <?php /* <p><?php echo $product['selprod_return_policies']['ppoint_title']; ?></p> */ ?>
                <p>
                    <?php
                                $lbl = Labels::getLabel('LBL_{DAYS}_DAYS_RETURN_BACK_POLICY', $siteLangId);
                                $returnAge = !empty($product['selprod_return_age']) ? $product['selprod_return_age'] : $product['shop_return_age'];
                                $returnAge = !empty($returnAge) ? $returnAge : 0;
                                echo CommonHelper::replaceStringData($lbl, ['{DAYS}' => $returnAge]);
                            ?>
                </p>
            </div>
        </li>
        <?php } ?>
        <?php if (!empty($product['shop_cancellation_age']) || !empty($product['selprod_cancellation_age'])) { ?>
        <li>
            <div class="certified-box">
                <i class="icn">
                    <svg class="svg">
                        <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#easyreturns"
                            href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#easyreturns">
                        </use>
                    </svg>
                </i>
                <p>
                    <?php
                                $lbl = Labels::getLabel('LBL_{DAYS}_DAYS_CANCELLATION_POLICY', $siteLangId);
                                $cancellationAge = !empty($product['selprod_cancellation_age']) ? $product['selprod_cancellation_age'] : $product['shop_cancellation_age'];
                                $cancellationAge = !empty($cancellationAge) ? $cancellationAge : 0;
                                echo CommonHelper::replaceStringData($lbl, ['{DAYS}' => $cancellationAge]);
                            ?>
                </p>
            </div>
        </li>
        <?php } ?>
        <?php if (isset($shippingDetails['ps_free']) && $shippingDetails['ps_free']==applicationConstants::YES) { ?>
        <li>
            <div class="certified-box">
                <i class="icn">
                    <svg class="svg">
                        <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#freeshipping"
                            href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#freeshipping">
                        </use>
                    </svg>
                </i>
                <p><?php echo Labels::getLabel('LBL_Free_Shipping_on_this_Order', $siteLangId); ?>
                </p>
            </div>
        </li>
        <?php } elseif (count($shippingRates)>0) { ?>
        <li>
            <div class="certified-box">
                <i class="icn">
                    <svg class="svg">
                        <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#shipping-policies"
                            href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#shipping-policies">
                        </use>
                    </svg>
                </i>
                <p><?php echo Labels::getLabel('LBL_Shipping_Rates', $siteLangId);?>
                    <a href="#shipRates" rel="facebox"><i class="fa fa-question-circle"></i></a></p>
                <div id="shipRates" style="display:none">
                    <div class="delivery-term-data-inner">
                        <?php
                                $arr_flds = array(
                                    'country_name'=> Labels::getLabel('LBL_Ship_to', $siteLangId),
                                    'pship_charges'=> Labels::getLabel('LBL_Cost', $siteLangId),
                                    'pship_additional_charges'=> Labels::getLabel('LBL_With_Another_item', $siteLangId),
                                );
                                $tbl = new HtmlElement('table', array('width'=>'100%', 'class'=>'table'));
                                $th = $tbl->appendElement('thead')->appendElement('tr');
                                foreach ($arr_flds as $val) {
                                    $e = $th->appendElement('th', array(), $val);
                                }
                                foreach ($shippingRates as $sn => $row) {
                                    $tr = $tbl->appendElement('tr');

                                    foreach ($arr_flds as $key => $val) {
                                        $td = $tr->appendElement('td');
                                        switch ($key) {
                                            case 'pship_additional_charges':
                                                $td->appendElement('plaintext', array(), CommonHelper::displayMoneyFormat($row[$key]));
                                                break;
                                            case 'pship_charges':
                                                $td->appendElement('plaintext', array(), CommonHelper::displayMoneyFormat($row[$key]));
                                                break;
                                            case 'country_name':
                                                $td->appendElement('plaintext', array(), Product::getProductShippingTitle($siteLangId, $row), true);
                                                break;
                                            default:
                                                $td->appendElement('plaintext', array(), $row[$key], true);
                                                break;
                                        }
                                    }
                                }
                                echo $tbl->getHtml(); ?>
                    </div>
                </div>
            </div>
        </li>
        <?php } ?>
        <?php if ($codEnabled) { ?>
        <li>
            <div class="certified-box">
                <i class="icn">
                    <svg class="svg">
                        <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#safepayments"
                            href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#safepayments">
                        </use>
                    </svg>
                </i>
                <p><?php echo Labels::getLabel('LBL_Cash_on_delivery_is_available', $siteLangId);?>
                    <i class="fa fa-question-circle-o tooltip tooltip--right"><span class="hovertxt"><?php echo Labels::getLabel('MSG_Cash_on_delivery_available._Choose_from_payment_options', $siteLangId);?>
                        </span></i></p>
            </div>
        </li>
        <?php } ?>
    </ul>

</section>
<?php }
