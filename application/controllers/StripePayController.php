<?php

require_once(CONF_INSTALLATION_PATH . 'library/payment-plugins/stripe/init.php');
class StripePayController extends PaymentController{
	
	private 
	$keyName="Stripe",
	$error = false,
	$paymentSettings = false,
	$currencyCode = 'usd';
	
	public function charge($orderId){
		
		if( empty(trim($orderId)) ){
			Message::addErrorMessage(Labels::getLabel('MSG_Invalid_Access',$this->siteLangId));
			CommonHelper::redirectUserReferer();
		}
		
		$this->paymentSettings = $this->getPaymentSettings();
		$stripe = array(
			'secret_key'      => $this->paymentSettings['privateKey'],
			'publishable_key' => $this->paymentSettings['publishableKey']
			);
		$this->set('stripe', $stripe);
		
		if( strlen(trim($this->paymentSettings['privateKey'])) > 0 && strlen(trim($this->paymentSettings['publishableKey'])) > 0 ){
			if( strpos($this->paymentSettings['privateKey'], 'test') !== false || strpos($this->paymentSettings['publishableKey'], 'test') !== false ){ }
			\Stripe\Stripe::setApiKey($stripe['secret_key']);
		} else {
			$this->error = Labels::getLabel('STRIPE_INVALID_PAYMENT_GATEWAY_SETUP_ERROR',$this->siteLangId);
		}
		
		$orderPaymentObj = new OrderPayment($orderId,$this->siteLangId);
		$paymentAmount = $orderPaymentObj->getOrderPaymentGatewayAmount();
		$payableAmount = $this->formatPayableAmount($paymentAmount);
		$orderInfo = $orderPaymentObj->getOrderPrimaryinfo();
		
		if( !$orderInfo['id'] ){
			FatUtility::exitWithErrorCode(404);
		} else if ( $orderInfo && $orderInfo["order_is_paid"] == Orders::ORDER_IS_PENDING ) {
			$this->currencyCode = strtolower($orderInfo["order_currency_code"]);
			$checkPayment = $this->doPayment($payableAmount, $orderInfo);
			$frm=$this->getPaymentForm($orderId);
			$this->set('frm', $frm);
			if($checkPayment){
				$this->set('success', true);
			}
		} else {
			$this->error = Labels::getLabel('MSG_INVALID_ORDER_PAID_CANCELLED',$this->siteLangId);
		}
		$this->set('paymentAmount',  $paymentAmount);
		$this->set('orderInfo', $orderInfo);
		if($this->error){
			$this->set('error', $this->error);
		}
		
		$cancelBtnUrl = CommonHelper::getPaymentCancelPageUrl();
		if( $orderInfo['order_type'] == Orders::ORDER_WALLET_RECHARGE ){
			$cancelBtnUrl = CommonHelper::getPaymentFailurePageUrl();
		}
		$this->set( 'cancelBtnUrl', $cancelBtnUrl );
		$this->set('exculdeMainHeaderDiv', true);
		$this->_template->addCss('css/payment.css');
		$this->_template->render(true,false);
	}
	
	public function checkCardType(){
		$post = FatApp::getPostedData();		
		$res=CommonHelper::validate_cc_number($post['cc']);		
		echo json_encode($res); exit;
	}
	
	private function formatPayableAmount($amount = null){
		if($amount == null) return false;
		$amount = number_format($amount, 2, '.', '');
		return $amount*100;
	}
	
	private function getPaymentSettings(){
		$pmObj=new PaymentSettings($this->keyName);
		return $pmObj->getPaymentSettings();
	}
	
	private function getPaymentForm($orderId){
		
		$frm = new Form('frmPaymentForm',array('id'=>'frmPaymentForm','action'=>CommonHelper::generateUrl('StripePay','charge',array($orderId)), 'class' =>"form form--normal"));
		$frm->addRequiredField(Labels::getLabel('LBL_ENTER_CREDIT_CARD_NUMBER',$this->siteLangId), 'cc_number');
		$frm->addRequiredField(Labels::getLabel('LBL_CARD_HOLDER_NAME',$this->siteLangId), 'cc_owner');
		$data['months'] = applicationConstants::getMonthsArr($this->siteLangId);
		$today = getdate();
		$data['year_expire'] = array();
		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$data['year_expire'][strftime('%Y', mktime(0, 0, 0, 1, 1, $i))] = strftime('%Y', mktime(0, 0, 0, 1, 1, $i));
		}
		$frm->addSelectBox( Labels::getLabel('LBL_EXPIRY_MONTH',$this->siteLangId), 'cc_expire_date_month',$data['months'], '', array(), '' );
		$frm->addSelectBox( Labels::getLabel('LBL_EXPIRY_YEAR',$this->siteLangId), 'cc_expire_date_year', $data['year_expire'], '', array(), '' );
		$frm->addPasswordField(Labels::getLabel('LBL_CVV_SECURITY_CODE',$this->siteLangId), 'cc_cvv')->requirements()->setRequired();
		/* $frm->addCheckBox(Labels::getLabel('LBL_SAVE_THIS_CARD_FOR_FASTER_CHECKOUT',$this->siteLangId), 'cc_save_card','1'); */
		$frm->addSubmitButton('', 'btn_submit', Labels::getLabel('LBL_Pay_Now', $this->siteLangId ));
		
