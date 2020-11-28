<?php

class PaymentMethodBase extends PluginBase
{
    public $userData;
    public $userMeta;
    
    /**
     * loadLoggedUserInfo
     *
     * @param int $userId
     * @return bool
     */
    public function loadLoggedUserInfo(int $userId): bool
    {
        $this->userId = $userId;
        $this->userId = FatUtility::int($this->userId);
        if (1 > $this->userId) {
            $this->error = Labels::getLabel('MSG_INVALID_USER', $this->langId);
            return false;
        }

        $srch = User::getSearchObject();
        $srch->joinTable(Shop::DB_TBL, 'LEFT OUTER JOIN', 'u.user_id = sh.shop_user_id', 'sh');
        $srch->joinTable(Shop::DB_TBL_LANG, 'LEFT OUTER JOIN', 'sh.shop_id = sh_l.shoplang_shop_id AND shoplang_lang_id = ' . $this->langId, 'sh_l');
        $srch->joinTable(Countries::DB_TBL, 'LEFT OUTER JOIN', 'sh.shop_country_id = c.country_id', 'c');
        $srch->joinTable(Countries::DB_TBL_LANG, 'LEFT OUTER JOIN', 'c.country_id = c_l.countrylang_country_id AND countrylang_lang_id = ' . $this->langId, 'c_l');
        $srch->joinTable(States::DB_TBL, 'LEFT OUTER JOIN', 'sh.shop_state_id = s.state_id', 's');
        $srch->joinTable(States::DB_TBL_LANG, 'LEFT OUTER JOIN', 's.state_id = s_l.statelang_state_id AND statelang_lang_id = ' . $this->langId, 's_l');
        $srch->joinTable(User::DB_TBL_CRED, 'LEFT OUTER JOIN', 'uc.' . User::DB_TBL_CRED_PREFIX . 'user_id = u.user_id', 'uc');
        $srch->joinTable(User::DB_TBL_USR_BANK_INFO, 'LEFT OUTER JOIN', 'ub.ub_user_id = u.user_id', 'ub');
        $srch->addMultipleFields([
            'user_id',
            'user_name',
            'shop_phone',
            'shop_id',
            'credential_email',
            'credential_username',
            'shop_postalcode',
            'IFNULL(country_name, country_code) as country_name',
            'IFNULL(state_name, state_identifier) as state_name',
            'IFNULL(shop_name, shop_identifier) as shop_name',
            'shop_description',
            'user_dob',
            'shop_city',
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
     * loadBaseCurrencyCode
     *
     * @return bool
     */
    protected function loadBaseCurrencyCode(): bool
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
        $resp = User::getUserMeta($this->userId, $key);
        return !empty($key) ? (string) $resp : $resp;
    }
}
