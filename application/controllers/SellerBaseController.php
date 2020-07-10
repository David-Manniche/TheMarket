<?php

class SellerBaseController extends LoggedUserController
{
    public function __construct($action)
    {
        parent::__construct($action);
        /* if( !User::isSeller() ){
        Message::addErrorMessage( Labels::getLabel('MSG_Invalid_Access',$this->siteLangId) );
        FatApp::redirectUser(UrlHelper::generateUrl('account'));
        } */

        if (UserAuthentication::isGuestUserLogged()) {
            Message::addErrorMessage(Labels::getLabel('MSG_Invalid_Access', $this->siteLangId));
            FatApp::redirectUser(UrlHelper::generateUrl('account'));
        }
        
        if (!User::canAccessSupplierDashboard() || !User::isSellerVerified($this->userParentId)) {
            FatApp::redirectUser(UrlHelper::generateUrl('Account', 'supplierApprovalForm'));
        }
        $_SESSION[UserAuthentication::SESSION_ELEMENT_NAME]['activeTab'] = 'S';

        $this->set('bodyClass', 'is--dashboard');
    }
}
