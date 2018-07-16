<?
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;

Loc::loadMessages(__FILE__);

$POST_RIGHT = $APPLICATION->GetGroupRight('askron.unicon');
if ($POST_RIGHT == 'D')
    $APPLICATION->AuthForm(Loc::getMessage('ACCESS_DENIED'));

if (isset($_FILES['file'])) {
    $zip = new ZipArchive;
    $res = $zip->open($_FILES['file']['tmp_name']);
    if ($res === true) {
        $zip->extractTo(Application::getDocumentRoot().'/upload/tmp/askron.unicon/');
        $zip->close();
        echo 'ok';
    } else {
        echo "can't open zip";
    }
    exit();
}

$aTabs = array(
    array('DIV' => 'editor', 'TAB' => Loc::getMessage('ASKRON_UNICON_TAB_EDITOR_TITLE'), 'ICON' => '', 'TITLE' => Loc::getMessage('ASKRON_UNICON_EDITOR_TITLE')),
    array('DIV' => 'components', 'TAB' => Loc::getMessage('ASKRON_UNICON_TAB_COMPONENTS_TITLE'), 'ICON' => '', 'TITLE' => Loc::getMessage('ASKRON_UNICON_COMPONENTS_TITLE'))
);
$tabControl = new CAdminTabControl('tabControl', $aTabs, false);

$APPLICATION->SetTitle(Loc::getMessage('ASKRON_UNICON_PAGE_TITLE'));

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
CJSCore::Init(array('jquery'));
$APPLICATION->SetAdditionalCss('/bitrix/css/askron.unicon/editor.css');
$APPLICATION->AddHeadScript('/bitrix/js/askron.unicon/editor.js');
?>
<form method="POST" Action="<?=$APPLICATION->GetCurPage();?>" ENCTYPE="multipart/form-data" name="postform">
<?
$tabControl->Begin();

$tabControl->BeginNextTab();
?>
<div id="text"></div>
<?
$tabControl->BeginNextTab();

$tabControl->Buttons(array(
    'disabled' => ($POST_RIGHT<'W'),
    'back_url' => 'askron.unicon.php?lang=' . LANG
));

$tabControl->End();
?>
</form>
<?require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');?>