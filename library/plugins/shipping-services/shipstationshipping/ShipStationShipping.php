<?php
require_once dirname(__FILE__) . '/ShipStationFunctions.php';

class ShipStationShipping extends ShippingServicesBase
{
    use ShipStationFunctions;

    public const KEY_NAME = __CLASS__;
    public const HOST = 'ssapi.shipstation.com';
    public const PRODUCTION_URL = 'https://' . self::HOST . '/';

    private const REQUEST_CARRIER_LIST = 1;
    private const REQUEST_SHIPPING_RATES = 2;
    private const REQUEST_CREATE_ORDER = 3;
    private const REQUEST_CREATE_LABEL = 4;
    
    private $resp;
    private $canGenerateLabelForOrder = false;
    private $endpoint = '';

    public $requiredKeys = [
        'api_key',
        'api_secret_key'
    ];
        
    /**
     * __construct
     *
     * @return void
     */
    public function __construct($langId)
    {
        $this->langId = FatUtility::int($langId);
        if (1 > $this->langId) {
            $this->langId = CommonHelper::getLangId();
        }
        $this->init();
    }
        
    /**
     * init
     *
     * @return bool
     */
    private function init(): bool
    {
        if (false == $this->validateSettings($this->langId)) {
            trigger_error($this->getError(), E_USER_ERROR);
        }
        return true;
    }

    /**
     * getCarriers
     *
     * @param  bool $assoc
     * @param  int $langId
     * @return array
     */
    public function getCarriers(bool $assoc = false, int $langId = 0): array
    {
        if (false === $this->doRequest(self::REQUEST_CARRIER_LIST)) {
            // CommonHelper::printArray($this->error, true);
            return [];
        }
        $list = $this->getResponse();
        if (true === $assoc) {
            $langId = 1 > $langId ? commonHelper::getLangId() : $langId;
            $list = array_reduce($list, function ($result, $item) {
                $name = $item['name'];
                if (!empty($item['nickname']) && strtolower($item['name']) !== strtolower($item['nickname'])) {
                    $name .= ' - ' . $item['nickname'];
                }
                $result[$item['code']] = $name;
                return $result;
            });
            array_unshift($list, Labels::getLabel('MSG_SELECT_CARRIER', $langId));
        }

        return $list;
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
        $pkgDetail = [
            'carrierCode' => $carrier_code,
            'serviceCode' => null,
            'packageCode' => null,
            'fromPostalCode' => $from_pin_code,
            'toState' => $this->address['state'],
            'toCountry' => $this->address['country'],
            'toPostalCode' => $this->address['postalCode'],
            'toCity' => $this->address['city'],
            'weight' => $this->getWeight(),
            'dimensions' => $this->getDimensions()
        ];
        
        if (false === $this->doRequest(self::REQUEST_SHIPPING_RATES, $pkgDetail)) {
            return false;
        }
        return $this->getResponse();
    }
   
    /**
     * formatShippingRates
     *
     * @return array
     */
    public function formatShippingRates(): array
    {
        $rateOptions = [];
        if (!empty($this->getResponse())) {
            $rateOptions[] = Labels::getLabel('MSG_Select_Service', $this->langId);
            foreach ($this->getResponse() as $key => $value) {
                $code = $value['serviceCode'];
                $price = $value['shipmentCost'] + $value['otherCost'];
                $name = $value['serviceName'];
                $displayPrice = CommonHelper::displayMoneyFormat($price);

                $label = $name . " (" . $displayPrice . " )";
                $rateOptions[$code . "-" . $price] = $label;
            }
        }

        return $rateOptions;
    }

    /**
     * addOrder
     *
     * @param  string $orderId
     * @param  int $opId
     * @return void
     */
    public function addOrder(string $orderId, int $opId)
    {
        $orderDetail = $this->getSystemOrder($orderId, $this->langId, $opId);
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

            $this->order = [];
            $this->order['orderNumber'] = $orderInvoiceNumber;
            $this->order['orderKey'] = $orderInvoiceNumber; // if specified, the method becomes idempotent and the existing Order with that key will be updated
            $this->order['orderDate'] = $orderDate;
            $this->order['paymentDate'] = $orderDate;
            $this->order['orderStatus'] = "awaiting_shipment"; // {awaiting_shipment, on_hold, shipped, cancelled}
            $this->order['customerUsername'] = $orderDetail['buyer_user_name'];
            $this->order['customerEmail'] = $orderDetail['buyer_email'];
            $this->order['amountPaid'] = $orderDetail['order_net_amount'];
            $this->order['taxAmount'] = (1 > $taxCharged ? $orderDetail['order_tax_charged'] : $taxCharged);
            $this->order['shippingAmount'] = $shippingTotal;
            /* $this->order['customerNotes']     = null;
            $this->order['internalNotes']     = "Express Shipping Please";
            $this->order['requestedShippingService']     = "Priority Mail"; */
            $this->order['paymentMethod'] = $orderDetail['pmethod_name'];
            /* $this->order['carrierCode']       = "fedex"; */
            $this->order['serviceCode']       = $op['opshipping_carrier'];
            $this->order['packageCode'] = "package";
            /* $this->order['confirmation']      = null;
            $this->order['shipDate']          = null; */


            $this->setAddress($billingAddress['oua_name'], $billingAddress['oua_address1'], $billingAddress['oua_address2'], $billingAddress['oua_city'], $billingAddress['oua_state'], $billingAddress['oua_zip'], $billingAddress['oua_country_code'], $billingAddress['oua_phone']);
            $this->order['billTo'] = $this->getAddress();

            $this->setAddress($shippingAddress['oua_name'], $shippingAddress['oua_address1'], $shippingAddress['oua_address2'], $shippingAddress['oua_city'], $shippingAddress['oua_state'], $shippingAddress['oua_zip'], $shippingAddress['oua_country_code'], $shippingAddress['oua_phone']);
            $this->order['shipTo'] = $this->getAddress();

            $weightUnitsArr = applicationConstants::getWeightUnitsArr($this->langId);
            $weightUnitName = ($op['op_product_weight_unit']) ? $weightUnitsArr[$op['op_product_weight_unit']] : '';
            $productWeightInOunce = $this->convertWeightInOunce($op['op_product_weight'], $weightUnitName);

            $this->setWeight($productWeightInOunce);
            $this->order['weight'] = $this->getWeight();

            $lengthUnitsArr = applicationConstants::getLengthUnitsArr($this->langId);
            $dimUnitName = ($op['op_product_dimension_unit']) ? $lengthUnitsArr[$op['op_product_dimension_unit']] : '';

            $lengthInCenti = $this->convertLengthInCenti($op['op_product_length'], $dimUnitName);
            $widthInCenti = $this->convertLengthInCenti($op['op_product_width'], $dimUnitName);
            $heightInCenti = $this->convertLengthInCenti($op['op_product_height'], $dimUnitName);

            $this->setDimensions($lengthInCenti, $widthInCenti, $heightInCenti);
            $this->order['dimensions'] = $this->getDimensions();

            $this->setItem($op);
            $this->order['items'] = [$this->getItem()];
        }

