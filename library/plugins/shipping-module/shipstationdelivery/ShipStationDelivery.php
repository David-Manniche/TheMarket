<?php
class ShipStationDelivery extends ShippingModuleBase
{
    public const KEY_NAME = __CLASS__;
    public $shipStation;

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
        $this->shipStation 	= new Shipstation();
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
        $filters 	= array
        (
            'orderNumber'		=> "",
            'orderStatus' 		=> "", // {awaiting_shipment, on_hold, shipped, cancelled}
            'storeid' 			=> "",
            'customerName' 		=> "",
            'itemKeyword'  		=> "", // Searchs on Sku, Description, and Options 
            'paymentDateStart' 	=> "", // e.g. 2014-01-01
            'paymentdateend' 	=> "", // e.g. 2014-01-04 (there is no typo, camel case isn't applied)
            'orderDateStart' 	=> "", // e.g. 2014-01-01
            'orderDateEnd' 		=> "", // e.g. 2014-01-04
            'modifyDateStart' 	=> "", // e.g. 2014-01-01
            'modifyDateEnd' 	=> "", // e.g. 2014-01-04
            'page' 				=> "",
            'pageSize' 			=> "", // Max: 500, Default: 100
        );

        $searchResult 	= $this->shipStation->getOrders($filters);

        $orders 		= $searchResult->orders;
        $totalResults 	= $searchResult->total;
        $currentPage 	= $searchResult->page;

        // WARNING: if there is only 1 page this value returns 0...

        $totalPages 	= $searchResult->pages;
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

        $orderDate = date('Y-m-dTH:i:s', strtotime($orderDetail['order_date_added'])).'.0000000';
        
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

            $order    = new stdClass();

            // $order->orderId           = null;
            $order->orderNumber       = $orderInvoiceNumber;
            $order->orderKey          = $orderInvoiceNumber; // if specified, the method becomes idempotent and the existing Order with that key will be updated
            $order->orderDate         = $orderDate;
            $order->paymentDate       = $orderDate;
            $order->orderStatus       = "awaiting_shipment"; // {awaiting_shipment, on_hold, shipped, cancelled}
            $order->customerUsername  = $orderDetail['buyer_user_name'];
            $order->customerEmail     = $orderDetail['buyer_email'];
            $order->amountPaid        = $orderDetail['order_net_amount'];
            $order->taxAmount         = 1 > $taxCharged ? $orderDetail['order_tax_charged'] : $taxCharged;
            $order->shippingAmount    = $shippingTotal;
            /* $order->customerNotes     = null;
            $order->internalNotes     = "Express Shipping Please";
            $order->gift              = null;
            $order->giftMessage       = null;
            $order->requestedShippingService     = "Priority Mail"; */
            $order->paymentMethod     = $orderDetail['pmethod_name'];
            /* $order->carrierCode       = "fedex";
            $order->serviceCode       = "fedex_2day"; */
            $order->packageCode       = "package";
            /* $order->confirmation      = null;
            $order->shipDate          = null; */


            // Define billing address //

            $billing    = new stdClass();

            $billing->name          = $billingAddress['oua_name']; // This has to be a String... If you put NULL the API cries...
            // $billing->company       = null;
            $billing->street1       = $billingAddress['oua_address1'];
            $billing->street2       = $billingAddress['oua_address2'];
            // $billing->street3       = null;
            $billing->city          = $billingAddress['oua_city'];
            $billing->state         = $billingAddress['oua_state'];
            $billing->postalCode    = $billingAddress['oua_zip'];
            $billing->country       = $billingAddress['oua_country_code'];
            $billing->phone         = $billingAddress['oua_phone'];
            // $billing->residential   = null;

            $order->billTo          = $billing;


            // Define shipping address //

            $shipping    = new stdClass();

