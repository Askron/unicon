<?
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;
use \Bitrix\Main\ModuleManager;
use \Bitrix\Main\IO\Directory;
use \Bitrix\Main\IO\InvalidPathException;

Loc::loadMessages(__FILE__);

class Askron_unicon extends CModule
{
    function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__ . '/version.php');

        $this->MODULE_ID ='askron.unicon';
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('ASKRON_UNICON_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('ASKRON_UNICON_MODULE_DESCRIPTION');

        $this->PARTNER_NAME = Loc::getMessage('ASKRON_UNICON_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('ASKRON_UNICON_PARTNER_URI');
    }

    public function GetPath($documentRoot=true)
    {
        if ($documentRoot)
            return dirname(__DIR__);
        else
            return str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__));
    }

    public function IsVersionCorrect()
    {
        return CheckVersion(ModuleManager::getVersion('main'), '14.00.00');
    }

    function InstallFiles()
    {
        if (!Directory::isDirectoryExists($this->GetPath() . '/admin/'))
            throw new InvalidPathException($this->GetPath() . '/admin/');
        elseif (!Directory::isDirectoryExists($this->GetPath() . '/install/admin/'))
            throw new InvalidPathException($this->GetPath() . '/install/admin/');
        else
            CopyDirFiles($this->GetPath() . '/install/admin/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/');

        if (!Directory::isDirectoryExists($this->GetPath() . '/install/css/'))
            throw new InvalidPathException($this->GetPath() . '/install/css/');
        else
            CopyDirFiles($this->GetPath() . '/install/css/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/css/'. $this->MODULE_ID);

        if (!Directory::isDirectoryExists($this->GetPath() . '/install/js/'))
            throw new InvalidPathException($this->GetPath() . '/install/js/');
        else
            CopyDirFiles($this->GetPath() . '/install/js/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/'. $this->MODULE_ID);

        if (!Directory::isDirectoryExists($this->GetPath() . '/install/fonts/'))
            throw new InvalidPathException($this->GetPath() . '/install/fonts/');
        else
            CopyDirFiles($this->GetPath() . '/install/fonts/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/fonts/'. $this->MODULE_ID);
    }

    function UnInstallFiles()
    {
        if (Directory::isDirectoryExists($this->GetPath() . '/install/admin/'))
            DeleteDirFiles($this->GetPath() . '/install/admin/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/');

        if (Directory::isDirectoryExists($_SERVER['DOCUMENT_ROOT'] . '/bitrix/css/'. $this->MODULE_ID))
            Directory::deleteDirectory($_SERVER['DOCUMENT_ROOT'] . '/bitrix/css/'. $this->MODULE_ID);

        if (Directory::isDirectoryExists($_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/'. $this->MODULE_ID))
            Directory::deleteDirectory($_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/'. $this->MODULE_ID);

        if (Directory::isDirectoryExists($_SERVER['DOCUMENT_ROOT'] . '/bitrix/fonts/'. $this->MODULE_ID))
            Directory::deleteDirectory($_SERVER['DOCUMENT_ROOT'] . '/bitrix/fonts/'. $this->MODULE_ID);
    }

    function DoInstall()
    {
        global $APPLICATION;
        $request = Application::getInstance()->getContext()->getRequest();

        if ($request['step']<2 && $this->IsVersionCorrect())
        {
            $APPLICATION->IncludeAdminFile(Loc::getMessage('ASKRON_UNICON_INSTALL_TITLE'), $this->GetPath() . '/install/step1.php');
        }
        elseif ($request['step']==2)
        {
            $this->InstallFiles();
            ModuleManager::registerModule($this->MODULE_ID);
            $APPLICATION->IncludeAdminFile(Loc::getMessage('ASKRON_UNICON_INSTALL_TITLE'), $this->GetPath() . '/install/step2.php');
        }
    }

    function DoUnInstall()
    {
        global $APPLICATION;
        $request = Application::getInstance()->getContext()->getRequest();

        if ($request['step']<2)
        {
            $APPLICATION->IncludeAdminFile(Loc::getMessage('ASKRON_UNICON_UNINSTALL_TITLE'), $this->GetPath() . '/install/unstep1.php');
        }
        elseif ($request['step']==2)
        {
            $this->UnInstallFiles();
            ModuleManager::unRegisterModule($this->MODULE_ID);
            $APPLICATION->IncludeAdminFile(Loc::getMessage('ASKRON_UNICON_UNINSTALL_TITLE'), $this->GetPath() . '/install/unstep2.php');
        }
    }
}
?>