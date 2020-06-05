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

        $this->init();
    }

    private function init()
    {
        $plugin = new Plugin();
        $this->keyName = $plugin->getDefaultPluginKeyName(Plugin::TYPE_SHIPPING_SERVICES);

        $error = '';
        $this->shippingService = PluginHelper::callPlugin($this->keyName, [$this->adminLangId], $error, $this->adminLangId);
        if (false === $this->shippingService) {
            FatUtility::dieJsonError($error);
        }
    }

    public function generateLabel($orderId, $opId)
    {
        if (false === $this->shippingService->addOrder($orderId, $opId)) {
            LibHelper::dieJsonError($this->shippingService->getError());
        }
        $order = $this->shippingService->getResponse();
        $shipmentApiOrderId = $order['orderId'];

        if (false === $this->shippingService->bindLabel()) {
            LibHelper::dieJsonError($this->shippingService->getError());
        }
        
        $response = $this->shippingService->getResponse(false);
        $responseArr = json_decode($response, true);
        $recordCol = ['opship_op_id' => $opId];

        $dataToSave = [
            'opship_order_id' => $shipmentApiOrderId,
            'opship_shipment_id' => $responseArr['shipmentId'],
            'opship_tracking_number' => $responseArr['trackingNumber'],
            'opship_response' => $response,
        ];

        $db = FatApp::getDb();
        if (!$db->insertFromArray(OrderProductShipment::DB_TBL, array_merge($recordCol, $dataToSave), false, array(), $dataToSave)) {
            LibHelper::dieJsonError($db->getError());
        }

        LibHelper::dieJsonSuccess(Labels::getLabel('LBL_SUCCESS', $this->adminLangId));
    }
}