		return $frm;
	}
	
	private function doPayment($payment_amount = null, $orderInfo = null){
		error_reporting(E_ALL);
		ini_set('display_errors',1);
		$this->paymentSettings=$this->getPaymentSettings();
		if( $payment_amount == null || !$this->paymentSettings || $orderInfo['id'] == null ) {
			return false;
		}
		$checkPayment = false;
		if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
			try {
				if (!isset($_POST['stripeToken'])) {
					throw new Exception("The Stripe Token was not generated correctly");
				} else {
					$stripe = array(
						'secret_key'      => $this->paymentSettings['privateKey'],
						'publishable_key' => $this->paymentSettings['publishableKey']
						);
					if( !empty(trim($this->paymentSettings['privateKey'])) && !empty(trim($this->paymentSettings['publishableKey'])) ) {
						\Stripe\Stripe::setApiKey($stripe['secret_key']);
					}
					
					$customer = \Stripe\Customer::create(array(
						  "email" => $orderInfo['customer_email'],
						  "source" => $_POST['stripeToken'],
						));
					$charge = \Stripe\Charge::create(array(
						/* 'source'     => $_POST['stripeToken'], */
						"customer" => $customer->id,
						'amount'   => $payment_amount,
						'currency' => $this->currencyCode,
						));
					$charge = $charge->__toArray();
					
					if(isset($charge['status'])){
						if(strtolower($charge['status']) == 'succeeded'){
							$message = '';
							$message .= 'Id: '.(string)$charge['id']. "&";
							$message .= 'Object: '.(string)$charge['object']. "&";
							$message .= 'Amount: '.(string)$charge['amount']. "&";
							$message .= 'Amount Refunded: '.(string)$charge['amount_refunded']. "&";
							$message .= 'Application Fee: '.(string)$charge['application_fee']. "&";
							$message .= 'Balance Transaction: '.(string)$charge['balance_transaction']. "&";
							$message .= 'Captured: '.(string)$charge['captured']. "&";
							$message .= 'Created: '.(string)$charge['created']. "&";
							$message .= 'Currency: '.(string)$charge['currency']. "&";
							$message .= 'Customer: '.(string)$charge['customer']. "&";
							$message .= 'Description: '.(string)$charge['description']. "&";
							$message .= 'Destination: '.(string)$charge['destination']. "&";
							$message .= 'Dispute: '.(string)$charge['dispute']. "&";
							$message .= 'Failure Code: '.(string)$charge['failure_code']. "&";
							$message .= 'Failure Message: '.(string)$charge['failure_message']. "&";
							$message .= 'Invoice: '.(string)$charge['invoice']. "&";
							$message .= 'Livemode: '.(string)$charge['livemode']. "&";
							$message .= 'Paid: '.(string)$charge['paid']. "&";
							$message .= 'Receipt Email: '.(string)$charge['receipt_email']. "&";
							$message .= 'Receipt Number: '.(string)$charge['receipt_number']. "&";
							$message .= 'Refunded: '.(string)$charge['refunded']. "&";
							$message .= 'Shipping: '.(string)$charge['shipping']. "&";
							$message .= 'Statement Descriptor: '.(string)$charge['statement_descriptor']. "&";
							$message .= 'Status: '.(string)$charge['status']. "&";
							/* Recording Payment in DB */
							$orderPaymentObj=new OrderPayment($orderInfo['id']);
							$orderPaymentObj->addOrderPayment($this->paymentSettings["pmethod_name"],$charge['id'],($payment_amount/100),Labels::getLabel("MSG_Received_Payment",$this->siteLangId),$message);
							/* End Recording Payment in DB */
							$checkPayment = true;
							FatApp::redirectUser(CommonHelper::generateUrl('custom', 'paymentSuccess',array($orderInfo['id'])));
						}else{
							$orderPaymentObj->addOrderPaymentComments($message);
							FatApp::redirectUser(CommonHelper::generateUrl('custom','paymentFailed'));
						}
					}
				}
			} catch (Exception $e) {
				$this->error = $e->getMessage();
			}
		}
		return $checkPayment;
	}
	
}