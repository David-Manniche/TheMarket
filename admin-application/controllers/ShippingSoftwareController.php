<?php

class ShippingSoftwareController extends AdminBaseController
{
    private $shipStation;
    public function __construct($action)
    {
        parent::__construct($action);
        $this->admin_id = AdminAuthentication::getLoggedAdminId();
        $this->objPrivilege->canViewShippingSoftware($this->admin_id);
        $this->canEdit = $this->objPrivilege->canEditShippingSoftware($this->admin_id, true);
        $this->set("canEdit", $this->canEdit);

        $error = '';
        if (false === PluginHelper::includePlugin(Plugin::TYPE_SHIPPING_SOFTWARE, 'shipping-software', $this->adminLangId, $error))
        {
            FatUtility::dieJsonError($error);
        }
        $this->shipStation = new ShipStationDelivery();
    }

    public function generateLabel($orderId)
    {
        $order = $this->addOrder($orderId);
    }

    public function addOrder($orderId)
    {
        $order 		= $this->shipStation->addOrder();
        CommonHelper::printArray($order, true);
    }

    public function deleteOrder($orderId)
    {
        $result = $this->shipStation->deleteOrder($orderId);
        CommonHelper::printArray($result, true);
    }

    public function getShipments($orderId)
    {
        $searchResult 	= $this->shipStation->getShipments($orderId);
        CommonHelper::printArray($searchResult, true);
    }

    public function createLabel()
    {
        $response = $this->shipStation->createLabel();
        CommonHelper::printArray($response);
    }

    public function getShippingRates($carrier_code, $from_pin_code, stdClass $productWeight, stdClass $deliveryAddress, stdClass $productDim) {

        $response  = $this->shipStation->getShippingRates($carrier_code, $from_pin_code, $productWeight, $deliveryAddress, $productDim);

        CommonHelper::printArray($response);
    }

    public function getCarriers() {

        $list = $this->shipStation->getCarriers();
        CommonHelper::printArray($list);
    }
    
}
