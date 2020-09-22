<?php

class OrderShopSearch extends SearchBase
{
    private $langId;
    private $isOrdersTableJoined;
    private $isOrderUserTableJoined;
    private $isOrderStatusJoined;
    private $commonLangId;

    public function __construct($langId = 0, $joinOrders = false, $joinOrderStatus = false, $deletedOrders = false)
    {
        parent::__construct(OrderShop::DB_TBL, 'os');
        $this->langId = FatUtility::int($langId);
        $this->isOrdersTableJoined = false;
        $this->isOrderUserTableJoined = false;
        $this->isOrderProductStatusJoined = false;
        $this->commonLangId = CommonHelper::getLangId();
       
        if ($joinOrders) {
            $this->joinOrders();
        }

        if ($joinOrderStatus) {
            $this->joinOrderStatus($this->langId);
        }

        if ($joinOrders && false == $deletedOrders) {
            $this->addCondition('order_deleted', '=', applicationConstants::NO);
        }
    }

    public function joinOrders()
    {
        if ($this->isOrdersTableJoined) {
            trigger_error(Labels::getLabel('MSG_Orders_Table_is_already_joined', $this->commonLangId), E_USER_ERROR);
        }
        $this->isOrdersTableJoined = true;
        $this->joinTable(Orders::DB_TBL, 'INNER JOIN', 'o.order_id = os.os_order_id', 'o');
    }

    public function joinOrderStatus($langId = 0)
    {
        $langId = FatUtility::int($langId);
        if ($this->langId) {
            $langId = $this->langId;
        }
        if ($this->isOrderStatusJoined) {
            trigger_error(Labels::getLabel('MSG_OrderProduct_Status_is_already_joined', $this->commonLangId), E_USER_ERROR);
        }
        $this->isOrderProductStatusJoined = true;
        $this->joinTable(Orders::DB_TBL_ORDERS_STATUS, 'LEFT OUTER JOIN', 'ost.orderstatus_id = os.os_status', 'ost');
        if ($langId) {
            $this->joinTable(Orders::DB_TBL_ORDERS_STATUS_LANG, 'LEFT OUTER JOIN', 'ost_l.orderstatuslang_orderstatus_id = ost.orderstatus_id AND ost_l.orderstatuslang_lang_id = ' . $langId, 'ost_l');
        }
    }
	
	public function joinOrderUser()
    {
        if (!$this->isOrdersTableJoined) {
            trigger_error(Labels::getLabel('MSG_joinOrderUser_can_be_joined_only,_if_joinOrders_is_Joined,_So,_Please_Use_joinOrders()_first,_then_try_to_join_joinOrderUser', $this->commonLangId), E_USER_ERROR);
        }
        $this->joinTable(User::DB_TBL, 'INNER JOIN', 'ou.user_id = o.order_user_id', 'ou');
        $this->joinTable(User::DB_TBL_CRED, 'INNER JOIN', 'ou.user_id = ouc.credential_user_id', 'ouc');
        $this->isOrderUserTableJoined = true;
    }
	
	public function joinShop($langId)
    {
        $this->joinTable(Shop::DB_TBL, 'INNER JOIN', 'osh.shop_id = os.os_shop_id', 'osh');
		$this->joinTable(Shop::DB_TBL_LANG, 'LEFT JOIN', 'lang.shoplang_shop_id = ' . Shop::DB_TBL_PREFIX . 'id AND shoplang_lang_id = ' . $langId, 'lang');
    }
	
	public function addDateFromCondition($dateFrom)
    {
        $dateFrom = FatDate::convertDatetimeToTimestamp($dateFrom);
        $dateFrom = date('Y-m-d', strtotime($dateFrom));

        if (!$this->isOrdersTableJoined) {
            trigger_error(Labels::getLabel('MSG_Order_Date_Condition_cannot_be_applied,_as_Orders_Table_is_not_Joined,_So,_Please_Use_joinOrders()_first,_then_try_to_add_Order_date_from_condition', $this->commonLangId), E_USER_ERROR);
        }
        if ($dateFrom != '') {
            $this->addCondition('o.order_date_added', '>=', $dateFrom . ' 00:00:00');
        }
    }

    public function addDateToCondition($dateTo)
    {
        $dateTo = FatDate::convertDatetimeToTimestamp($dateTo);
        $dateTo = date('Y-m-d', strtotime($dateTo));

        if (!$this->isOrdersTableJoined) {
            trigger_error(Labels::getLabel('MSG_Order_Date_Condition_cannot_be_applied,_as_Orders_Table_is_not_Joined,_So,_Please_Use_joinOrders()_first,_then_try_to_add_Order_date_to_condition', $this->commonLangId), E_USER_ERROR);
        }
        if ($dateTo != '') {
            $this->addCondition('o.order_date_added', '<=', $dateTo . ' 23:59:59');
        }
    }
	
	public function addMinPriceCondition($priceFrom)
    {
        if (!$this->isOrdersTableJoined) {
            trigger_error(Labels::getLabel('MSG_Order_Price_Condition_cannot_be_applied,_as_Orders_Table_is_not_Joined,_So,_Please_Use_joinOrders()_first,_then_try_to_add_Order_Price_condition', $this->commonLangId), E_USER_ERROR);
        }
        $this->addCondition('o.order_net_amount', '>=', $priceFrom);
    }

    public function addMaxPriceCondition($priceTo)
    {
        if (!$this->isOrdersTableJoined) {
            trigger_error(Labels::getLabel('MSG_Order_Price_Condition_cannot_be_applied,_as_Orders_Table_is_not_Joined,_So,_Please_Use_joinOrders()_first,_then_try_to_add_Order_Price_condition', $this->commonLangId), E_USER_ERROR);
        }
        $this->addCondition('o.order_net_amount', '<=', $priceTo);
    }
	
	public function addStatusCondition($op_status)
    {
        if (is_array($op_status)) {
            if (!empty($op_status)) {
                $this->addCondition('os.os_status', 'IN', $op_status);
            } else {
                $this->addCondition('os.os_status', '=', 0);
            }
        } else {
            $op_status_id = FatUtility::int($op_status);
            $this->addCondition('os.os_status', '=', $op_status_id);
        }
    }
}