            $shipping->name         = $shippingAddress['oua_name'];
            // $shipping->company      = "Go-Parts";
            $shipping->street1      = $shippingAddress['oua_address1'];
            $shipping->street2      = $shippingAddress['oua_address2'];
            // $shipping->street3      = null;
            $shipping->city         = $shippingAddress['oua_city'];
            $shipping->state        = $shippingAddress['oua_state'];
            $shipping->postalCode   = $shippingAddress['oua_zip'];
            $shipping->country      = $shippingAddress['oua_country_code'];
            $shipping->phone        = $shippingAddress['oua_phone'];
            // $shipping->residential  = true;

            $order->shipTo          = $shipping;


            // Order weight //
            $weightUnitsArr = applicationConstants::getWeightUnitsArr($langId);
            $weight_unit_name = ($op['op_product_weight_unit']) ? $weightUnitsArr[$op['op_product_weight_unit']] : '';

            $weight      = new stdClass();

            $weight->value          = $op['op_product_weight'];
            $weight->units          = $weight_unit_name;

            $order->weight          = $weight;


            // Extra order data //
            $lengthUnitsArr = applicationConstants::getLengthUnitsArr($langId);
            $dim_unit_name = ($op['op_product_dimension_unit']) ? $lengthUnitsArr[$op['op_product_dimension_unit']] : '';

            $dimensions   = new stdClass();

            $dimensions->units      = $dim_unit_name;
            $dimensions->length     = $op['op_product_length'];
            $dimensions->width      = $op['op_product_width'];
            $dimensions->height     = $op['op_product_height'];

            $order->dimensions      = $dimensions;


            // Insurance options //

            /* $insuranceOptions   = new stdClass();

            $insuranceOptions->provider         = null;
            $insuranceOptions->insureShipment   = false;
            $insuranceOptions->insuredValue     = 0;

            $order->insuranceOptions            = $insuranceOptions; */


            // International options //

            /* $internationalOptions   = new stdClass();

            $internationalOptions->contents     = null;
            $internationalOptions->customsItems = null;

            $order->internationalOptions        = $internationalOptions; */


            // International options //

            /* $advancedOptions    = new stdClass();

            $advancedOptions->warehouseId       = "{Your Warehouse ID Here}";
            $advancedOptions->nonMachinable     = false;
            $advancedOptions->saturdayDelivery  = false;
            $advancedOptions->containsAlcohol   = false;
            $advancedOptions->storeId           = "{Your Store ID Here}";
            $advancedOptions->customField1      = "";
            $advancedOptions->customField2      = "";
            $advancedOptions->customField3      = "";
            $advancedOptions->source            = null;

            $order->advancedOptions             = $advancedOptions; */


            // Add items to order [START] ================================= //
            
            // Loop the order products here...

            // Item //

            $item   = new stdClass();

            $item->lineItemKey          = $op['op_product_name'];
            $item->sku                  = $op['op_selprod_sku'];
            $item->name                 = $op['op_selprod_title'];
            $item->imageUrl             = CommonHelper::generateFullUrl('image', 'product', array($op['selprod_product_id'], "THUMB", $op['op_selprod_id'], 0, $langId));
            $item->weight               = $op['op_product_weight'];
            $item->quantity             = $op['op_qty'];
            $item->unitPrice            = $op['op_unit_price'];
            // $item->warehouseLocation    = null;
            // $item->options              = array();


            // Add to items array //
            $items[] 	= $item;


            $order->items                   = $items;

