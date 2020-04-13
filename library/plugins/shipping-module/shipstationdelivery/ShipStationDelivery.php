<?php

class ShipStationDelivery extends ShippingModuleBase
{
    public const KEY_NAME = __CLASS__;
    public $shipStation;
    
    private $order;
    private $orderResponse;

    public $requiredKeys = [
        'api_key',
        'api_secret_key'
    ];
    
    /**
     * __construct
     *
     * @param  mixed $langId
     * @return void
     */
    public function __construct($langId = 0)
    {
        $this->langId = FatUtility::int($langId);
        if (1 > $this->langId) {
            $this->langId = CommonHelper::getLangId();
        }

        if (false == $this->validateSettings()) {
            return false;
        }
        $this->initialize();
    }
        
    /**
     * initialize
     *
     * @return void
     */
    private function initialize()
    {
        include_once dirname(__FILE__) . '/libraries/unirest/Unirest.php';
        include_once dirname(__FILE__) . '/libraries/shipstation/Shipstation.class.php';

        $apiKey = $this->settings['api_key'];
        $apiSecret = $this->settings['api_secret_key'];
        $this->shipStation = new Shipstation();
        $this->shipStation->setSsApiKey($apiKey);
        $this->shipStation->setSsApiSecret($apiSecret);
        return true;
    }
        
    /**
     * getOrders
     *
     * @return void
     */
    public function getOrders()
    {
        $filters = array(
            'orderNumber' => "",
            'orderStatus' => "", // {awaiting_shipment, on_hold, shipped, cancelled}
            'storeid' => "",
            'customerName' => "",
            'itemKeyword' => "", // Searchs on Sku, Description, and Options
            'paymentDateStart' => "", // e.g. 2014-01-01
            'paymentdateend' => "", // e.g. 2014-01-04 (there is no typo, camel case isn't applied)
            'orderDateStart' => "", // e.g. 2014-01-01
            'orderDateEnd' => "", // e.g. 2014-01-04
            'modifyDateStart' => "", // e.g. 2014-01-01
            'modifyDateEnd' => "", // e.g. 2014-01-04
            'page' => "",
            'pageSize' => "", // Max: 500, Default: 100
        );

        $searchResult = $this->shipStation->getOrders($filters);

        $orders = $searchResult->orders;
        $totalResults = $searchResult->total;
        $currentPage = $searchResult->page;

        // WARNING: if there is only 1 page this value returns 0...

        $totalPages = $searchResult->pages;
        return $orders;
    }
    
    /**
     * getWarehouses
     *
     * @return void
     */
    public function getWarehouses()
    {
        return $this->shipStation->getWarehouses();
    }
    
    /**
     * getOrder
     *
     * @param  mixed $orderId
     * @return void
     */
    public function getOrder($orderId)
    {
        return $this->shipStation->getOrder($orderId);
    }
    
