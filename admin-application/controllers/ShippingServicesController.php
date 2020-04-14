<?php

class ShippingServicesController extends AdminBaseController
{
    private $shipping;
    private $keyName;

    public function __construct($action)
    {
        parent::__construct($action);
        $this->admin_id = AdminAuthentication::getLoggedAdminId();
        $this->objPrivilege->canViewShippingSoftware($this->admin_id);
        $this->canEdit = $this->objPrivilege->canEditShippingSoftware($this->admin_id, true);
        $this->set("canEdit", $this->canEdit);

        $plugin = new Plugin();
        $this->keyName = $plugin->getDefaultPluginKeyName(Plugin::TYPE_SHIPPING_SERVICES);
        $error = '';
        if (false === PluginHelper::includePlugin($this->keyName, 'shipping-services', $this->adminLangId, $error)) {
            FatUtility::dieJsonError($error);
        }
        $this->shipping = new $this->keyName();
    }

    public function generateLabel($orderId, $opId)
    {
        $order = $this->shipping->addOrder($orderId, $this->adminLangId, $opId);
        if (false === $order) {
            $this->shipping->setSystemError($this->adminLangId);
        }
        CommonHelper::printArray($order, true);
    }

    public function createLabel()
    {
        $response = $this->shipping->createLabel();
        CommonHelper::printArray($response);
    }

    public function getCarriers()
    {
        $response = $this->shipping->getCarriers();
        CommonHelper::printArray($response, true);
    }
}
