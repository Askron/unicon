<?
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if ($APPLICATION->GetGroupRight('form')>'D')
{
    $aMenu = array(
        'parent_menu' => 'global_menu_content',
        'sort' => 100,
        'url' => 'askron.unicon.php?lang=' . LANGUAGE_ID,
        'text' => Loc::getMessage('ASKRON_UNICON_MENU_TITLE'),
        'title' => Loc::getMessage('ASKRON_UNICON_MENU_HINT'),
        'icon' => 'menu_icon',
        'page_icon' => 'page_icon',
        'module_id' => 'askron.unicon'
    );

    return $aMenu;
}
else
    return false;
?>