<?
use \Bitrix\Main\Localization\Loc;

echo CAdminMessage::ShowMessage(array('MESSAGE' => Loc::getMessage('ASKRON_UNICON_UNINSTALLED'), 'TYPE' => 'OK'));
?>
<form action="<?=$APPLICATION->GetCurPage();?>">
    <input type="hidden" name="lang" value="<?=LANGUAGE_ID?>">
    <input type="submit" name="" value="<?=Loc::getMessage("MOD_BACK");?>">
</form>