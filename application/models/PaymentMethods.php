<?php

class PaymentMethods extends MyAppModel
{
    public const DB_TBL = 'tbl_payment_methods';
    public const DB_TBL_LANG = 'tbl_payment_methods_lang';
    public const DB_TBL_PREFIX = 'pmethod_';
    
    public const TYPE_DEFAULT = 1;
    public const TYPE_PLUGIN = 2;

    public const MOVE_TO_ADMIN_WALLET = 0;
    public const MOVE_TO_CUSTOMER_WALLET = 1;
    public const MOVE_TO_CUSTOMER_CARD = 2;

    public const REFUND_TYPE_RETURN = 1;
    public const REFUND_TYPE_CANCEL = 2;

    private $paymentPlugin = '';
    private $keyname = '';
    private $langId = '';
    private $canRefundToCard = false;
    private $resp = [];
    private $db;
    private $sellerId = '';
    private $opId = '';
    private $extraAmount = '';
    private $transferId = '';
    private $invoiceNumber = '';

    public function __construct($id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
        $this->db = FatApp::getDb();
        $this->objMainTableRecord->setSensitiveFields(
            array(
            'pmethod_code'
            )
        );
    }

    public static function getSearchObject($langId = 0, $isActive = true)
    {
        $langId = FatUtility::int($langId);

        $srch = new SearchBase(static::DB_TBL, 'pm');
        if ($isActive == true) {
            $srch->addCondition('pm.' . static::DB_TBL_PREFIX . 'active', '=', applicationConstants::ACTIVE);
        }

        if ($langId > 0) {
            $srch->joinTable(
                static::DB_TBL_LANG,
                'LEFT OUTER JOIN',
                'pm_l.pmethodlang_' . static::DB_TBL_PREFIX . 'id = pm.' . static::DB_TBL_PREFIX . 'id and pm_l.pmethodlang_lang_id = ' . $langId,
                'pm_l'
            );
        }

        $srch->addOrder('pm.' . static::DB_TBL_PREFIX . 'active', 'DESC');
        $srch->addOrder('pm.' . static::DB_TBL_PREFIX . 'display_order', 'ASC');
        return $srch;
    }

