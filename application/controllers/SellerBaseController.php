<?php

class SellerBaseController extends LoggedUserController
{
    public $sellerParentId = 0 ;

    public function __construct($action)
    {
        parent::__construct($action);
        /* if( !User::isSeller() ){
        Message::addErrorMessage( Labels::getLabel('MSG_Invalid_Access',$this->siteLangId) );
        FatApp::redirectUser(CommonHelper::generateUrl('account'));
        } */

        if (UserAuthentication::isGuestUserLogged()) {
            Message::addErrorMessage(Labels::getLabel('MSG_Invalid_Access', $this->siteLangId));
            FatApp::redirectUser(CommonHelper::generateUrl('account'));
        }
        $userData = User::getAttributesById(UserAuthentication::getLoggedUserId());
        
        $this->sellerParentId = (0 < $userData['user_parent']) ? $userData['user_parent'] : UserAuthentication::getLoggedUserId();
            
        if (!User::canAccessSupplierDashboard() || !User::isSellerVerified($this->sellerParentId)) {
            FatApp::redirectUser(CommonHelper::generateUrl('Account', 'supplierApprovalForm'));
        }
        $_SESSION[UserAuthentication::SESSION_ELEMENT_NAME]['activeTab'] = 'S';
        
        $this->set('bodyClass', 'is--dashboard');

        $this->userPrivilege = UserPrivilege::getInstance();
    }
}
