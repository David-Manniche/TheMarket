<?php
class FullTextSearchBase
{
	public function getSettings()
    {
        try {
            $keyName = get_called_class()::KEY_NAME;
        } catch (\Error $e) {
            $this->error = 'ERR - ' . $e->getMessage();
            return false;
        }
        return PluginSetting::getConfDataByCode($keyName);
    }
}
