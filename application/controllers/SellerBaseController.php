<?php

class SellerBaseController extends LoggedUserController
{
    public function __construct($action)
    {
        parent::__construct($action);

        if (UserAuthentication::isGuestUserLogged()) {
            $msg = Labels::getLabel('MSG_INVALID_ACCESS', $this->siteLangId);
            LibHelper::exitWithError($msg, false, true);
            FatApp::redirectUser(UrlHelper::generateUrl('account'));
        }
        
        if (!User::canAccessSupplierDashboard() || !User::isSellerVerified($this->userParentId)) {
            $msg = Labels::getLabel('MSG_INVALID_ACCESS', $this->siteLangId);
            LibHelper::exitWithError($msg, false, true);
            FatApp::redirectUser(UrlHelper::generateUrl('Account', 'supplierApprovalForm'));
        }
        $_SESSION[UserAuthentication::SESSION_ELEMENT_NAME]['activeTab'] = 'S';

        $this->set('bodyClass', 'is--dashboard');
    }
    
    public function imgCropper()
    {
        /* if ($imageType==AttachedFile::FILETYPE_SHOP_LOGO) {
          $attachment = AttachedFile::getAttachment(AttachedFile::FILETYPE_SHOP_LOGO, $shop_id, 0, $lang_id, false);
          $imageFunction = 'shopLogo';
          } else {
          $attachment = AttachedFile::getAttachment(AttachedFile::FILETYPE_SHOP_BANNER, $shop_id, 0, $lang_id, false, $slide_screen);
          $imageFunction = 'shopBanner';
          }
          $this->set('image', UrlHelper::generateUrl('Image', $imageFunction, array($attachment['afile_record_id'], $attachment['afile_lang_id'], '', $attachment['afile_id']))); */
        $this->_template->render(false, false, 'cropper/index.php');
    }
}
