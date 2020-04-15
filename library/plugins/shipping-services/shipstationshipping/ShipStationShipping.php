<?php

class ShipStationShipping extends ShippingServicesBase
{
    public const KEY_NAME = __CLASS__;
    public $shipStation;
    
    private $order;
    private $orderResponse;
    public $address;
    public $weight;
    public $dimensions;

    public $requiredKeys = [
        'api_key',
        'api_secret_key'
    ];
        
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
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
        $this->address = $this->weight = $this->dimensions = (object)array();
        return true;
    }

    /**
     * addOrder
     *
     * @param  string $orderId
     * @param  int $langId
     * @param  int $opId
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
            $this->setAddress($billingAddress['oua_name'], $billingAddress['oua_address1'], $billingAddress['oua_address2'], $billingAddress['oua_city'], $billingAddress['oua_state'], $billingAddress['oua_zip'], $billingAddress['oua_country_code'], $billingAddress['oua_phone']);
            $this->order->billTo = $this->address;

            // Define shipping address //
            $this->setAddress($shippingAddress['oua_name'], $shippingAddress['oua_address1'], $shippingAddress['oua_address2'], $shippingAddress['oua_city'], $shippingAddress['oua_state'], $shippingAddress['oua_zip'], $shippingAddress['oua_country_code'], $shippingAddress['oua_phone']);
            $this->order->shipTo = $this->address;

            // Order weight //
            $weightUnitsArr = applicationConstants::getWeightUnitsArr($langId);
            $weightUnitName = ($op['op_product_weight_unit']) ? $weightUnitsArr[$op['op_product_weight_unit']] : '';
            $productWeightInOunce = $this->convertWeightInOunce($op['op_product_weight'], $weightUnitName);

            $this->setWeight($productWeightInOunce);
            $this->order->weight = $this->weight;

            // Extra order data //
            $lengthUnitsArr = applicationConstants::getLengthUnitsArr($langId);
            $dimUnitName = ($op['op_product_dimension_unit']) ? $lengthUnitsArr[$op['op_product_dimension_unit']] : '';

            $lengthInCenti = $this->convertLengthInCenti($op['op_product_length'], $dimUnitName);
            $widthInCenti = $this->convertLengthInCenti($op['op_product_width'], $dimUnitName);
            $heightInCenti = $this->convertLengthInCenti($op['op_product_height'], $dimUnitName);

            $this->setDimensions($lengthInCenti, $widthInCenti, $heightInCenti);
            $this->order->dimensions = $this->dimensions;

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
     * @param  string $carrier_code
     * @param  string $from_pin_code
     * @param  int $langId
     * @return void
     */
    public function getShippingRates(string $carrier_code, string $from_pin_code, int $langId)
    {
        $order = new stdClass();
        $order->carrierCode = $carrier_code;
        $order->serviceCode = null;
        $order->packageCode = null;
        $order->fromPostalCode = $from_pin_code;
        $order->toState = $this->address->state;
        $order->toCountry = $this->address->country;
        $order->toPostalCode = $this->address->postalCode;
        $order->toCity = $this->address->city;
        $order->weight = $this->weight;
        if (!empty($order->dimensions)) {
            $order->dimensions = $this->dimensions;
        }
        if (!$response = $this->shipStation->getRates((array) $order)) {
            $langId = 1 > $langId ? commonHelper::getLangId() : $langId;
            $this->setSystemError($langId);
            return false;
        }

        return $response;
    }
            
    /**
     * getCarriers
     *
     * @param  bool $assoc
     * @param  int $langId
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
            array_unshift($list, Labels::getLabel('MSG_SELECT_CARRIER', $langId));
        }

        return $list;
    }
    
    /**
     * formatCarrierOptions
     *
     * @param  array $services
     * @param  int $freeShipping
     * @param  int $userId
     * @param  int $langId
     * @return void
     */
    public function formatCarrierOptions(array $services, int $freeShipping, int $userId, int $langId)
    {
        $langId = 1 > $langId ? commonHelper::getLangId() : $langId;
        $servicesList = [];
        if (!empty($services)) {
            $servicesList[] = Labels::getLabel('MSG_Select_Service', $langId);
            foreach ($services as $key => $value) {
                $code = $value->serviceCode;
                $price = $value->shipmentCost + $value->otherCost;
                $name = $value->serviceName;
                if ($freeShipping > 0 && $userId > 0) {
                    $displayPrice = Labels::getLabel('LBL_Free_Shipping', $langId);
                } else {
                    $displayPrice = CommonHelper::displayMoneyFormat($price);
                }
                $label = $name . " (" . $displayPrice . " )";
                $servicesList[$code . "-" . $price] = $label;
            }
        }

        return $servicesList;
    }
        
    /**
     * setAddress
     *
     * @param  string $name
     * @param  string $stt1
     * @param  string $stt2
     * @param  string $city
     * @param  string $state
     * @param  string $zip
     * @param  string $countryCode
     * @param  string $phone
     * @return void
     */
    public function setAddress(string $name, string $stt1, string $stt2, string $city, string $state, string $zip, string $countryCode, string $phone)
    {
        $this->address = new stdClass();

        $this->address->name = $name; // This has to be a String... If you put NULL the API cries...
        // $this->address->company       = null;
        $this->address->street1 = $stt1;
        $this->address->street2 = $stt2;
        // $this->address->street3       = null;
        $this->address->city = $city;
        $this->address->state = $state;
        $this->address->postalCode = $zip;
        $this->address->country = $countryCode;
        $this->address->phone = $phone;
        // $this->address->residential   = null;
        return true;
    }
          
    /**
     * setWeight
     *
     * @param  float $weight
     * @param  string $unit
     * @return void
     */
    public function setWeight($weight, $unit = 'ounces')
    {
        $this->weight = new stdClass();
        $this->weight->value = floatval($weight);
        $this->weight->units = trim($unit);

        return true;
    }
         
    /**
     * setDimensions
     *
     * @param  int $length
     * @param  int $width
     * @param  int $height
     * @param  string $unit
     * @return void
     */
    public function setDimensions($length, $width, $height, $unit = 'centimeters')
    {
        $this->dimensions = new stdClass();

        $this->dimensions->units = $unit;
        $this->dimensions->length = $length;
        $this->dimensions->width = $width;
        $this->dimensions->height = $height;

        return true;
    }

    
    /**
     * addItem
     *
     * @param  mixed $obj
     * @param  array $op
     * @param  int $langId
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
     * @param  string $api_key
     * @param  string $api_secret
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
     * setSystemError
     *
     * @param  int $langId
     * @return void
     */
    public function setSystemError($langId)
    {
        $error = $this->shipStation->getLastError();
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