        $this->canGenerateLabelForOrder = true;
        return $this->doRequest(self::REQUEST_CREATE_ORDER, $this->order);
    }
        
    /**
     * bindLabel - This function should be called after addOrder
     *
     * @return void
     */
    public function bindLabel()
    {
        if (false === $this->canGenerateLabelForOrder) {
            $this->error = Labels::getLabel('MSG_ORDER_CONFIRMATION_IS_REQUIRED', $this->langId);
            return false;
        }
        return $this->doRequest(self::REQUEST_CREATE_LABEL, $this->getResponse());
    }
        
    /**
     * downloadLabel
     *
     * @param  mixed $opId
     * @return void
     */
    public function downloadLabel(int $opId)
    {
        $orderProductShipmentDetail = $this->getOrderProductShipment($opId);
        $shipmentResponse = json_decode($orderProductShipmentDetail['opship_response'], true);
        $trackingNumber = $orderProductShipmentDetail['opship_tracking_number'];

        $filename = "label-" . $trackingNumber . ".pdf";
        
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Type: application/pdf");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Transfer-Encoding: binary");
        
        return base64_decode($shipmentResponse['labelData']);
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
     * @return bool
     */
    public function setAddress(string $name, string $stt1, string $stt2, string $city, string $state, string $zip, string $countryCode, string $phone): bool
    {
        $this->address = [];

        $this->address['name'] = $name; // This has to be a String... If you put NULL the API cries...
        // $this->address['company']       = null;
        $this->address['street1'] = $stt1;
        $this->address['street2'] = $stt2;
        $this->address['city'] = $city;
        $this->address['state'] = $state;
        $this->address['postalCode'] = $zip;
        $this->address['country'] = $countryCode;
        $this->address['phone'] = $phone;
        return true;
    }
    
    /**
     * getAddress
     *
     * @return array
     */
    public function getAddress(): array
    {
        return empty($this->address) ? [] : $this->address;
    }
          
    /**
     * setWeight
     *
     * @param  float $weight
     * @param  string $unit
     * @return bool
     */
    public function setWeight($weight, $unit = 'ounces'): bool
    {
        $this->weight = [];
        $this->weight['value'] = floatval($weight);
        $this->weight['units'] = trim($unit);

        return true;
    }

    /**
     * getWeight
     *
     * @return object
     */
    public function getWeight(): array
    {
        return empty($this->weight) ? [] : $this->weight;
    }
         
    /**
     * setDimensions
     *
     * @param  int $length
     * @param  int $width
     * @param  int $height
     * @param  string $unit
     * @return bool
     */
    public function setDimensions($length, $width, $height, $unit = 'centimeters'): bool
    {
        $this->dimensions = [];

        $this->dimensions['units'] = $unit;
        $this->dimensions['length'] = $length;
        $this->dimensions['width'] = $width;
        $this->dimensions['height'] = $height;

        return true;
    }

    /**
     * getDimensions
     *
     * @return object
     */
    public function getDimensions(): array
    {
        return empty($this->dimensions) ? [] : $this->dimensions;
    }
         
    /**
     * setItem
     *
     * @param  array $op
     * @return bool
     */
    public function setItem($op): bool
    {
        $this->item = [];

        $this->item['lineItemKey'] = $op['op_product_name'];
        $this->item['sku'] = $op['op_selprod_sku'];
        $this->item['name'] = $op['op_selprod_title'];
        $this->item['imageUrl'] = CommonHelper::generateFullUrl('image', 'product', array($op['selprod_product_id'], "THUMB", $op['op_selprod_id'], 0, $this->langId));
        $this->item['weight'] = $this->order['weight'];
        $this->item['quantity'] = $op['op_qty'];
        $this->item['unitPrice'] = $op['op_unit_price'];
        return true;
    }

    /**
     * getItem
     *
     * @return array
     */
    public function getItem(): array
    {
        return empty($this->item) ? [] : $this->item;
    }
}