    /**
     * addOrder
     *
     * @param  mixed $orderId
     * @param  mixed $langId
     * @return void
     */
    public function addOrder(string $orderId, int $langId, int $opId)
    {
        $orderDetail = $this->getSystemOrder($orderId, $langId, $opId);
        CommonHelper::printArray($orderDetail, true);
        if (false === $orderDetail) {
            return false;
        }

        $orderTimestamp = strtotime($orderDetail['order_date_added']);
        $orderDate = date('Y-m-d', $orderTimestamp) . 'T' . date('H:i:s', $orderTimestamp) . '.0000000';
        
        $taxCharged = 0;
        $shippingTotal = 0;
        $orderInvoiceNumber = 0;
        foreach ($orderDetail["products"] as $op) {
            $shippingTotal = $shippingTotal + CommonHelper::orderProductAmount($op, 'shipping');
            if (!empty($op['taxOptions'])) {
                foreach ($op['taxOptions'] as $key => $val) {
                    $taxCharged += $val['value'];
                }
            }
            $orderInvoiceNumber = $op['op_invoice_number'];

            $billingAddress = $orderDetail['billingAddress'];
            $shippingAddress = $orderDetail['shippingAddress'];

            $this->order = new stdClass();

            $this->order->orderNumber = $orderInvoiceNumber;
            $this->order->orderKey = $orderInvoiceNumber; // if specified, the method becomes idempotent and the existing Order with that key will be updated
            $this->order->orderDate = $orderDate;
            $this->order->paymentDate = $orderDate;
            $this->order->orderStatus = "awaiting_shipment"; // {awaiting_shipment, on_hold, shipped, cancelled}
            $this->order->customerUsername = $orderDetail['buyer_user_name'];
            $this->order->customerEmail = $orderDetail['buyer_email'];
            $this->order->amountPaid = $orderDetail['order_net_amount'];
            $this->order->taxAmount = 1 > $taxCharged ? $orderDetail['order_tax_charged'] : $taxCharged;
            $this->order->shippingAmount = $shippingTotal;
            /* $this->order->customerNotes     = null;
            $this->order->internalNotes     = "Express Shipping Please";
            $this->order->requestedShippingService     = "Priority Mail"; */
            $this->order->paymentMethod = $orderDetail['pmethod_name'];
            /* $this->order->carrierCode       = "fedex"; */
            $this->order->serviceCode       = $op['opshipping_carrier'];
            $this->order->packageCode = "package";
            /* $this->order->confirmation      = null;
            $this->order->shipDate          = null; */


            // Define billing address //
            $this->setAddress($this->order->billTo, $billingAddress);

            // Define shipping address //
            $this->setAddress($this->order->shipTo, $shippingAddress);

            // Order weight //
            $this->setWeight($this->order->weight, $op, $langId);

            // Extra order data //
            $this->setDimensions($this->order->dimensions, $op, $langId);


            // Add items to order [START] ================================= //
            
            // Loop the order products here...

            // Add Item //
            $this->addItem($this->order->items, $op, $langId);

            // Add items to order [END] =================================== //
        }

        // Defining ShipStation Order [END] =========================== //
        // ============================================================ //
        return $this->orderResponse = $this->shipStation->addOrder($this->order);
    }

    
    /**
     * createLabel
     *
     * @return void
     */
    public function createLabel()
    {
        $filters = array(
            "orderId" => $this->orderResponse->orderId,
            "carrierCode" => "ups",
            "serviceCode" => "ups_ground",
            "packageCode" => "package",
            "weight" => (array) $this->order->weight,
            "shipFrom" => array(
                "name" => "Jonathan Moyes",
                "company" => "",
                "phone" => "303-555-1212",
                "Email" => "example@test.com",
                "street1" => "123 Main Street",
                "street2" => "",
                "city" => "Boulder",
                "state" => "CO",
                "postalCode" => "80301",
                "country" => "US"
            ),
            "shipTo" => (array) $this->order->shipTo,
            "testLabel" => false
        );
        
        $label = $this->shipStation->createLabel($filters);
        
        $filename = "label-" . $label->carrierCode . "-" . $label->trackingNumber . ".pdf";
        
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Type: application/pdf");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Transfer-Encoding: binary");
        
