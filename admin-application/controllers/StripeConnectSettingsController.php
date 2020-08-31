<?php

class StripeConnectSettingsController extends PaymentMethodSettingsController
{
    public static function form(int $langId)
    {
        $frm = new Form('frmStripeConnect');

        $envoirment = Plugin::getEnvArr($langId);
        $envFld = $frm->addSelectBox(Labels::getLabel('LBL_ENVOIRMENT', $langId), 'env', $envoirment, '', ['class' => 'fieldsVisibility-js'], '');
        $envFld->requirement->setRequired(true);

        $frm->addTextBox(Labels::getLabel('LBL_CLIENT_ID', $langId), 'client_id');
        $clientIdFld = new FormFieldRequirement('client_id', Labels::getLabel('LBL_CLIENT_ID', $langId));
        $clientIdFld->setRequired(false);
        $reqClientIdFld = new FormFieldRequirement('client_id', Labels::getLabel('LBL_CLIENT_ID', $langId));
        $reqClientIdFld->setRequired(true);
        
        $frm->addTextBox(Labels::getLabel('LBL_PUBLISHABLE_KEY', $langId), 'publishable_key');
        $publishableKeyFld = new FormFieldRequirement('publishable_key', Labels::getLabel('LBL_PUBLISHABLE_KEY', $langId));
        $publishableKeyFld->setRequired(false);
        $reqPublishableKeyFld = new FormFieldRequirement('publishable_key', Labels::getLabel('LBL_PUBLISHABLE_KEY', $langId));
        $reqPublishableKeyFld->setRequired(true);
        
        $frm->addTextBox(Labels::getLabel('LBL_SECRET_KEY', $langId), 'secret_key');
        $secretKeyFld = new FormFieldRequirement('secret_key', Labels::getLabel('LBL_SECRET_KEY', $langId));
        $secretKeyFld->setRequired(false);
        $reqSecretKeyFld = new FormFieldRequirement('secret_key', Labels::getLabel('LBL_SECRET_KEY', $langId));
        $reqSecretKeyFld->setRequired(true);

        $frm->addTextBox(Labels::getLabel('LBL_CLIENT_ID', $langId), 'live_client_id');
        $liveClientIdFld = new FormFieldRequirement('live_client_id', Labels::getLabel('LBL_CLIENT_ID', $langId));
        $liveClientIdFld->setRequired(false);
        $reqLiveClientIdFld = new FormFieldRequirement('live_client_id', Labels::getLabel('LBL_CLIENT_ID', $langId));
        $reqLiveClientIdFld->setRequired(true);

        $frm->addTextBox(Labels::getLabel('LBL_PUBLISHABLE_KEY', $langId), 'live_publishable_key');
        $livePublishableKeyFld = new FormFieldRequirement('live_publishable_key', Labels::getLabel('LBL_PUBLISHABLE_KEY', $langId));
        $livePublishableKeyFld->setRequired(false);
        $reqLivePublishableKeyFld = new FormFieldRequirement('live_publishable_key', Labels::getLabel('LBL_PUBLISHABLE_KEY', $langId));
        $reqLivePublishableKeyFld->setRequired(true);
                
        $frm->addTextBox(Labels::getLabel('LBL_SECRET_KEY', $langId), 'live_secret_key');
        $liveSecretKeyFld = new FormFieldRequirement('live_secret_key', Labels::getLabel('LBL_SECRET_KEY', $langId));
        $liveSecretKeyFld->setRequired(false);
        $reqLiveSecretKeyFld = new FormFieldRequirement('live_secret_key', Labels::getLabel('LBL_SECRET_KEY', $langId));
        $reqLiveSecretKeyFld->setRequired(true);

        $envFld->requirements()->addOnChangerequirementUpdate(Plugin::ENV_SANDBOX, 'eq', 'client_id', $reqClientIdFld);
        $envFld->requirements()->addOnChangerequirementUpdate(Plugin::ENV_SANDBOX, 'eq', 'publishable_key', $reqPublishableKeyFld);
        $envFld->requirements()->addOnChangerequirementUpdate(Plugin::ENV_SANDBOX, 'eq', 'secret_key', $reqSecretKeyFld);

        $envFld->requirements()->addOnChangerequirementUpdate(Plugin::ENV_SANDBOX, 'eq', 'live_client_id', $liveClientIdFld);
        $envFld->requirements()->addOnChangerequirementUpdate(Plugin::ENV_SANDBOX, 'eq', 'live_publishable_key', $livePublishableKeyFld);
        $envFld->requirements()->addOnChangerequirementUpdate(Plugin::ENV_SANDBOX, 'eq', 'live_secret_key', $liveSecretKeyFld);
        
        
        $envFld->requirements()->addOnChangerequirementUpdate(Plugin::ENV_PRODUCTION, 'eq', 'client_id', $clientIdFld);
        $envFld->requirements()->addOnChangerequirementUpdate(Plugin::ENV_PRODUCTION, 'eq', 'publishable_key', $publishableKeyFld);
        $envFld->requirements()->addOnChangerequirementUpdate(Plugin::ENV_PRODUCTION, 'eq', 'secret_key', $secretKeyFld);

        $envFld->requirements()->addOnChangerequirementUpdate(Plugin::ENV_PRODUCTION, 'eq', 'live_client_id', $reqLiveClientIdFld);
        $envFld->requirements()->addOnChangerequirementUpdate(Plugin::ENV_PRODUCTION, 'eq', 'live_publishable_key', $reqLivePublishableKeyFld);
        $envFld->requirements()->addOnChangerequirementUpdate(Plugin::ENV_PRODUCTION, 'eq', 'live_secret_key', $reqLiveSecretKeyFld);

        $captureMethod = [
            'automatic' => Labels::getLabel('LBL_INSTANT_(_DEFAULT_)', $langId),
            'manual' => Labels::getLabel('LBL_ON_ORDER_STATUS_UPDATE', $langId)
        ];
        $condFld = $frm->addSelectBox(Labels::getLabel('LBL_CAPTURE_PAYMENT', $langId), 'capture_method', $captureMethod, 'automatic', ['class' => 'fieldsVisibility-js'], '');
        $condFld->requirement->setRequired(true);

        $orderStatusArr = Orders::getOrderProductStatusArr($langId);
        $frm->addSelectBox(
            Labels::getLabel("LBL_ORDER_STATUS", $langId),
            'order_status',
            $orderStatusArr,
            OrderStatus::ORDER_SHIPPED,
            array(),
            ''
        );
        $orderStatus = new FormFieldRequirement('order_status', Labels::getLabel('LBL_ORDER_STATUS', $langId));
        $orderStatus->setRequired(false);
        $reqOrderStatus = new FormFieldRequirement('order_status', Labels::getLabel('LBL_ORDER_STATUS', $langId));
        $reqOrderStatus->setRequired(true);

        $condFld->requirements()->addOnChangerequirementUpdate('automatic', 'eq', 'order_status', $orderStatus);
        $condFld->requirements()->addOnChangerequirementUpdate('manual', 'eq', 'order_status', $reqOrderStatus);

        $frm->addSubmitButton('&nbsp;', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $langId));
        return $frm;
    }
}
