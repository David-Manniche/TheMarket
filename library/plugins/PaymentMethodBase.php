<?php

class PaymentMethodBase extends pluginBase
{
    public $userId;
    public $userData;
    public $userMeta;
    
    /**
     * loadLoggedUserInfo
     *
     * @return bool
     */
    public function loadLoggedUserInfo(): bool
    {
        $srch = User::getSearchObject();
        $srch->joinTable(Countries::DB_TBL, 'LEFT OUTER JOIN', 'u.user_country_id = c.country_id', 'c');
        $srch->joinTable(Countries::DB_TBL_LANG, 'LEFT OUTER JOIN', 'c.country_id = c_l.countrylang_country_id AND countrylang_lang_id = ' . $this->langId, 'c_l');
        $srch->joinTable(States::DB_TBL, 'LEFT OUTER JOIN', 'u.user_state_id = s.state_id', 's');
        $srch->joinTable(States::DB_TBL_LANG, 'LEFT OUTER JOIN', 's.state_id = s_l.statelang_state_id AND statelang_lang_id = ' . $this->langId, 's_l');
        $srch->joinTable(User::DB_TBL_CRED, 'LEFT OUTER JOIN', 'uc.' . User::DB_TBL_CRED_PREFIX . 'user_id = u.user_id', 'uc');
        $srch->joinTable(User::DB_TBL_USR_BANK_INFO, 'LEFT OUTER JOIN', 'ub.ub_user_id = u.user_id', 'ub');
        $srch->addMultipleFields([
            'user_id',
            'user_name',
            'user_dial_code',
            'user_phone',
            'credential_email',
            'credential_username',
            'IFNULL(country_name, country_code) as country_name',
            'IFNULL(state_name, state_identifier) as state_name',
            'user_dob',
            'user_address1',
            'user_address2',
            'user_zip',
            'user_city',
            'country_code',
            'state_code',
            'ub.*'
        ]);
        $srch->addCondition('user_id', '=', $this->userId);
        $rs = $srch->getResultSet();
        $this->userData = FatApp::getDb()->fetch($rs);
        return true;
    }
    
    /**
     * validateLoggedUser
     *
     * @return bool
     */
    protected function validateLoggedUser(): bool
    {
        $this->userId = UserAuthentication::getLoggedUserId(true);
        $this->userId = FatUtility::int($this->userId);
        if (1 > $this->userId) {
            $this->error = Labels::getLabel('MSG_USER_NOT_LOGGED_IN', $this->langId);
            return false;
        }
        return true;
    }
    
    /**
     * getBaseCurrencyCode
     *
     * @return bool
     */
    protected function getBaseCurrencyCode(): bool
    {
        $currency = Currency::getDefault();
        if (empty($currency)) {
            $this->error = Labels::getLabel('MSG_DEFAULT_CURRENCY_NOT_SET', $this->langId);
            return false;
        }
        $this->systemCurrencyCode = strtoupper($currency['currency_code']);
        return true;
    }
    
    /**
     * updateUserMeta
     *
     * @param  string $key
     * @param  string $value
     * @return bool
     */
    protected function updateUserMeta(string $key, string $value): bool
    {
        $user = new User($this->userId);
        if (false === $user->updateUserMeta($key, $value)) {
            $this->error = $user->getError();
            return false;
        }
        return true;
    }
    
    /**
     * getUserMeta
     *
     * @param  string $key
     * @return void
     */
    protected function getUserMeta(string $key = '')
    {
        return User::getUserMeta($this->userId, $key);
    }
}
