<?php

class ShipStationShipping extends ShippingModuleBase
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
            $this->order->billTo = $this->formatAddress($billingAddress['oua_name'], $billingAddress['oua_address1'], $billingAddress['oua_address2'], $billingAddress['oua_city'], $billingAddress['oua_state'], $billingAddress['oua_zip'], $billingAddress['oua_country_code'], $billingAddress['oua_phone']);

            // Define shipping address //
            $this->order->shipTo = $this->formatAddress($shippingAddress['oua_name'], $shippingAddress['oua_address1'], $shippingAddress['oua_address2'], $shippingAddress['oua_city'], $shippingAddress['oua_state'], $shippingAddress['oua_zip'], $shippingAddress['oua_country_code'], $shippingAddress['oua_phone']);

            // Order weight //
            $weightUnitsArr = applicationConstants::getWeightUnitsArr($langId);
            $weightUnitName = ($op['op_product_weight_unit']) ? $weightUnitsArr[$op['op_product_weight_unit']] : '';
            $productWeightInOunce = $this->convertWeightInOunce($op['op_product_weight'], $weightUnitName);

            $this->order->weight = $this->formatWeight($productWeightInOunce);

            // Extra order data //
            $lengthUnitsArr = applicationConstants::getLengthUnitsArr($langId);
            $dimUnitName = ($op['op_product_dimension_unit']) ? $lengthUnitsArr[$op['op_product_dimension_unit']] : '';

            $lengthInCenti = $this->convertLengthInCenti($op['op_product_length'], $dimUnitName);
            $widthInCenti = $this->convertLengthInCenti($op['op_product_width'], $dimUnitName);
            $heightInCenti = $this->convertLengthInCenti($op['op_product_height'], $dimUnitName);

            $this->order->dimensions = $this->formatDimensions($lengthInCenti, $widthInCenti, $heightInCenti);


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
     * @param  mixed $deliveryAddress
     * @param  mixed $productDim
     * @param  mixed $productWeight
     * @return void
     */
    public function getShippingRates($carrier_code, $from_pin_code, stdClass $deliveryAddress, stdClass $productDim, stdClass $productWeight)
    {
        $order = new stdClass();
        $order->carrierCode = $carrier_code;
        $order->serviceCode = null;
        $order->packageCode = null;
        $order->fromPostalCode = $from_pin_code;
        $order->toState = $deliveryAddress->state;
        $order->toCountry = $deliveryAddress->country;
        $order->toPostalCode = $deliveryAddress->postalCode;
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
     * @param  mixed $assoc
     * @param  mixed $langId
     * @return void
     */
    public function getCarriers(bool $assoc = false, int $langId = 0)
    {
        if (!$list = $this->shipStation->getCarriers()) {
            $error = $this->shipStation->getLastError();
            throw new Exception($error->message);
        }

        if (true === $assoc) {
            $langId = 1 > $langId ? commonHelper::getLangId() : $langId;
            $list = array_reduce($list, function ($result, $item) {
                $name = $item->name;
                if (!empty($item->nickname) && strtolower($item->name) !== strtolower($item->nickname)) {
                    $name .= ' - ' . $item->nickname;
                }
                $result[$item->code] = $name;
                return $result;
            });
            array_unshift($list, Labels::getLabel('MSG_Select_Services', $langId));
        }

        return $list;
    }
        
    /**
     * formatAddress
     *
     * @param  mixed $name
     * @param  mixed $stt1
     * @param  mixed $stt2
     * @param  mixed $city
     * @param  mixed $state
     * @param  mixed $zip
     * @param  mixed $countryCode
     * @param  mixed $phone
     * @return void
     */
    public function formatAddress($name, $stt1, $stt2, $city, $state, $zip, $countryCode, $phone)
    {
        $address = new stdClass();

        $address->name = $name; // This has to be a String... If you put NULL the API cries...
        // $address->company       = null;
        $address->street1 = $stt1;
        $address->street2 = $stt2;
        // $address->street3       = null;
        $address->city = $city;
        $address->state = $state;
        $address->postalCode = $zip;
        $address->country = $countryCode;
        $address->phone = $phone;
        // $address->residential   = null;
        return $address;
    }
         
    /**
     * formatWeight
     *
     * @param  mixed $weight
     * @param  mixed $unit
     * @return void
     */
    public function formatWeight($weight, $unit = 'ounces')
    {
        $weightObj = new stdClass();
        $weightObj->value = floatval($weight);
        $weightObj->units = trim($unit);

        return $weightObj;
    }
           
    /**
     * formatDimensions
     *
     * @param  mixed $length
     * @param  mixed $width
     * @param  mixed $height
     * @param  mixed $unit
     * @return void
     */
    public function formatDimensions($length, $width, $height, $unit = 'centimeters')
    {
        $dimensions = new stdClass();

        $dimensions->units = $unit;
        $dimensions->length = $length;
        $dimensions->width = $width;
        $dimensions->height = $height;

        return $dimensions;
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
