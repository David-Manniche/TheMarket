<?php

class AdvertisementFeedBase extends pluginBase
{
    protected function getUserMeta($key = '')
    {
        return User::getUserMeta(UserAuthentication::getLoggedUserId(), $key);
    }
}
