<?php

class OrdersController extends AdminBaseController
{
    public function __construct($action)
    {
        $ajaxCallArray = array();
        if (!FatUtility::isAjaxCall() && in_array($action, $ajaxCallArray)) {
            die($this->str_invalid_Action);
        }
        parent::__construct($action);
        $this->admin_id = AdminAuthentication::getLoggedAdminId();
        $this->canView = $this->objPrivilege->canViewOrders($this->admin_id, true);
        $this->canEdit = $this->objPrivilege->canEditOrders($this->admin_id, true);
        $this->set("canView", $this->canView);
        $this->set("canEdit", $this->canEdit);
    }

    public function index()
    {
        $this->objPrivilege->canViewOrders();
        $frmSearch = $this->getOrderSearchForm($this->adminLangId);
        $data = FatApp::getPostedData();
        if ($data) {
            $data['keyword'] = $data['id'];
            unset($data['id']);
            $frmSearch->fill($data);
        }
        $this->set('frmSearch', $frmSearch);
        $this->_template->render();
    }

    public function deletedOrders()
    {
        $this->objPrivilege->canViewOrders();
        $frmSearch = $this->getOrderSearchForm($this->adminLangId, true);
        $data = FatApp::getPostedData();
        if ($data) {
            $data['keyword'] = $data['id'];
            unset($data['id']);
            $frmSearch->fill($data);
        }
        $this->set('frmSearch', $frmSearch);
        $this->_template->render();
    }

    public function search()
    {
        $this->objPrivilege->canViewOrders();

        $frmSearch = $this->getOrderSearchForm($this->adminLangId);

        $data = FatApp::getPostedData();
        $post = $frmSearch->getFormDataFromArray($data);

        $page = (empty($data['page']) || $data['page'] <= 0) ? 1 : FatUtility::int($data['page']);
        $pageSize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);

        $srch = new OrderSearch();
        $srch->joinOrderBuyerUser();
        $srch->joinOrderPaymentMethod($this->adminLangId);

        $srch->addOrder('order_date_added', 'DESC');
        $srch->addCondition('order_type', '=', Orders::ORDER_PRODUCT);
        $srch->setPageNumber($page);
        $srch->setPageSize($pageSize);

        $srch->addMultipleFields(array('order_id', 'order_date_added', 'order_is_paid', 'order_status', 'buyer.user_id', 'buyer.user_name as buyer_user_name', 'buyer_cred.credential_email as buyer_email', 'order_net_amount', 'order_wallet_amount_charge', 'order_pmethod_id', 'IFNULL(pmethod_name, pmethod_identifier) as pmethod_name', 'pmethod_code', 'order_is_wallet_selected', 'order_deleted'));

        $keyword = FatApp::getPostedData('keyword', null, '');
        if (!empty($keyword)) {
            $srch->addKeywordSearch($keyword);
        }

        $user_id = FatApp::getPostedData('user_id', FatUtility::VAR_INT, -1);
        if ($user_id) {
            $srch->addCondition('buyer.user_id', '=', $user_id);
        }

        if (isset($post['order_is_paid']) && $post['order_is_paid'] != '') {
            $order_is_paid = FatUtility::int($post['order_is_paid']);
            $srch->addCondition('order_is_paid', '=', $order_is_paid);
        }

        $dateFrom = FatApp::getPostedData('date_from', null, '');
        if (!empty($dateFrom)) {
            $srch->addDateFromCondition($dateFrom);
        }

        $dateTo = FatApp::getPostedData('date_to', null, '');
        if (!empty($dateTo)) {
            $srch->addDateToCondition($dateTo);
        }

        $priceFrom = FatApp::getPostedData('price_from', null, '');
        if (!empty($priceFrom)) {
            $srch->addMinPriceCondition($priceFrom);
        }

        $priceTo = FatApp::getPostedData('price_to', null, '');
        if (!empty($priceTo)) {
            $srch->addMaxPriceCondition($priceTo);
        }

        $isDeleted = FatApp::getPostedData('is_deleted', FatUtility::VAR_INT, 0);
        if (!empty($isDeleted)) {
            $srch->addCondition('order_deleted', '=', applicationConstants::YES);
            $this->set("deletedOrders", true);
        } else {
            $srch->addCondition('order_deleted', '=', applicationConstants::NO);
            $this->set("deletedOrders", false);
        }

        $rs = $srch->getResultSet();
        $ordersList = FatApp::getDb()->fetchAll($rs);

