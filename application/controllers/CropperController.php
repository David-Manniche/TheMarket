<?php
class CropperController extends LoggedUserController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

    public function index()
    {
        $userId = UserAuthentication::getLoggedUserId(true);
        $userImgUpdatedOn = User::getAttributesById($userId, 'user_img_updated_on');
        $uploadedTime = AttachedFile::setTimeParam($userImgUpdatedOn);
        $userImage = FatCache::getCachedUrl(CommonHelper::generateFullUrl('image', 'user', array($userId)).$uploadedTime, CONF_IMG_CACHE_TIME, '.jpg');
        $editMode = false;
        $file_row = AttachedFile::getAttachment(AttachedFile::FILETYPE_USER_PROFILE_IMAGE, $userId);
        if ($file_row != false) {
            $editMode = true;
        }

        $this->set('userImage', $userImage);
        $this->set('editMode', $editMode);
        $this->_template->render(false, false);
    }
}