    public function cashOnDeliveryIsActive()
    {
        $paymentMethod = PaymentMethods::getSearchObject();
        $paymentMethod->addMultipleFields(array('pmethod_id', 'pmethod_code', 'pmethod_active'));
        $paymentMethod->addCondition('pmethod_code', '=', 'cashondelivery');
        $paymentMethod->addCondition('pmethod_active', '=', applicationConstants::YES);
        $rs = $paymentMethod->getResultSet();
        if (FatApp::getDb()->fetch($rs)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * canRefundToCard
     *
     * @param string $keyname
     * @param int $langId
     * @return bool
     */
    public function canRefundToCard(string $keyname, int $langId, int $methodType = self::TYPE_PLUGIN): bool
    {
        if (self::TYPE_DEFAULT == $methodType) {
            return false;
        }
        
        $this->keyname = $keyname;
        $this->langId = $langId;
        $this->paymentPlugin = PluginHelper::callPlugin($this->keyname, [$this->langId]);
        return $this->canRefundToCard = method_exists($this->paymentPlugin, 'initiateRefund');
    }

    /**
     * moveRefundLocationsArr
     *
     * @param type $langId
     * @return array
     */
    public static function moveRefundLocationsArr($langId = 0): array
    {
        $langId = FatUtility::int($langId);
        if ($langId < 1) {
            $langId = FatApp::getConfig('CONF_ADMIN_DEFAULT_LANG');
        }

        return [
            self::MOVE_TO_ADMIN_WALLET => Labels::getLabel('LBL_MOVE_TO_ADMIN_WALLET', $langId),
            self::MOVE_TO_CUSTOMER_WALLET => Labels::getLabel('LBL_MOVE_TO_CUSTOMER_WALLET', $langId),
            self::MOVE_TO_CUSTOMER_CARD => Labels::getLabel('LBL_MOVE_TO_CUSTOMER_CARD', $langId),
        ];
    }

    private function formatPayableAmount($amount)
    {
        $amount = number_format($amount, 2, '.', '');
        return $amount * 100;
    }

    /**
     * initiateRefund
     *
     * @param $opId
     * @return mixed
     */
    public function initiateRefund(string $opId, int $refundType = self::REFUND_TYPE_RETURN): bool
    {
        $db = FatApp::getDb();
        if (false == $this->canRefundToCard) {
            $msg = Labels::getLabel('MSG_THIS_{PAYMENT-METHOD}_PAYMENT_METHOD_IS_NOT_ABLE_TO_REFUND_IN_CARD', $this->langId);
            $this->error = CommonHelper::replaceStringData($msg, ['{PAYMENT-METHOD}' => $this->keyname]);
            return false;
        }
        $this->opId = $opId;

        $orderObj = new Orders();
        $childOrderInfo = $orderObj->getOrderProductsByOpId($this->opId, $this->langId);
        $payments = $orderObj->getOrderPayments(["order_id" => $childOrderInfo['op_order_id']]);

        $this->sellerId = $childOrderInfo['op_selprod_user_id'];
        $this->invoiceNumber = $childOrderInfo['op_invoice_number'];

        $txnData = $this->getTransferTxnData();

        if (!empty($txnData)) {
            foreach ($txnData as $txn) {
                if (!empty($txn['utxn_gateway_txn_id'])) {
                    $this->transferId = $txn['utxn_gateway_txn_id'];
                    $this->extraAmount = $txn['utxn_debit'];
                    break;
                }
                $this->extraAmount = $txn['utxn_credit'];
            }

            if (empty($this->transferId)) {
                // Partial Amount if credited. (Discount Or Rewards Point)
                $this->refundFromWallet();
            }
        }

        $txnId = "";
        array_walk($payments, function ($value, $key) use (&$txnId) {
            if ($this->keyname == $value['opayment_method']) {
                $txnId = $value['opayment_gateway_txn_id'];
                return;
            }
        });

        $checkShipping = false;
        if (0 < $childOrderInfo["op_free_ship_upto"] && array_key_exists(OrderProduct::CHARGE_TYPE_SHIPPING, $childOrderInfo['charges']) && $childOrderInfo["op_actual_shipping_charges"] != $childOrderInfo['charges'][OrderProduct::CHARGE_TYPE_SHIPPING]['opcharge_amount']) {
            $checkShipping = true;
        }

        switch ($refundType) {
            case self::REFUND_TYPE_RETURN:
                $txnAmount = $childOrderInfo['op_refund_amount'];
                break;
            
            case self::REFUND_TYPE_CANCEL:
                $txnAmount = ($childOrderInfo["op_unit_price"] * $childOrderInfo["op_qty"]);
                
                /*Deduct Shipping Amount[*/
                $actualShipCharges = 0;
                if (true === $checkShipping && $childOrderInfo["op_free_ship_upto"] > $txnAmount) {
                    $unitShipCharges = round($childOrderInfo['op_actual_shipping_charges'] / $childOrderInfo["op_qty"], 2);
                    $returnShipChargesToCust = 0;
                    if (FatApp::getConfig('CONF_RETURN_SHIPPING_CHARGES_TO_CUSTOMER', FatUtility::VAR_INT, 0)) {
                        $returnShipChargesToCust = $unitShipCharges * $childOrderInfo["op_refund_qty"];
                    }

                    $actualShipCharges = $childOrderInfo['op_actual_shipping_charges'] - $returnShipChargesToCust;
                    $txnAmount -= $actualShipCharges;
                }
                
                $txnAmount += $childOrderInfo["op_other_charges"];
                break;
            
            default:
                $this->error = Labels::getLabel('MSG_INVALID_REFUND_TYPE', $this->langId);
                return false;
                break;
        }

        switch ($this->keyname) {
            case 'StripeConnect':
                $requestParam = [
                    'amount' => $this->formatPayableAmount($txnAmount),
                    'payment_intent' => $txnId,
                    'reverse_transfer' => true
                ];
                if (false === $this->paymentPlugin->init(true)) {
                    $this->error = $this->paymentPlugin->getError();
                    return false;
                }

                $respStatus = $this->paymentPlugin->initiateRefund($requestParam);
                if (false == $respStatus) {
                    $this->error = $this->paymentPlugin->getError();
                    return false;
                }

                if (!empty($this->transferId)) {
                    $comments = Labels::getLabel('MSG_REFUND_INITIATE_REGARDING_#{invoice-no}', $this->langId);
                    $comments = CommonHelper::replaceStringData($comments, ['{invoice-no}' => $this->invoiceNumber]);
                    $requestParam = [
                        'transferId' => $this->transferId,
                        'data' => [
                            'amount' => $this->formatPayableAmount($this->extraAmount), // In Paisa
                            'description' => $comments,
                            'metadata' => [
                                'op_id' => $this->opId
                            ],
                        ],
                    ];
                    $respStatus = $this->paymentPlugin->revertTransfer($requestParam);
                    if (false == $respStatus) {
                        $this->error = $this->paymentPlugin->getError();
                        return false;
                    }
                }
                //To get response object
                $this->resp = $this->paymentPlugin->getResponse();
                break;
        }
        return true;
    }
    
    /**
     * getTransferTxnData
     *
     * @return void
     */
    public function getTransferTxnData(int $sellerId = 0, int $opId = 0)
    {
        $sellerId = 0 < $sellerId ? $sellerId : $this->sellerId;
        $opId = 0 < $opId ? $opId : $this->opId;

        $db = FatApp::getDb();
        $srch = Transactions::getUserTransactionsObj($sellerId);
        $srch->addCondition('utxn.utxn_type', '=', Transactions::TYPE_TRANSFER_TO_THIRD_PARTY_ACCOUNT);
        $srch->addCondition('utxn.utxn_op_id', '=', $opId);
        $srch->addOrder('utxn_gateway_txn_id', 'DESC');
        $rs = $srch->getResultSet();
        $records = $db->fetchAll($rs);
        if (!$records) {
            $this->error = $db->getError();
            return false;
        }
        return $records;
    }
    
    /**
     * refundFromWallet - Partial Amount if credited. (Discount Or Rewards Point)
     *
     * @return bool
     */
    private function refundFromWallet()
    {
        $comments = Labels::getLabel('MSG_REFUND_INITIATE_REGARDING_#{invoice-no}', $this->langId);
        $comments = CommonHelper::replaceStringData($comments, ['{invoice-no}' => $this->invoiceNumber]);
        Transactions::debitWallet($this->sellerId, Transactions::TYPE_ORDER_REFUND, $this->extraAmount, $this->langId, $comments, $this->opId);
        return true;
    }

    /**
     * getResponse
     *
     * @return object
     */
    public function getResponse(): object
    {
        return empty($this->resp) ? (object) array() : $this->resp;
    }
}
