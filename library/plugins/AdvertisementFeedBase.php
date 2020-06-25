<?php

class AdvertisementFeedBase extends PluginBase
{
    protected function getUserMeta($key = '')
    {
        return User::getUserMeta(UserAuthentication::getLoggedUserId(), $key);
    }
}