        $this->set("ordersList", $ordersList);
        $this->set('pageCount', $srch->pages());
        $this->set('page', $page);
        $this->set('pageSize', $pageSize);
        $this->set('postedData', $post);
        $this->set('recordCount', $srch->recordCount());

        $this->set('canViewSellerOrders', $this->objPrivilege->canViewSellerOrders($this->admin_id, true));
        $this->set('canViewUsers', $this->objPrivilege->canViewUsers($this->admin_id, true));

        $this->_template->render(false, false);
    }

    public function View($orderId)
    {
        $this->objPrivilege->canViewOrders();

        $orderObj = new Orders();
        $order = $orderObj->getOrder($orderId, $this->adminLangId);

        $frm = $this->getPaymentForm($this->adminLangId, $order['order_id']);
        $this->set('frm', $frm);
        $this->set('yesNoArr', applicationConstants::getYesNoArr($this->adminLangId));
        $this->set('order', $order);

        $this->_template->render();
    }

    public function listChildOrderStatusLog($op_id)
    {
        $this->objPrivilege->canViewOrders();
        $op_id = FatUtility::int($op_id);
        $orderObj = new Orders();
        $comments = $orderObj->getOrderComments($this->adminLangId, array("op_id" => $op_id));
        //CommonHelper::printArray( $comments );
        $this->set('comments', $comments);
        $this->_template->render(false, false);
    }

    public function updatePayment()
    {
        $this->objPrivilege->canEditOrders();
        $frm = $this->getPaymentForm($this->adminLangId);

        $post = $frm->getFormDataFromArray(FatApp::getPostedData());
        if (false === $post) {
            Message::addErrorMessage(current($frm->getValidationErrors()));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $orderId = $post['opayment_order_id'];

        if ($orderId == '' || $orderId == null) {
            Message::addErrorMessage($this->str_invalid_request);
            FatUtility::dieJsonError(Message::getHtml());
        }

        $srch = new OrderSearch($this->adminLangId);
        $srch->joinOrderPaymentMethod();
        $srch->addMultipleFields(array('pmethod_code'));
        $srch->addCondition('order_id', '=', $orderId);
        $srch->addCondition('order_type', '=', Orders::ORDER_PRODUCT);
        $rs = $srch->getResultSet();
        $order = FatApp::getDb()->fetch($rs);
        if (!empty($order) && array_key_exists('pmethod_code', $order) && 'CashOnDelivery' == $order['pmethod_code']) {
            Message::addErrorMessage(Labels::getLabel('LBL_COD_orders_are_not_eligible_for_payment_status_update', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }

        $orderPaymentObj = new OrderPayment($orderId, $this->adminLangId);
        if (!$orderPaymentObj->addOrderPayment($post["opayment_method"], $post['opayment_gateway_txn_id'], $post["opayment_amount"], $post["opayment_comments"])) {
            Message::addErrorMessage($orderPaymentObj->getError());
            FatUtility::dieJsonError(Message::getHtml());
        }

        $this->set('msg', Labels::getLabel('LBL_Payment_Details_Added_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function Cancel($order_id)
    {
        $this->objPrivilege->canEditOrders();

        $orderObj = new Orders();
        $order = $orderObj->getOrderById($order_id);

        if ($order == false) {
            Message::addErrorMessage(Labels::getLabel('LBL_Error:_Please_perform_this_action_on_valid_record.', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }
		
		$allowedCancellationArr =  Orders::getBuyerAllowedOrderCancellationStatuses();
		$srch = new OrderProductSearch(0, true);
        $srch->addMultipleFields(array('op.op_status_id', 'o.order_id'));
        $srch->addCondition('order_id', '=', $order_id);
		$srch->addCondition('op_status_id', 'NOT IN', $allowedCancellationArr);
        $opDetails = FatApp::getDb()->fetchAll($srch->getResultSet());
		if(!empty($opDetails)) {
			Message::addErrorMessage(Labels::getLabel('LBL_Error:_Orders_that_are_completed_cannot_be_Cancelled.', $this->adminLangId));
			FatUtility::dieJsonError(Message::getHtml());
		}
		
        if ($order["order_is_paid"]) {
            if (!$orderObj->addOrderPaymentHistory($order_id, Orders::ORDER_IS_CANCELLED, Labels::getLabel('MSG_Order_Cancelled', $order['order_language_id']), 1)) {
                Message::addErrorMessage($orderObj->getError());
                FatUtility::dieJsonError(Message::getHtml());
            }

            if (!$orderObj->refundOrderPaidAmount($order_id, $order['order_language_id'])) {
                Message::addErrorMessage($orderObj->getError());
                FatUtility::dieJsonError(Message::getHtml());
            }
        }

        $this->set('msg', Labels::getLabel('LBL_Payment_Details_Cancelled_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    public function delete($order_id)
    {
        $this->objPrivilege->canEditOrders();

        $orderObj = new Orders();
        $order = $orderObj->getOrderById($order_id);

        if ($order == false) {
            Message::addErrorMessage(Labels::getLabel('LBL_Error:_Please_perform_this_action_on_valid_record.', $this->adminLangId));
            FatUtility::dieJsonError(Message::getHtml());
        }

        if (!$order["order_is_paid"]) {
            $updateArray = array( 'order_deleted' => applicationConstants::YES );
            $whr = array('smt' => 'order_id = ?', 'vals' => array($order_id));

            if (!FatApp::getDb()->updateFromArray(Orders::DB_TBL, $updateArray, $whr)) {
                Message::addErrorMessage(Labels::getLabel('MSG_Invalid_Access', $this->adminLangId));
                FatUtility::dieWithError(Message::getHtml());
            }
        }

        $this->set('msg', Labels::getLabel('LBL_Order_Deleted_Successfully', $this->adminLangId));
        $this->_template->render(false, false, 'json-success.php');
    }

    private function getPaymentForm($langId, $orderId = '')
    {
        $frm = new Form('frmPayment');
        $frm->addHiddenField('', 'opayment_order_id', $orderId);
        $frm->addTextArea(Labels::getLabel('LBL_Comments', $this->adminLangId), 'opayment_comments', '')->requirements()->setRequired();
        $frm->addRequiredField(Labels::getLabel('LBL_Payment_Method', $this->adminLangId), 'opayment_method');
        $frm->addRequiredField(Labels::getLabel('LBL_Txn_ID', $this->adminLangId), 'opayment_gateway_txn_id');
        $frm->addRequiredField(Labels::getLabel('LBL_Amount', $this->adminLangId), 'opayment_amount')->requirements()->setFloatPositive(true);
        $frm->addSubmitButton('&nbsp;', 'btn_submit', Labels::getLabel('LBL_Save_Changes', $this->adminLangId));
        return $frm;
    }

    private function getOrderSearchForm($langId, $deletedOrders = false)
    {
        $currency_id = FatApp::getConfig('CONF_CURRENCY', FatUtility::VAR_INT, 1);
        $currencyData = Currency::getAttributesById($currency_id, array('currency_code', 'currency_symbol_left', 'currency_symbol_right'));
        $currencySymbol = ($currencyData['currency_symbol_left'] != '') ? $currencyData['currency_symbol_left'] : $currencyData['currency_symbol_right'];

        $frm = new Form('frmOrderSearch');
        $keyword = $frm->addTextBox(Labels::getLabel('LBL_Keyword', $this->adminLangId), 'keyword', '', array('id' => 'keyword', 'autocomplete' => 'off'));

        $frm->addTextBox(Labels::getLabel('LBL_Buyer', $this->adminLangId), 'buyer', '');

        $frm->addSelectBox(Labels::getLabel('LBL_Payment_Status', $this->adminLangId), 'order_is_paid', Orders::getOrderPaymentStatusArr($langId), '', array(), Labels::getLabel('LBL_Select_Payment_Status', $this->adminLangId));

        $frm->addDateField('', 'date_from', '', array('placeholder' => 'Date From', 'readonly' => 'readonly' ));
        $frm->addDateField('', 'date_to', '', array('placeholder' => 'Date To', 'readonly' => 'readonly' ));
        $frm->addTextBox('', 'price_from', '', array('placeholder' => 'Order From' . ' [' . $currencySymbol . ']' ));
        $frm->addTextBox('', 'price_to', '', array('placeholder' => 'Order To [' . $currencySymbol . ']' ));

        $frm->addHiddenField('', 'page');
        $frm->addHiddenField('', 'user_id');
        $deleted = ($deletedOrders) ? 1 : 0;
        $frm->addHiddenField('', 'is_deleted', $deleted);
        $fld_submit = $frm->addSubmitButton('&nbsp;', 'btn_submit', Labels::getLabel('LBL_Search', $this->adminLangId));
        $fld_cancel = $frm->addButton("", "btn_clear", Labels::getLabel('LBL_Clear_Search', $this->adminLangId));
        $fld_submit->attachField($fld_cancel);
        return $frm;
    }
}
