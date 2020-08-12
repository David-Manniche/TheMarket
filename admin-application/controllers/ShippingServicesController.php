<?php

class ShippingServicesController extends AdminBaseController
{
    private $keyName;
    private $filename = '';
    private $labelData = '';
    private $trackingNumber = '';
    private $shipmentResponse = '';
    private $error = '';
    
    /**
     * __construct
     *
     * @param  string $action
     * @return void
     */
    public function __construct($action)
    {
        parent::__construct($action);
        $this->admin_id = AdminAuthentication::getLoggedAdminId();
        $this->objPrivilege->canViewShippingManagement($this->admin_id);
        $this->canEdit = $this->objPrivilege->canEditShippingManagement($this->admin_id, true);
        $this->set("canEdit", $this->canEdit);

        $this->init();
    }
    
    /**
     * init
     *
     * @return void
     */
    private function init()
    {
        $plugin = new Plugin();
        $this->keyName = $plugin->getDefaultPluginKeyName(Plugin::TYPE_SHIPPING_SERVICES);

        $error = '';
        $this->shippingService = PluginHelper::callPlugin($this->keyName, [$this->adminLangId], $error, $this->adminLangId);
        if (false === $this->shippingService) {
            FatUtility::dieJsonError($error);
        }

        if (false === $this->shippingService->init()) {
            FatUtility::dieJsonError($this->shippingService->getError());
        }
    }
    
    /**
     * generateLabel
     *
     * @param  string $orderId
     * @param  int $opId
     * @return void
     */
    public function generateLabel(string $orderId, int $opId)
    {
        if (false === $this->shippingService->addOrder($orderId, $opId)) {
            LibHelper::dieJsonError($this->shippingService->getError());
        }
        $order = $this->shippingService->getResponse();
        
        $shipmentApiOrderId = $order['orderId'];
        $requestParam = [
            'orderId' => $order['orderId'],
            'carrierCode' => $order['carrierCode'],
            'serviceCode' => $order['serviceCode'],
            'confirmation' => $order['confirmation'],
            'shipDate' => date('Y-m-d'),// date('Y-m-d', strtotime('+7 day')),
            'weight' => $order['weight'],
            'dimensions' => $order['dimensions'],
        ];

        if (false === $this->shippingService->bindLabel($requestParam)) {
            LibHelper::dieJsonError($this->shippingService->getError());
        }
        
        $response = $this->shippingService->getResponse(false);
        $responseArr = json_decode($response, true);
        $recordCol = ['opship_op_id' => $opId];

        $dataToSave = [
            'opship_orderid' => $shipmentApiOrderId,
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

    /**
     * loadLabelData
     *
     * @param  int $opId
     * @return void
     */
    private function loadLabelData(int $opId): bool
    {
        $orderProductShipmentDetail = OrderProductShipment::getAttributesById($opId);
        if (empty($orderProductShipmentDetail)) {
            $this->error = Labels::getLabel("MSG_NO_LABEL_DATA_FOUND", $this->adminLangId);
            return false;
        }

        $this->shipmentResponse = json_decode($orderProductShipmentDetail['opship_response'], true);
        $this->trackingNumber = $orderProductShipmentDetail['opship_tracking_number'];
        $this->filename = "label-" . $this->trackingNumber . ".pdf";
        $this->labelData = $this->shipmentResponse['labelData'];
        return true;
    }

    /**
     * downloadLabel
     *
     * @param  int $opId
     * @return void
     */
    public function downloadLabel(int $opId)
    {
        if (false === $this->loadLabelData($opId)) {
            LibHelper::dieJsonError($this->error);
        }
        $this->shippingService->downloadLabel($this->labelData, $this->filename);
    }

    /**
     * previewLabel
     *
     * @param  int $opId
     * @return void
     */
    public function previewLabel(int $opId)
    {
        if (false === $this->loadLabelData($opId)) {
            LibHelper::dieJsonError($this->error);
        }
        $this->shippingService->downloadLabel($this->labelData, $this->filename, true);
    }

    /**
     * prceedToShipment
     *
     * @param  int $opId
     * @return void
     */
    public function prceedToShipment(int $opId)
    {
        $db = FatApp::getDb();
        $opSrch = new OrderProductSearch($this->adminLangId, false, true, true);
        $opSrch->joinShippingCharges();
        $opSrch->joinTable(OrderProductShipment::DB_TBL, 'LEFT JOIN', OrderProductShipment::DB_TBL_PREFIX . 'op_id = op.op_id', 'opship');
        $opSrch->addCountsOfOrderedProducts();
        $opSrch->addOrderProductCharges();
        $opSrch->doNotCalculateRecords();
        $opSrch->doNotLimitRecords();
        $opSrch->addCondition('op.op_id', '=', $opId);

        $opSrch->addMultipleFields(
            array('opshipping_label', 'opshipping_carrier_code', 'opshipping_service_code', 'opship.*')
        );

        $opRs = $opSrch->getResultSet();
        $data = $db->fetch($opRs);

        $requestParam = [
            "orderId" => $data['opship_orderid'],
            "carrierCode" => $data['opshipping_carrier_code'],
            "shipDate" => date('Y-m-d'),
            "trackingNumber" => $data['opship_tracking_number'],
            "notifyCustomer" => true,
            "notifySalesChannel" => true,
        ];

        if (false === $this->shippingService->proceedToShipment($requestParam)) {
            LibHelper::dieJsonError($this->shippingService->getError());
        }

        $orderInfo = $this->shippingService->getResponse();
        $updateData = [
            'opship_op_id' => $opId,
            'opship_order_number' => $orderInfo['orderNumber'],
            "opship_tracking_number" => $data['opship_tracking_number'],
        ];

       
        if (!$db->insertFromArray(OrderProductShipment::DB_TBL, $updateData, false, array(), $updateData)) {
            LibHelper::dieJsonError($db->getError());
        }
        LibHelper::dieJsonSuccess(Labels::getLabel('LBL_SUCCESS', $this->adminLangId));
    }

    /**
     * track - Not Working Yet
     *
     * @param  int $opId
     * @return void
     */
    public function track(int $opId)
    {
        $orderData = OrderProductShipment::getAttributesById($opId);
        if (false === $this->shippingService->loadOrder($orderData["opship_orderid"])) {
            LibHelper::dieWithError($this->shippingService->getError());
        }
        $orderInfo = $this->shippingService->getResponse();
        if (empty($orderInfo)) {
            $msg = Labels::getLabel("MSG_ORDER_NOT_GENERATED", $this->adminLangId);
            LibHelper::dieWithError($msg);
        }

        if (!isset($orderInfo['orderStatus']) || 'awaiting_shipment' == $orderInfo['orderStatus']) { 
            $msg = Labels::getLabel("MSG_NOT_SHIPPED_YET", $this->adminLangId);
            LibHelper::dieWithError($msg);    
        }

        $requestParam = [
            'orderId' => $orderData['opship_orderid'],
            'orderNumber' => $orderData['opship_order_number'],
            'trackingNumber' => $orderData['opship_tracking_number'],
        ];
        if (false === $this->shippingService->getFulfillments($requestParam)) {
            LibHelper::dieWithError($this->shippingService->getError());
        }
        $data = $this->shippingService->getResponse();
        CommonHelper::printArray($data);
    }
}