        return base64_decode($label->labelData);
    }
    
    /**
     * getShippingRates
     *
     * @param  mixed $carrier_code
     * @param  mixed $from_pin_code
     * @param  mixed $productWeight
     * @param  mixed $deliveryAddress
     * @param  mixed $productDim
     * @return void
     */
    public function getShippingRates($carrier_code, $from_pin_code, stdClass $productWeight, stdClass $deliveryAddress, stdClass $productDim)
    {
        $order = new stdClass();
        $order->carrierCode = $carrier_code;
        $order->serviceCode = null;
        $order->packageCode = null;
        $order->fromPostalCode = $from_pin_code;
        $order->toState = $deliveryAddress->state;
        $order->toCountry = $deliveryAddress->country;
        $order->toPostalCode = $deliveryAddress->pincode;
        $order->toCity = $deliveryAddress->city;
        $order->weight = $productWeight;
        if (!empty($order->dimensions)) {
            $order->dimensions = $productDim;
        }
        if (!$response = $this->shipStation->getRates((array) $order)) {
            $error = $this->shipStation->getLastError();
           
            throw new Exception($error->message);
        }

        return $response;
    }
    
    /**
     * getCarriers
     *
     * @return void
     */
    public function getCarriers()
    {
        if (!$list = $this->shipStation->getCarriers()) {
            $error = $this->shipStation->getLastError();
            throw new Exception($error->message);
        }
        return $list;
    }
    
    /**
     * setAddress
     *
     * @param  mixed $obj - Where you want to store address values.
     * @param  mixed $addressArr
     * @return void
     */
    public function setAddress(&$obj, $addressArr)
    {
        $address = new stdClass();

        $address->name = $addressArr['oua_name']; // This has to be a String... If you put NULL the API cries...
        // $address->company       = null;
        $address->street1 = $addressArr['oua_address1'];
        $address->street2 = $addressArr['oua_address2'];
        // $address->street3       = null;
        $address->city = $addressArr['oua_city'];
        $address->state = $addressArr['oua_state'];
        $address->postalCode = $addressArr['oua_zip'];
        $address->country = $addressArr['oua_country_code'];
        $address->phone = $addressArr['oua_phone'];
        // $address->residential   = null;
        $obj = $address;
        return true;
    }
      
    /**
     * setWeight
     *
     * @param  mixed $obj - Where you want to store wight values.
     * @param  mixed $op
     * @param  mixed $langId
     * @return void
     */
    public function setWeight(&$obj, $op, $langId)
    {
        $weightUnitsArr = applicationConstants::getWeightUnitsArr($langId);
        $weight_unit_name = ($op['op_product_weight_unit']) ? $weightUnitsArr[$op['op_product_weight_unit']] : '';

        $weight = new stdClass();
        $weight->value = floatval($op['op_product_weight']);
        $weight->units = trim($weight_unit_name);

        $obj = $weight;

        return true;
    }
       
    /**
     * setDimensions
     *
     * @param  mixed $obj - Where you want to store dimension values.
     * @param  mixed $op
     * @param  mixed $langId
     * @return void
     */
    public function setDimensions(&$obj, $op, $langId)
    {
        $lengthUnitsArr = applicationConstants::getLengthUnitsArr($langId);
        $dim_unit_name = ($op['op_product_dimension_unit']) ? $lengthUnitsArr[$op['op_product_dimension_unit']] : '';

        $dimensions = new stdClass();

        $dimensions->units = $dim_unit_name;
        $dimensions->length = $op['op_product_length'];
        $dimensions->width = $op['op_product_width'];
        $dimensions->height = $op['op_product_height'];

        $obj = $dimensions;
        return true;
    }

    
    /**
     * addItem
     *
     * @param  mixed $obj
     * @param  mixed $op
     * @param  mixed $langId
     * @return void
     */
    public function addItem(&$obj, $op, $langId)
    {
        $item = new stdClass();

        $item->lineItemKey = $op['op_product_name'];
        $item->sku = $op['op_selprod_sku'];
        $item->name = $op['op_selprod_title'];
        $item->imageUrl = CommonHelper::generateFullUrl('image', 'product', array($op['selprod_product_id'], "THUMB", $op['op_selprod_id'], 0, $langId));
        $item->weight = $this->order->weight;
        $item->quantity = $op['op_qty'];
        $item->unitPrice = $op['op_unit_price'];
        // $item->warehouseLocation    = null;
        // $item->options              = array();


        // Add to items array //
        $items[] = $item;

        $obj = $items;
        return true;
    }
    
    /**
     * validateShipstationAccount
     *
     * @param  mixed $api_key
     * @param  mixed $api_secret
     * @return void
     */
    public function validateShipstationAccount($api_key, $api_secret)
    {
        $this->shipstation = new Shipstation();
        $this->shipstation->setSsApiKey($api_key);
        $this->shipstation->setSsApiSecret($api_secret);

        try {
            $this->getCarriers();
        } catch (Exception $ex) {
            $this->error = "Shipstation Error : " . $ex->getMessage();
            return false;
        }

        return true;
    }
    
    /**
     * getError
     *
     * @return void
     */
    public function getError()
    {
        return $this->shipStation->getLastError();
    }
    
    /**
     * setSystemError
     *
     * @param  mixed $langId
     * @return void
     */
    public function setSystemError($langId)
    {
        $error = $this->getError();
        $errorDetail = json_decode($error->message, true);
        
        // CommonHelper::printArray($error);
        // CommonHelper::printArray($errorDetail, true);
        $errMsg = $errorDetail['Message'];
        if (500 == $error->code) {
            $errMsg = 'Internal Server Error - ShipStation has encountered an error.';
            if (isset($errorDetail['ExceptionMessage'])) {
                $errMsg = $errorDetail['ExceptionMessage'];
            }
        }

        $message = Labels::getLabel("MSG_ERROR_{CODE}_-_{MSG}", $langId);
        $message = CommonHelper::replaceStringData($message, ['{CODE}' => $error->code, '{MSG}' => $errMsg]);
        Message::addErrorMessage($message);
        if (isset($errorDetail['ModelState'])) {
            foreach ($errorDetail['ModelState'] as $errMsg) {
                Message::addErrorMessage(current($errMsg));
            }
        }
        FatUtility::dieJsonError(Message::getHtml());
    }
}
