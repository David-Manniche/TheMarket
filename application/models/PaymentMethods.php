<?php

class PaymentMethods extends MyAppModel
{
    public const DB_TBL = 'tbl_payment_methods';
    public const DB_TBL_LANG = 'tbl_payment_methods_lang';
    public const DB_TBL_PREFIX = 'pmethod_';
    
    public const TYPE_DEFAULT = 1;
    public const TYPE_PLUGIN = 2;

    private $paymentPlugin = '';
    private $keyname = '';
    private $langId = '';
    private $canRefundToCard = false;
    private $resp = [];
    private $db;

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
     * initiateRefund
     * 
     * @param $orderId
     * @return mixed
     */
    public function initiateRefund(string $orderId): bool
    {
        if (false == $this->canRefundToCard) {
            $msg = Labels::getLabel('MSG_THIS_{PAYMENT-METHOD}_PAYMENT_METHOD_IS_NOT_ABLE_TO_REFUND_IN_CARD', $this->langId);
            $this->error = CommonHelper::replaceStringData($msg, ['{PAYMENT-METHOD}' => $this->keyname]);
            return false;
        }

        $orderObj = new Orders();
        $payments = $orderObj->getOrderPayments(array("order_id" => $orderId));
        CommonHelper::printArray($payments, true);
        switch ($this->keyname) {
            case 'StripeConnect':
                $requestParam = [

                ];
                // $this->resp = $this->paymentPlugin->initiateRefund($requestParam);
                break;
            
        }
        
        if (false == $this->resp) {
            $this->error = $this->paymentPlugin->getError(); 
            return false;
        }

        return true;
    }

    /**
     * getResponse
     * 
     * @return array
     */
    public function getResponse(): array
    {
        return $this->resp;
    }
}
