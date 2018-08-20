<?php
class UserGdprRequestsController extends AdminBaseController {
	public function __construct($action) {
		parent::__construct($action);		
		$this->admin_id = AdminAuthentication::getLoggedAdminId();
		$this->canView = $this->objPrivilege->canViewUserRequests($this->admin_id,true);
		$this->canEdit = $this->objPrivilege->canEditUserRequests($this->admin_id,true);
		$this->set("canView",$this->canView);
		$this->set("canEdit",$this->canEdit);	
	}
	
	public function index() {
		$this->objPrivilege->canViewUserRequests();
		$this->_template->render();		
	}
	
	public function userRequestsSearch(){
		$this->objPrivilege->canViewUserRequests();
		$pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
		$page = FatApp::getPostedData( 'page', FatUtility::VAR_INT, 1 );
		if ( $page < 2 ) {
			$page = 1;
		}
		
		$srch = new UserGdprRequestSearch();	
		$srch->joinUser();
		$srch->addMultipleFields(array('user_id','user_name','user_phone','credential_email','credential_username','ureq_id','ureq_status','ureq_type','ureq_date'));
		$srch->addCondition('ureq_deleted','=',applicationConstants::NO);
		$srch->addOrder('ureq_date','DESC');
		
		$srch->setPageNumber($page);
		$srch->setPageSize($pagesize);	
		
		$rs = $srch->getResultSet();
		$records = FatApp::getDb()->fetchAll($rs);			
		
		$userRequestTypeArr = UserGdprRequest::getUserRequestTypesArr($this->adminLangId);
		$userRequestStatusArr = UserGdprRequest::getUserRequestStatusesArr($this->adminLangId);
		$this->set("arr_listing",$records);
		$this->set("userRequestTypeArr",$userRequestTypeArr);
		$this->set("userRequestStatusArr",$userRequestStatusArr);
		$this->set('pageCount',$srch->pages());
		$this->set('page', $page);
		$this->set('pageSize', $pagesize);					
		$this->set('recordCount', $srch->recordCount());
		$this->_template->render(false,false);
	}
	
	public function updateRequestStatus(){
		$this->objPrivilege->canEditUserRequests();
		$post = FatApp::getPostedData();
		if(empty($post)){
			Message::addErrorMessage($this->str_invalid_request);
			FatUtility::dieJsonError(Message::getHtml());
		}
				
		$userReqId = FatUtility::int($post['reqId']);
		$status = FatUtility::int($post['status']);
		
		if( 1 > $userReqId ){
			Message::addErrorMessage($this->str_invalid_request_id);
			FatUtility::dieJsonError(Message::getHtml());
		}
		
		$emailNotificationObj = new EmailHandler();
		if (!$emailNotificationObj->GdprRequestStatusUpdate( $post['reqId'], $this->adminLangId)){
			Message::addErrorMessage(Labels::getLabel($emailNotificationObj->getError(),$this->adminLangId));
			FatUtility::dieJsonError( Message::getHtml() );
		}
		$userRequest = new UserGdprRequest($userReqId);
		if (!$userRequest->updateRequestStatus($status)) {
			Message::addErrorMessage($userRequest->getError());
			FatUtility::dieJsonError( Message::getHtml() );				
		}
		
		$this->set('msg', Labels::getLabel('LBL_Updated_Successfully',$this->adminLangId));
		$this->_template->render(false, false, 'json-success.php');
	}
	
	public function viewUserRequest($userReqId){
		$this->objPrivilege->canViewUserRequests();
		$userReqId = FatUtility::int($userReqId);
		
		if(1 > $userReqId){
			Message::addErrorMessage($this->str_invalid_request_id);
			FatUtility::dieWithError( Message::getHtml() );
		}
		
		$srch = new UserGdprRequestSearch();	
		$srch->joinUser();
		$srch->addMultipleFields(array('user_name','user_phone','credential_email','credential_username','ureq_date','ureq_purpose'));
		$srch->addCondition('ureq_id','=',$userReqId);
		$srch->addCondition('ureq_type','=',UserGdprRequest::TYPE_DATA_REQUEST);
		$srch->addCondition('ureq_deleted','=',applicationConstants::NO);
		$rs = $srch->getResultSet();
		$userRequest = FatApp::getDb()->fetch($rs);	
		if($userRequest==false) {
			Message::addErrorMessage($this->str_invalid_request);
			FatUtility::dieWithError( Message::getHtml() );
		}
		
		$this->set('userRequest',$userRequest);
		$this->_template->render(false, false);	
	}
	
	/* public function deleteUserRequest(){
		$this->objPrivilege->canEditUserRequests();
		$post = FatApp::getPostedData();
		if(empty($post)){
			Message::addErrorMessage($this->str_invalid_request);
			FatUtility::dieJsonError(Message::getHtml());
		}
		
		$userReqId = FatUtility::int($post['reqId']);

		if( 1 > $userReqId ){
			Message::addErrorMessage($this->str_invalid_request_id);
			FatUtility::dieJsonError(Message::getHtml());
		}
		
		$userObj = new UserGdprRequest();
		if (!$userObj->deleteRequest()) {
			Message::addErrorMessage($userObj->getError());
			FatUtility::dieJsonError( Message::getHtml() );				
		}
		
		$this->set('userReqId', $userReqId);
		$this->set('msg', Labels::getLabel('LBL_Updated_Successfully',$this->adminLangId));
		$this->_template->render(false, false, 'json-success.php');
	} */
	
	public function truncateUserData()
	{
		$this->objPrivilege->canEditUserRequests();
		$post = FatApp::getPostedData();
		if(empty($post)){
			Message::addErrorMessage($this->str_invalid_request);
			FatUtility::dieJsonError(Message::getHtml());
		}

		$userId = FatUtility::int($post['userId']);
		$userReqId = FatUtility::int($post['reqId']);
		
		$userObj = new User($userId);
		if (!$userObj->truncateUserInfo()) {
			Message::addErrorMessage(Labels::getLabel("MSG_USER_INFO_COULD_NOT_BE_DELETED",$this->adminLangId) . $userObj->getError());				
			FatUtility::dieJsonError( Message::getHtml());				
		}
		
		$emailNotificationObj = new EmailHandler();
		if (!$emailNotificationObj->GdprRequestStatusUpdate( $post['reqId'], $this->adminLangId)){
			Message::addErrorMessage(Labels::getLabel($emailNotificationObj->getError(),$this->adminLangId));
			FatUtility::dieJsonError( Message::getHtml() );
		}
		
		/* Update request status to complete [ */
		$assignValues = array(
			'ureq_status'=>UserGdprRequest::STATUS_COMPLETE,
			'ureq_approved_date'=>date('Y-m-d H:i:s'),
		);
		
		$userReqObj = new UserGdprRequest($userReqId);
		$userReqObj->assignValues($assignValues);
		if (!$userReqObj->save()) {
			$db->rollbackTransaction();
			Message::addErrorMessage($userReqObj->getError());
			FatUtility::dieJsonError( Message::getHtml() );
		}
		/* ] */
		
		$this->set('userReqId', $userReqId);
		$this->set('msg', Labels::getLabel('LBL_Successfully_Deleted_User_data',$this->adminLangId));
		$this->_template->render(false, false, 'json-success.php');
	}

}