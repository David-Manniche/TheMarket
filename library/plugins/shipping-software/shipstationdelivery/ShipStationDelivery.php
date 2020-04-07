<?php
class ShipStationDelivery extends ShippingSoftwareBase
{
    public const KEY_NAME = __CLASS__;
    public $shipStation;

    public $requiredKeys = [
        'api_key',
        'api_secret_key'
    ];

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

    private function initialize()
    {      
        include_once dirname(__FILE__) . '/libraries/unirest/Unirest.php';
        include_once dirname(__FILE__) . '/libraries/shipstation/Shipstation.class.php';

        $apiKey = $this->settings['api_key'];
        $apiSecret = $this->settings['api_secret_key'];
        $this->shipStation 	= new Shipstation();
        $this->shipStation->setSsApiKey($apiKey);
        $this->shipStation->setSsApiSecret($apiSecret);
    }
    
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

    public function getWarehouses()
    {
        return $this->shipStation->getWarehouses();
    }

    public function getOrder($orderId)
    {
        return $this->shipStation->getOrder($orderId);
    }

    public function addOrder()
    {
        $order    = new stdClass();

        $order->orderId           = null;
        $order->orderNumber       = "TEST001";
        $order->orderKey          = null; // if specified, the method becomes idempotent and the existing Order with that key will be updated
        $order->orderDate         = date('Y-m-d').'T'.date('H:i:s').'.0000000';
        $order->paymentDate       = date('Y-m-d').'T'.date('H:i:s').'.0000000';
        $order->orderStatus       = "awaiting_shipment"; // {awaiting_shipment, on_hold, shipped, cancelled}
        $order->customerUsername  = "Otoniel Ortega";
        $order->customerEmail     = "ortega.x3@gmail.com";
        $order->amountPaid        = 150.00;
        $order->taxAmount         = 25.00;
        $order->shippingAmount    = 25.00;
        $order->customerNotes     = null;
        $order->internalNotes     = "Express Shipping Please";
        $order->gift              = null;
        $order->giftMessage       = null;
        $order->requestedShippingService     = "Priority Mail";
        $order->paymentMethod     = null;
        $order->carrierCode       = "fedex";
        $order->serviceCode       = "fedex_2day";
        $order->packageCode       = "package";
        $order->confirmation      = null;
        $order->shipDate          = null;


        // Define billing address //

        $billing    = new stdClass();

        $billing->name          = "Otoniel Ortega"; // This has to be a String... If you put NULL the API cries...
        $billing->company       = null;
        $billing->street1       = null;
        $billing->street2       = null;
        $billing->street3       = null;
        $billing->city          = null;
        $billing->state         = null;
        $billing->postalCode    = null;
        $billing->country       = null;
        $billing->phone         = null;
        $billing->residential   = null;

        $order->billTo          = $billing;


        // Define shipping address //

        $shipping    = new stdClass();

        $shipping->name         = "Otoniel Ortega";
        $shipping->company      = "Go-Parts";
        $shipping->street1      = "Santa Clarita #1234";
        $shipping->street2      = null;
        $shipping->street3      = null;
        $shipping->city         = "Los Angeles";
        $shipping->state        = "CA";
        $shipping->postalCode   = "90002";
        $shipping->country      = "US";
        $shipping->phone        = "555-555-5555";
        $shipping->residential  = true;

        $order->shipTo          = $shipping;


        // Order weight //

        $weight      = new stdClass();

        $weight->value          = 16;
        $weight->units          = "ounces";

        $order->weight          = $weight;


        // Extra order data //

        $dimensions   = new stdClass();

        $dimensions->units      = "inches";
        $dimensions->length     = 5;
        $dimensions->width      = 10;
        $dimensions->height     = 15;

        $order->dimensions      = $dimensions;


        // Insurance options //

        $insuranceOptions   = new stdClass();

        $insuranceOptions->provider         = null;
        $insuranceOptions->insureShipment   = false;
        $insuranceOptions->insuredValue     = 0;

        $order->insuranceOptions            = $insuranceOptions;


        // International options //

        $internationalOptions   = new stdClass();

        $internationalOptions->contents     = null;
        $internationalOptions->customsItems = null;

        $order->internationalOptions        = $internationalOptions;


        // International options //

        $advancedOptions    = new stdClass();

        $advancedOptions->warehouseId       = "{Your Warehouse ID Here}";
        $advancedOptions->nonMachinable     = false;
        $advancedOptions->saturdayDelivery  = false;
        $advancedOptions->containsAlcohol   = false;
        $advancedOptions->storeId           = "{Your Store ID Here}";
        $advancedOptions->customField1      = "";
        $advancedOptions->customField2      = "";
        $advancedOptions->customField3      = "";
        $advancedOptions->source            = null;

        $order->advancedOptions             = $advancedOptions;


        // Add items to order [START] ================================= //
        
        // Loop the order products here...

        // Item //

        $item   = new stdClass();

        $item->lineItemKey          = null;
        $item->sku                  = "TEST-0001-000";
        $item->name                 = "Quad Core Android PC";
        $item->imageUrl             = "http://img.dxcdn.com/productimages/sku_214428_1.jpg";
        $item->weight               = null;
        $item->quantity             = 1;
        $item->unitPrice            = 0;
        $item->warehouseLocation    = null;
        $item->options              = array();

            // Product weight //

            $weight        = new stdClass();

            $weight->value = 16;
            $weight->units = "ounces";


        $item->weight      = $weight;


        // Add to items array //
        $items[] 	= $item;


        $order->items                   = $items;

        // Add items to order [END] =================================== //


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

    public function getCarriers() {

        if (!$list = $this->shipStation->getCarriers()) {
            $error = $this->shipStation->getLastError();
            throw new Exception($error->message);
        }
        return $list;
    }

    public function setProductDeliveryAddress($state, $country, $city, $postal_code) {
        $this->productDeliveryAddress = new stdClass();
        $this->productDeliveryAddress->state = $state;
        $this->productDeliveryAddress->country = $country;
        $this->productDeliveryAddress->pincode = $postal_code;
        $this->productDeliveryAddress->city = $city;
        return true;
    }

    public function setProductWeight($weight, $unit = "ounces") {

        $this->productWeight = new stdClass();
        $this->productWeight->value = intval($weight);
        $this->productWeight->units = trim('ounces');
        return true;
    }

    public function setProductDim($length, $width, $height) {

        $this->productDim = new stdClass();
        $this->productDim->length = $length;
        $this->productDim->width = $width;
        $this->productDim->height = $height;
        $this->productDim->units = "centimeters";
        return true;
    }

    public function getProductDim() {
        return $this->productDim;
    }

    public function getProductWeight() {
        return $this->productWeight;
    }

    public function getProductDeliveryAddress() {
        return $this->productDeliveryAddress;
    }

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
