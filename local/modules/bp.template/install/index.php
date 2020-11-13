<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bp\Template\SettingsTable;

Loc::loadMessages(__FILE__);

class bp_template extends CModule
{
    public function __construct()
    {
        $arModuleVersion = array();
        
        include __DIR__ . '/version.php';

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        
        $this->MODULE_ID = 'bp.template';
        $this->MODULE_NAME = Loc::getMessage('BP_TEMPLATE_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('BP_TEMPLATE_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = Loc::getMessage('BP_TEMPLATE_MODULE_PARTNER_NAME');
        $this->PARTNER_URI = 'http://airmedia.msk.ru';
    }

    public function doInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->installDB();
        $this->InstallEvents();
        $this->InstallFiles();
    }

    public function doUninstall()
    {
        $this->uninstallDB();
        ModuleManager::unregisterModule($this->MODULE_ID);
    }

    public function installDB()
    {
        if (Loader::includeModule($this->MODULE_ID))
        {
            SettingsTable::getEntity()->createDbTable();
        }
    }

    public function uninstallDB()
    {
        if (Loader::includeModule($this->MODULE_ID))
        {
            $connection = Application::getInstance()->getConnection();
            $connection->dropTable(SettingsTable::getTableName());
        }
        $this->UnInstallEvents();
        $this->UnInstallFiles();
    }

    function InstallEvents()
    {
        return true;
    }
    function UnInstallEvents()
    {
        return true;
    }
    function InstallFiles()
    {
        return copy(
            $_SERVER['DOCUMENT_ROOT'].'/local/modules/'.$this->MODULE_ID.'/admin/bp_template_index.php', 
            $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/bp_template_index.php'
        );
    }
    function UnInstallFiles()
    {
        unlink($_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/bp_template_index.php');
        return true;
    }
}
