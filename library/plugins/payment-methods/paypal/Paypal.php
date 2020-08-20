<?php

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class Paypal extends PaymentMethodBase
{
    public const KEY_NAME = __CLASS__;

    public $requiredKeys = [
        'client_id',
        'secret_key',
    ];

    private $clientId = '';
    private $secretKey = '';
    private $resp;
    /**
     * __construct
     *
     * @param  int $langId
     * @return void
     */
    public function __construct(int $langId)
    {
        $this->langId = 0 < $langId ? $langId : CommonHelper::getLangId();
    }


    /**
     * init
     *
     * @param int $userId
     * @return bool
     */
    public function init(int $userId): bool
    {
        if (false == $this->validateSettings()) {
            return false;
        }

        if (false === $this->loadBaseCurrencyCode()) {
            return false;
        }

        $this->clientId = 0 < $this->settings['env'] ? $this->settings['live_client_id'] : $this->settings['client_id'];
        $this->secretKey = 0 < $this->settings['env'] ? $this->settings['live_secret_key'] : $this->settings['secret_key'];
        if (false === $this->loadLoggedUserInfo($userId)) {
            return false;
        }
        return true;
    }

    /**
     * environment - Setup Paypal Environment.
     *
     * @return object
     */
    public function environment(): object
    {
        return (0 < $this->settings['env'] ? new ProductionEnvironment($this->clientId, $this->secretKey) : new SandboxEnvironment($this->clientId, $this->secretKey));
    }

    /**
     * client - Setup Paypal Client.
     *
     * @return object
     */
    public function client(): object
    {
        return new PayPalHttpClient($this->environment());
    }

    //=== Create papal order    
    /**
     * createOrder
     *
     * @param  mixed $orderId
     * @return bool
     */
    public function createOrder(string $orderId): bool
    {
        //=== Create New Order Request
        $request = new OrdersCreateRequest();
        $request->prefer("return=representation");

        //=== Create Request Body
        $request->body = $this->buildRequestBody($orderId);
        //=== Call PayPal to set up a transaction
        return $this->executeRequest($request);
    }

    /**
     * captureOrder
     *
     * @param  mixed $paypalOrderId
     * @return bool
     */
    public function captureOrder($paypalOrderId): bool
    {
        $request = new OrdersCaptureRequest($paypalOrderId);
        //=== Call PayPal to get the transaction details
        return $this->executeRequest($request);
    }

    /**
     * getResponse
     *
     * @return object
     */
    public function getResponse(): object
    {
        return $this->resp;
    }

    /**
     * buildRequestBody
     *
     * @param  string $orderId
     * @return array
     */
    private function buildRequestBody(string $orderId): array
    {
        $orderPaymentObj = new OrderPayment($orderId, $this->langId);
        $paymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();
        $orderInfo = $orderPaymentObj->getOrderPrimaryinfo();
        $orderObj = new Orders();
        $orderAddresses = $orderObj->getOrderAddresses($orderId);
        $shippingAddress = $orderAddresses[Orders::SHIPPING_ADDRESS_TYPE];
        $billingAddress = $orderAddresses[Orders::BILLING_ADDRESS_TYPE];

        $cancelBtnUrl = CommonHelper::getPaymentCancelPageUrl();
        if ($orderInfo['order_type'] == Orders::ORDER_WALLET_RECHARGE) {
            $cancelBtnUrl = CommonHelper::getPaymentFailurePageUrl();
        }

        $request_body = $purchase_units = $pu_amount = [];

        //=== Prepare amount & break down of amount for order
        $pu_amount["currency_code"] = $this->systemCurrencyCode;
        $pu_amount["value"] = $paymentAmount;
        $purchase_units["reference_id"] = $orderId;
        $purchase_units["amount"] = $pu_amount;
        $purchase_units["shipping"] = [
            "address_line_1" => $shippingAddress['oua_address1'],
            "address_line_2" => $shippingAddress['oua_address2'],
            "admin_area_1" => $shippingAddress['oua_state_code'],
            "admin_area_2" => $shippingAddress['oua_city'],
            "postal_code" => $shippingAddress['oua_zip'],
            "country_code" => $shippingAddress['oua_country_code']
        ];

        $request_body["intent"] = "CAPTURE";
        $request_body["payer"] = [
            "name" => [
                "given_name" => $orderInfo['customer_name'],
            ],
            "address" => [
                "address_line_1" => $billingAddress['oua_address1'],
                "address_line_2" => $billingAddress['oua_address2'],
                "admin_area_1" => $billingAddress['oua_state_code'],
                "admin_area_2" => $billingAddress['oua_city'],
                "postal_code" => $billingAddress['oua_zip'],
                "country_code" => $billingAddress['oua_country_code']
            ],
            "email_address" => $orderInfo['customer_email'],
            "phone" => [
                "phone_type" => "MOBILE",
                "phone_number" => [
                    "national_number" => $orderInfo['customer_phone']
                ]
            ]
        ];
        $request_body["purchase_units"][] = $purchase_units;
        $request_body["application_context"] = [
            "cancel_url" => $cancelBtnUrl,
            "return_url" => UrlHelper::generateFullUrl(self::KEY_NAME, "callback")
        ];

        return $request_body;
    }


    /**
     * executeRequest
     *
     * @param  mixed $request
     * @return bool
     */
    private function executeRequest($request): bool
    {
        try {
            $client = $this->client();
            //=== Return a response to the client.
            $this->resp = $client->execute($request);
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $msg = LibHelper::isJson($e->getMessage()) ? json_decode($e->getMessage(), true) : $e->getMessage();
            $this->error = $msg;
            return false;
        } catch (\Error $e) {
            // Handle error
            $msg = LibHelper::isJson($e->getMessage()) ? json_decode($e->getMessage(), true) : $e->getMessage();
            $this->error = $msg;
            return false;
        } catch (HttpException $e) {
            $msg = LibHelper::isJson($e->getMessage()) ? json_decode($e->getMessage(), true) : $e->getMessage();
            $this->error = $msg;
            return false;
        }
        return true;
    }
}
