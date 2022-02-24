<?php

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

if (class_exists('cutter')) {
    return;
}

Loc::loadMessages(__FILE__);

class cutter extends \CModule
{
    public $MODULE_ID = 'cutter';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_CSS;
    public $MODULE_GROUP_RIGHTS = 'Y';
    public $errors = false;

    public function __construct()
    {
        $arModuleVersion = array();

        include(__DIR__ . '/version.php');

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = 'Just test';
        $this->MODULE_DESCRIPTION = 'Just test';
    }

    /**
     * @return bool
     */
    public function InstallDB()
    {
        global $DB, $APPLICATION,$DBType;
        $this->errors = false;
        if(!$DB->Query("SELECT 'x' FROM urlsCutter_table", true))
        {
            $this->errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"]."/local/modules/cutter/install/db/{$DBType}/install.sql");
        }
        if($this->errors !== false)
        {
            $APPLICATION->ThrowException(implode("<br>", $this->errors));
        }
        RegisterModule('cutter');

        return true;
    }

    /**
     * @return bool
     */
    public function InstallEvents()
    {
        return true;
    }

    /**
     * @param array $params
     * @return bool
     */
    public function UnInstallDB($params = array())
    {
        global $DB, $APPLICATION,$DBType;
        $this->errors = false;

        if($DB->Query("SELECT 'x' FROM urlsCutter_table", true))
        {
            $this->errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"]."/local/modules/cutter/install/db/{$DBType}/uninstall.sql");
        }

        if($this->errors !== false)
        {
            $APPLICATION->ThrowException(implode("<br>", $this->errors));
        }
        UnRegisterModule('cutter');
        return true;
    }

    /**
     * @return bool
     */
    public function UnInstallEvents()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function InstallFiles()
    {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/cutter/install/components", $_SERVER["DOCUMENT_ROOT"]."/local/components", true, true);
        return true;
    }

    /**
     * @return bool
     */
    public function UnInstallFiles()
    {
        DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/cutter/install/components", $_SERVER["DOCUMENT_ROOT"]."/local/components");
        return true;
    }

    /**
     * @return void
     */
    public function DoInstall()
    {
        global $USER, $APPLICATION;
        if ($USER->IsAdmin()){
            $this->InstallDB();
            $this->InstallFiles();
        }

    }

    /**
     * @return void
     */
    public function DoUninstall()
    {
        $this->UnInstallDB(false);

    }


}