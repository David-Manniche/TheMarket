<?php

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

class FacebookLogin extends SocialMediaAuthBase
{
    public const KEY_NAME = __CLASS__;

    public $requiredKeys = [
        'app_id',
        'app_secret'
    ];
    
    private $fbAuthObj;
    private $helper;
    private $response = [];
    private $accessToken = '';

    /**
     * __construct
     *
     * @param  int $langId
     * @return void
     */
    public function __construct(int $langId)
    {
        $this->langId = FatUtility::int($langId);
        if (1 > $this->langId) {
            $this->langId = CommonHelper::getLangId();
        }
    }

    /**
     * init
     *
     * @return void
     */
    public function init(): bool
    {
        if (false == $this->validateSettings($this->langId)) {
            return false;
        }

        $this->fbAuthObj = new Facebook(
            [
            'app_id' => $this->settings['app_id'],
            'app_secret' => $this->settings['app_secret'],
            'default_graph_version' => 'v3.2',
            ]
        );
        $this->helper = $this->fbAuthObj->getRedirectLoginHelper();

        return true;
    }
    
    /**
     * getRequestUri
     *
     * @return string
     */
    public function getRequestUri(): string
    {
        $permissions = ['email', 'public_profile'];
        return $this->helper->getLoginUrl(CommonHelper::generateFullUrl(static::KEY_NAME, 'index', [], '', false), $permissions);
    }
    
    /**
     * getResponse
     *
     * @return array
     */
    private function getResponse(): array
    {
        return $this->response;
    }
    
    /**
     * loadAccessToken
     *
     * @return bool
     */
    public function loadAccessToken(): bool
    {
        try {
            $this->accessToken = $this->helper->getAccessToken();
            if (empty($this->accessToken) || null == $this->accessToken) {
                $this->error = $this->error = Labels::getLabel('MSG_UNABLE_TO_RETRIEVE_ACCESS_TOKEN', $this->langId);
                return false;
            }
        } catch (FacebookResponseException $e) {
            $this->error = Labels::getLabel('MSG_GRAPH_RETURNED_AN_ERROR:_', $this->langId);
            $this->error .= $e->getMessage();
            return false;
        } catch (FacebookSDKException $e) {
            $this->error = Labels::getLabel('MSG_FACEBOOK_SDK_RETURNED_AN_ERROR:_', $this->langId);
            $this->error .= $e->getMessage();
            return false;
        }
        return true;
    }
    
    /**
     * getAccessToken
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * verifyAccessToken
     *
     * @param  mixed $accessToken
     * @return void
     */
    public function verifyAccessToken($accessToken)
    {
        $this->fbAuthObj->setDefaultAccessToken($accessToken);

        try {
            $graphResponse = $this->fbAuthObj->get('/me?fields=id, name, email, first_name, last_name');
            $fbUser = $graphResponse->getGraphUser();
        } catch (FacebookResponseException $e) {
            $this->error = Labels::getLabel('MSG_GRAPH_RETURNED_AN_ERROR:_', $this->langId);
            $this->error .= $e->getMessage();
            return false;
        } catch (FacebookSDKException $e) {
            $this->error = Labels::getLabel('MSG_FACEBOOK_SDK_RETURNED_AN_ERROR:_', $this->langId);
            $this->error .= $e->getMessage();
            return false;
        }
        $this->response = $fbUser;
        return true;
    }
}