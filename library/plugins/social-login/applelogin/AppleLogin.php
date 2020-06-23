<?php

class AppleLogin extends SocialMediaAuthBase
{
    public const KEY_NAME = __CLASS__;
    private const PRODUCTION_URL = 'https://appleid.apple.com/auth/';

    public $requiredKeys = ['client_id'];

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
        return true;
    }
    
    /**
     * getRequestUri
     *
     * @return string
     */
    public function getRequestUri(): string
    {
        return static::PRODUCTION_URL . 'authorize?' . http_build_query([
            'response_type' => 'code id_token',
            'response_mode' => 'form_post',
            'client_id' => $this->settings['client_id'],
            'redirect_uri' => $this->getRedirectUri(),
            'state' => $_SESSION['appleSignIn']['state'],
            'scope' => 'name email',
        ]);
    }
    
    /**
     * getRedirectUri
     *
     * @return string
     */
    public function getRedirectUri(): string
    {
        return CommonHelper::generateFullUrl(static::KEY_NAME, 'index', array(), '', false);
    }
}
