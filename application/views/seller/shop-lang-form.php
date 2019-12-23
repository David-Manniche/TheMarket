<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
/* $shopLangFrm->setFormTagAttribute('onsubmit', 'setupShopLang(this); return(false);'); */
$shopLangFrm->setFormTagAttribute('class', 'form form--horizontal layout--'.$formLayout);

$shopLangFrm->developerTags['colClassPrefix'] = 'col-lg-4 col-md-';
$shopLangFrm->developerTags['fld_default_col'] = 4;

$paymentPolicyfld = $shopLangFrm->getField('shop_payment_policy');
$paymentPolicyfld->htmlAfterField = '<small class="text--small">'.Labels::getLabel('LBL_Shop_payment_terms_comments', $formLangId).'</small>';

$paymentPolicyfld = $shopLangFrm->getField('shop_delivery_policy');
$paymentPolicyfld->htmlAfterField = '<small class="text--small">'.Labels::getLabel('LBL_Shop_delivery_policy_comments', $formLangId).'</small>';

$paymentPolicyfld = $shopLangFrm->getField('shop_refund_policy');
$paymentPolicyfld->htmlAfterField = '<small class="text--small">'.Labels::getLabel('LBL_Shop_refund_policy_comments', $formLangId).'</small>';

$paymentPolicyfld = $shopLangFrm->getField('shop_additional_info');
$paymentPolicyfld->htmlAfterField = '<small class="text--small">'.Labels::getLabel('LBL_Shop_additional_info_comments', $formLangId).'</small>';

$paymentPolicyfld = $shopLangFrm->getField('shop_seller_info');
$paymentPolicyfld->htmlAfterField = '<small class="text--small">'.Labels::getLabel('LBL_Shop_seller_info_comments', $formLangId).'</small>';

$langFld = $shopLangFrm->getField('lang_id');
$langFld->setfieldTagAttribute('onChange', "shopLangForm(" . $shop_id . ", this.value);");
?>

<?php     $variables= array('formLangId'=>$formLangId, 'language'=>$language,'siteLangId'=>$siteLangId,'shop_id'=>$shop_id,'action'=>$action);

$this->includeTemplate('seller/_partial/shop-navigation.php', $variables, false); ?>
<div class="cards">
    <div class="cards-content pt-3 pl-4 pr-4 ">
        <div class="tabs__content">
            <div class="row ">
                <?php
                    $translatorSubscriptionKey = FatApp::getConfig('CONF_TRANSLATOR_SUBSCRIPTION_KEY', FatUtility::VAR_STRING, '');
                    $siteDefaultLangId = FatApp::getConfig('conf_default_site_lang', FatUtility::VAR_INT, 1);
                    if (!empty($translatorSubscriptionKey) && $formLangId != $siteDefaultLangId) { ?> 
                        <div class="row justify-content-end"> 
                            <div class="col-auto mb-4">
                                <input class="btn btn-primary" 
                                    type="button" 
                                    value="<?php echo Labels::getLabel('LBL_AUTOFILL_LANGUAGE_DATA', $siteLangId); ?>" 
                                    onClick="shopLangForm( <?php echo $shop_id; ?>, <?php echo $formLangId; ?>, 1)">
                            </div>
                        </div>
                    <?php } ?>
                <div class="col-lg-12 col-md-12" id="shopFormBlock">
                    <?php echo $shopLangFrm->getFormTag();
                    echo $shopLangFrm->getFormHtml(false);
                    echo '</form>'; ?>
                </div>
            </div>
        </div>
    </div>
</div>