            // Add items to order [END] =================================== //
        }

        // Defining ShipStation Order [END] =========================== //
        // ============================================================ //

        return $this->shipStation->addOrder($order);
    }

    public function deleteOrder($orderId)
    {
        return $this->shipStation->deleteOrder($orderId);
    }

    public function getShipments($orderId)
    {
        $filters 	= array
        (
            'carrierCode'			=> "", // e.g. fedex
            'orderId' 				=> "",
            'orderNumber' 			=> "",
            'recipientCountryCode' 	=> "",
            'recipientName'  		=> "",
            'serviceCode' 			=> "", // e.g. fedex_ground
            'shipdatestart' 		=> "2014-12-01", // e.g. 2014-01-04
            'shipdateend' 			=> "2014-12-31", // e.g. 2014-01-01
            'trackingNumber' 		=> "",
            'voiddatestart' 		=> "", // e.g. 2014-01-01
            'voiddateend' 			=> "", // e.g. 2014-01-04
            'page' 					=> "",
            'pageSize' 				=> "", // Max: 500, Default: 100
        );

        $searchResult 	= $this->shipStation->getShipments($filters);

        /* $shipments 		= $searchResult->shipments;
        $totalResults 	= $searchResult->total;
        $currentPage 	= $searchResult->page;

        // WARNING: if there is only 1 page this value returns 0...

        $totalPages 	= $searchResult->pages; */
        return $searchResult;

    }
    
    /**
     * createLabel
     *
     * @return void
     */
    public function createLabel()
    {
        $filters = array(
            "carrierCode"=> "ups",
            "serviceCode"=>"ups_ground",
            "packageCode"=>"package",
            "weight"=>array(
                "value"=>2,
                "units"=>"ounces"
            ),
            "shipFrom"=> array(
				"name"=>"Jonathan Moyes",							  
				"company"=>"",
				"phone"=>"303-555-1212",
				"Email"=>"example@test.com", 
				"street1"=>"123 Main Street",
				"street2"=>"",
				"city"=>"Boulder",
				"state"=>"CO",
				"postalCode"=>"80301",
				"country"=>"US"	
			),
            "shipTo"=> array(
				"name"=>"RMA Processing",							  
				"company"=>"Modular Robotics",
				"phone"=>"303-656-9407",
				"email"=>"support@modrobotics.com", 
				"street1"=>"1860 38th Street",
				"street2"=>"",
				"city"=>"Boulder",
				"state"=>"CO",
				"postalCode"=>"80301",
				"country"=>"US"	
			),
            "testLabel"=>false
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
    public function getShippingRates($carrier_code, $from_pin_code, stdClass $productWeight, stdClass $deliveryAddress, stdClass $productDim) {

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
    public function getCarriers() {

        if (!$list = $this->shipStation->getCarriers()) {
            $error = $this->shipStation->getLastError();
            throw new Exception($error->message);
        }
        return $list;
    }
    
    /**
     * setProductDeliveryAddress
     *
     * @param  mixed $state
     * @param  mixed $country
     * @param  mixed $city
     * @param  mixed $postal_code
     * @return void
     */
    public function setProductDeliveryAddress($state, $country, $city, $postal_code) {
        $this->productDeliveryAddress = new stdClass();
        $this->productDeliveryAddress->state = $state;
        $this->productDeliveryAddress->country = $country;
        $this->productDeliveryAddress->pincode = $postal_code;
        $this->productDeliveryAddress->city = $city;
        return true;
    }
    
    /**
     * setProductWeight
     *
     * @param  mixed $weight
     * @param  mixed $unit
     * @return void
     */
    public function setProductWeight($weight, $unit = "ounces") {

        $this->productWeight = new stdClass();
        $this->productWeight->value = intval($weight);
        $this->productWeight->units = trim('ounces');
        return true;
    }
    
    /**
     * setProductDim
     *
     * @param  mixed $length
     * @param  mixed $width
     * @param  mixed $height
     * @return void
     */
    public function setProductDim($length, $width, $height) {

        $this->productDim = new stdClass();
        $this->productDim->length = $length;
        $this->productDim->width = $width;
        $this->productDim->height = $height;
        $this->productDim->units = "centimeters";
        return true;
    }
    
    /**
     * getProductDim
     *
     * @return void
     */
    public function getProductDim() {
        return $this->productDim;
    }
    
    /**
     * getProductWeight
     *
     * @return void
     */
    public function getProductWeight() {
        return $this->productWeight;
    }
    
    /**
     * getProductDeliveryAddress
     *
     * @return void
     */
    public function getProductDeliveryAddress() {
        return $this->productDeliveryAddress;
    }
    
    /**
     * validateShipstationAccount
     *
     * @param  mixed $api_key
     * @param  mixed $api_secret
     * @return void
     */
    public function validateShipstationAccount($api_key, $api_secret) {

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
}
