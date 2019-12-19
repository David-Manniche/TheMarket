<?php
class CropperController extends MyAppController
{
    public function __construct($action)
    {
        parent::__construct($action);
    }

    public function index()
    {
        /*$user = new User(UserAuthentication::getLoggedUserId());
        $this->set('data', $user->getProfileData());*/
        $this->_template->render(false, false);
    }
}
